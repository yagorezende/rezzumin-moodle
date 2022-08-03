<?php
//    This file is part of Moodle - http://moodle.org/
//
//    Moodle is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    Moodle is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package local_rezzumin
 * @author  Yago Rezende
 * @license https://opensource.org/licenses/BSD-2-Clause
 */

//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class rezzumin_feedback_edit_form extends moodleform
{
    //Add elements to form
    public function definition()
    {
        global $CFG;
        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('text', 'messagetext', get_string('form_feedback_text_label',
            'local_rezzumin')); // Add elements to your form.
        $mform->setType('messagetext', PARAM_NOTAGS);                   // Set type of element.
        $mform->setDefault('messagetext', get_string('form_feedback_text_field_label',
            'local_rezzumin'));
        // Default value.
        $choices = array();
        $choices['0'] = \core\notification::SUCCESS;
        $choices['1'] = \core\notification::WARNING;
        $choices['2'] = \core\notification::ERROR;
        $choices['3'] = \core\notification::INFO;

        $mform->addElement('select', 'messagetype', get_string('form_feedback_type_label',
            'local_rezzumin'), $choices);
        $mform->setDefault('messagetype', '3');

        $this->add_action_buttons();
    }

    //Custom validation should be added here
    function validation($data, $files)
    {
        return array();
    }
}