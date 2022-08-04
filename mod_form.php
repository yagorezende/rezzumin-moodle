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
 * Rezzumin file description here.
 *
 * @package    rezzumin
 * @copyright  2022 yagorezende@id.uff.br
 * @author     Yago Rezende
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/rezzumin/lib.php');

class mod_rezzumin_mod_form extends moodleform_mod
{
    function definition()
    {
        global $CFG, $DB, $OUTPUT;

        $mform =& $this->_form;

        $mform->addElement('text', 'display_msg', get_string('add_instance_msg', 'rezzumin'), array('size'=>'64'));
        $mform->setType('display_msg', PARAM_TEXT);
        $mform->addRule('display_msg', 'Required field', 'required');

        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
    }
}