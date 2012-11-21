<?php

class block_fn_my_menu extends block_base {
    
    
    

    function init() {
        global $CFG;
        
        $this->title = get_string('pluginname','block_fn_my_menu');
        $this->content_type = BLOCK_TYPE_TEXT;
        $this->version = 2007120100;

        if (!isset($CFG->block_fn_my_menu_mycoursesdef)) {
            $CFG->block_fn_my_menu_mycoursesdef = 1;
        }
        if (!isset($CFG->block_fn_my_menu_messagesdef)) {
            $CFG->block_fn_my_menu_messagesdef = 1;
        }
        if (!isset($CFG->block_fn_my_menu_profiledef)) {
            $CFG->block_fn_my_menu_profiledef = 1;
        }
        if (!isset($CFG->block_fn_my_menu_blogmenudef)) {
            $CFG->block_fn_my_menu_blogmenudef = 1;
        }

        if (!isset($CFG->block_fn_my_menu_mygradedef)) {
            $CFG->block_fn_my_menu_mygradedef = 1;
        }
        if (!isset($CFG->block_fn_my_menu_id0)) {
            $CFG->block_fn_my_menu_id0 = 'mycourses';
            $CFG->block_fn_my_menu_id1 = 'messages';
            $CFG->block_fn_my_menu_id2 = 'profile';
            $CFG->block_fn_my_menu_id3 = 'blogmenu';
            $CFG->block_fn_my_menu_id4 = 'mygrade';
        }        
    }     
    
    
    
    
    
    
       

    function specialization() {
        global $CFG;

        /// Set up the display title.
        if (!empty($this->config->displaytitle)) {
            $this->title = $this->config->displaytitle;
        } else {
            $this->title = get_string('displaytitle', 'block_fn_my_menu');
        }

        if (!empty($this->instance->pageid)) {
            $this->context = get_context_instance(CONTEXT_COURSE, $this->instance->pageid);
        }
        if (empty($this->context)) {
            $this->context = get_context_instance(CONTEXT_COURSE, SITEID);
        }

        $this->showadmin = has_capability('moodle/course:update', $this->context);
        
    }   
    
    
    
    
    
    
    
    
  
    public function get_content() {
        
        global $USER, $CFG, $THEME, $SITE, $PAGE,  $DB;
        //print_r($this->config);die;
        require_once($CFG->dirroot.'/mod/forum/lib.php');
        require_once($CFG->dirroot.'/course/lib.php');

        //if (!isloggedin() || isguest()) {
        if (!isloggedin()) {
            return false;
        }
        
        //$this->course = $DB->get_record('course', array('id' => $this->page->course));
        $this->course = $this->page->course;

        if($this->content !== NULL) {
            return $this->content;
        }

        if ($this->course->format == 'topics') {
            $format = 'topic';
        }
        else {
            $format = 'week';
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        if (!empty($this->message)) {
            if ($this->showadmin) {
                $this->content->text = $this->message;
            }
            return $this->content;
        }

        /// Add the treemenu lib.
        if (!class_exists('HTML_TreeMenu')) {
            require_once($CFG->dirroot.'/blocks/fn_my_menu/HTML_TreeMenu-1.2.0/TreeMenu.php');
            //$PAGE->requires->js('/blocks/fn_my_menu/HTML_TreeMenu-1.2.0/TreeMenu.js');
            //require_js($CFG->wwwroot.'/blocks/fn_my_menu/HTML_TreeMenu-1.2.0/TreeMenu.js');
        }

        //start the tree

        /// Prefix the pix directory relative to where the javascript file is physically located
        /// (only relative will work; trust me)
        $reldir = '../../../../';
        $nicon      = $reldir.'blocks/fn_my_menu/icons/folder.gif';
        $eicon      = $reldir.'blocks/fn_my_menu/icons/folder-expanded.gif';

        $this->menu = new HTML_TreeMenu();

        if (file_exists($CFG->dirroot.'/theme/FNmain/pix/breadcrumb.gif')) {
            $homeicon = $reldir.'theme/FNmain/pix/breadcrumb.gif';
            $homeoicon = $reldir.'theme/FNmain/pix/breadcrumb.gif';
        } else {
            $homeicon = $reldir.'blocks/fn_my_menu/icons/home.gif';
            $homeoicon = $reldir.'blocks/fn_my_menu/icons/home.gif';
        }
        $cssclass = 'treeMenuDefault';

        $mnode = new HTML_TreeNode(array('text' => $SITE->shortname, 
                                         'link' => $CFG->wwwroot, 
                                         'icon' => $homeicon, 
                                    'isDynamic' => false,
                                     'cssClass' => $cssclass, 
                                 'expandedIcon' => $homeoicon, 
                                     'expanded' => true, 
                                        'width' => 16, 
                                       'height' => 16));

        //print sections
       
        $id = array();
        $id[0] = isset($this->config->id0) ? $this->config->id0 : $CFG->block_fn_my_menu_id0;
        $id[1] = isset($this->config->id1) ? $this->config->id1 : $CFG->block_fn_my_menu_id1;
        $id[2] = isset($this->config->id2) ? $this->config->id2 : $CFG->block_fn_my_menu_id2;
        $id[3] = isset($this->config->id3) ? $this->config->id3 : $CFG->block_fn_my_menu_id3;
        $id[4] = isset($this->config->id4) ? $this->config->id4 : $CFG->block_fn_my_menu_id4;
        
        if (!isset($this->config)) {
            $this->config = new object();
        }
    

        foreach ($id as $i => $name) {
            if (!isset($this->config->{$name})) {
                $this->config->{$name} = ($CFG->{'block_fn_my_menu_'.$name.'def'} ? 'show' : 'hide');
                $this->config->{$name} = ($CFG->{'block_fn_my_menu_'.$name.'def'} ? 'show' : 'hide');
            }
        }
        //print_r($this->config);die;
        $grouppic = $reldir.'pix/i/group.gif';

        // add them to the tree in the order from configuration
        for ($i = 0; $i <= 4; $i++) {

            if (($id[$i] == "mycourses") && !empty($this->config->mycourses) && ($this->config->mycourses == "show")) {
                //if (!$courses = get_my_courses($USER->id, NULL, 'id,shortname,format,visible')) {
                if (!$courses = $courses = enrol_get_my_courses('id, shortname,format,visible', 'visible DESC,sortorder ASC')) {
                    $courses = array();
                }
                        
                
                $label = !empty($CFG->block_fn_my_menu_mycoursesname) ? $CFG->block_fn_my_menu_mycoursesname : get_string('mycourses', 'block_fn_my_menu');
                
                $cnode = &$mnode->addItem(new HTML_TreeNode(array('text' => ' '.$label, 'link' => '',
                                                                  'icon' => $nicon, 'expandedIcon' => $eicon, 'width' => 16, 'height' => 16)));
                $curl = $CFG->wwwroot.'/course/view.php?id=';
                
                foreach ($courses as $course) {
                    if ((empty($CFG->block_fn_my_menu_courselink) || ($CFG->block_fn_my_menu_courselink != $course->id))) {
                        if ($this->course->id == $course->id) {
                            $class = ' fnmymenu-coursesel';
                        } else {
                            $class = 'fnmymenu-course';
                        }
                        $cname = '<span class="'.$class.'"> '.$course->shortname.'</span>';
                        $cnode->addItem(new HTML_TreeNode(array('text' => $cname, 'link' => $curl.$course->id,
                                          'icon' => $reldir.'blocks/fn_my_menu/icons/courseact.gif',
                                          'expandedIcon' => $reldir.'blocks/fn_my_menu/icons/courseact.gif',
                                          'cssClass' => $cssclass, 'width' => 16, 'height' => 16)));
                    }
                }
            }
            
            if ($id[$i] == "messages") {
                if (!empty($this->config->messages) && $this->config->messages=="show") {
                    if ($nummess = $this->count_unread_messages($USER->id)) {
                        $nummess = ' ('.$nummess.')';
                        $icon = "$reldir/blocks/fn_my_menu/icons/messages_blink.gif";
                    } else {
                        $nummess = '';
                        $icon = "$reldir/blocks/fn_my_menu/icons/messages.gif";
                    }
                    //$link = "\" onclick=\"return openpopup(\'/message/index.php?course={$this->course->id}\', \'message\', \'menubar=0,location=0,scrollbars,status,resizable,width=400,height=500\', 0);\"";
                    $link = $CFG->wwwroot . "/message/index.php?course={$this->course->id}";
                    $mnode->addItem(new HTML_TreeNode(array('text' => ' '.get_string('messages', 'block_fn_my_menu').$nummess,
                                                            'link' => $link,
                                                            'icon' => $icon,
                                                            'expandedIcon' => $icon,
                                                            'cssClass' => $cssclass, 'width' => 16, 'height' => 16)));
                }
            }
            
            if ($id[$i] == "profile") {
                //if (!isguest()) {
                if (true) {
                    if (!empty($this->config->profile) && $this->config->profile=="show") {
                        if ($USER->picture) {
                            $uicon = $reldir.'user/pix.php/'.$USER->id.'/f2.jpg';
                        } else {
                            $uicon = $reldir.'pix/u/f2.png';
                        }
                        $mnode->addItem(new HTML_TreeNode(array('text' => ' '.get_string('profile','block_fn_my_menu'),
                                                                'link' => $CFG->wwwroot.'/user/view.php?id='.$USER->id.'&course='.$this->course->id,
                                                                'icon' => $uicon, 'expandedIcon' => $uicon,
                                                                'cssClass' => $cssclass, 'width' => 16, 'height' => 16)));
                    }
                }
            }
            
            if ($id[$i] == "blogmenu") {
                if (!empty($this->config->blogmenu) && $this->config->blogmenu=="show") {
                    $mnode->addItem(new HTML_TreeNode(array('text' => ' '.get_string('blogmenu', 'block_fn_my_menu'),
                                                            'link' => $CFG->wwwroot.'/blog/index.php?userid='.$USER->id.'&courseid=1',
                                                            'icon' => $reldir.'blocks/fn_my_menu/icons/blog.gif',
                                                            'expandedIcon' => $reldir.'blocks/fn_my_menu/icons/blog.gif',
                                                            'cssClass' => $cssclass, 'width' => 16, 'height' => 16)));
                }
            }
            
            if ($id[$i] == "mygrade") {
                if (!empty($this->config->mygrade) && $this->config->mygrade=="show") {
                    
                    foreach ($courses as $key => $value) {
                        $myfirstcourseid = $key;
                        break;
                    }
                    
                    if($this->course->id > 1){
                        $mygradelink = $CFG->wwwroot.'/grade/report/user/index.php?id='.$this->course->id.'&userid='.$USER->id;
                    }elseif (sizeof($courses)){
                        $mygradelink = $CFG->wwwroot.'/grade/report/overview/index.php?id='.$myfirstcourseid;
                    }else{
                        $mygradelink = $CFG->wwwroot;
                    }
                    
                    $mnode->addItem(new HTML_TreeNode(array('text' => ' '.get_string('mygrade', 'block_fn_my_menu'),
                                                            'link' => $mygradelink,
                                                            'icon' => $reldir.'blocks/fn_my_menu/icons/grades.png',
                                                            'expandedIcon' => $reldir.'blocks/fn_my_menu/icons/grades.png',
                                                            'cssClass' => $cssclass, 'width' => 16, 'height' => 16)));
                }
            }
        }

        $this->menu->addItem($mnode);
        
        
        $treeMenu = new HTML_TreeMenu_DHTML($this->menu, array('images' => $CFG->wwwroot.'/blocks/fn_my_menu/HTML_TreeMenu-1.2.0/images', 'defaultClass' => 'treeMenuDefault'));

        $this->content->text = '<script type="text/javascript" src="'.$CFG->wwwroot.'/blocks/fn_my_menu/HTML_TreeMenu-1.2.0/TreeMenu.js"></script>' . $treeMenu->toHTML();

        return $this->content;
    }


    
    
    
    
    
    
    
    
    
    function count_unread_messages($userid=0) {
        global $CFG, $USER, $DB;

        if ($userid == 0) {
            $userid = $USER->id;
        }

        return $DB->count_records_sql("SELECT COUNT(m.useridfrom) as count
                                  FROM {$CFG->prefix}message m
                                  WHERE m.useridto = '$userid'");
    }     

}
?>