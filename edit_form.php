<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Form for editing HTML block instances.
 *
 * @package   moodlecore
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing Random glossary entry block instances.
 *
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_fn_my_menu_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        
        $options = array('show'=>'Show', 'hide'=>'Hide');
 
        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
 
        // A sample string variable with a default value.
        $mform->addElement('text', 'config_displaytitle', get_string('cfgdisplaytitle', 'block_fn_my_menu'));
        $mform->setDefault('config_displaytitle', '');
        $mform->setType('config_displaytitle', PARAM_MULTILANG); 
        
        $mform->addElement('select', 'config_mycourses', get_string('mycourses', 'block_fn_my_menu'), $options);
        $mform->addElement('select', 'config_messages', get_string('messages', 'block_fn_my_menu'), $options);
        $mform->addElement('select', 'config_profile', get_string('profile', 'block_fn_my_menu'), $options);
        $mform->addElement('select', 'config_blogmenu', get_string('blogmenu', 'block_fn_my_menu'), $options);
        
/*        
$this->config->mycourses; mycourses show hide
$this->config->messages;
$this->config->profile;
$this->config->blogmenu;
$this->config->myfiles;               
*/
 
    }
}