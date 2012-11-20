<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $options = array('show'=>'Show', 'hide'=>'Hide');

    $settings->add(new admin_setting_configtext('block_fn_my_menu_mycoursesname', get_string('mycoursesname','block_fn_my_menu'), '', get_string('mycourses', 'block_fn_my_menu'), PARAM_RAW));                        
    $settings->add(new admin_setting_configselect('block_fn_my_menu_mycoursesdef', get_string('mycourses', 'block_fn_my_menu'), '', 'all', $options));
    $settings->add(new admin_setting_configselect('block_fn_my_menu_messagesdef', get_string('messages', 'block_fn_my_menu'), '', 'all', $options));
    $settings->add(new admin_setting_configselect('block_fn_my_menu_profiledef', get_string('profile', 'block_fn_my_menu'), '', 'all', $options));
    $settings->add(new admin_setting_configselect('block_fn_my_menu_blogmenudef', get_string('blogmenu', 'block_fn_my_menu'), '', 'all', $options));

}