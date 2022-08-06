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


global $CFG;
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class rezzumin_new_text_form extends moodleform
{
    //Add elements to form
    public function definition()
    {
        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('text', 'textTitle', get_string('text_title', 'rezzumin'));
        $mform->setDefault('textTitle', 'My Text Title');

        $mform->addElement('textarea', 'textBody',
            get_string("text_body", "rezzumin"),
            'wrap="virtual" rows="20" cols="50"');// Add elements to your form.
        $mform->setType('textBody', PARAM_TEXT);

        $mform->addElement('text', 'textCoverage', get_string('text_coverage', 'rezzumin'));
        $mform->setType('textCoverage', PARAM_INT);                   // Set type of element.
        $mform->addRule('textCoverage', get_string('text_coverage_error', 'rezzumin'),
            'numeric', 'client', false, false);

        $this->add_action_buttons(true, 'Save Text');
    }

    //Custom validation should be added here
    function validation($data, $files)
    {
        if ($data->textCoverage > 100 or $data->textCoverage < 25) {
            return array()['The text coverage must be between 25 and 100 percent'];
        }
        return array();
    }
}