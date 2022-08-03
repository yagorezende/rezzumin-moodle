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

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/rezzumin/classes/form/edit.php');

global $DB;

$PAGE->set_url(new moodle_url('/local/rezzumin/edit.php'));
// TODO: check this context on https://docs.moodle.org/310/en/Context
$PAGE->set_context(\context_system::instance());
$PAGE->set_title("Rezzumin Edit");
// operations before render

//Instantiate simplehtml_form
$mform = new rezzumin_feedback_edit_form();

// [ BEGIN OF FORM ]
//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    // On cancel return to manage page
    redirect($CFG->wwwroot . '/local/rezzumin/manage.php', 'You cancel the feedback form');
} else if ($fromform = $mform->get_data()) {
    // Insert the data into our database table
    $recordToInsert = new stdClass();
    $recordToInsert->content = $fromform->messagetext;
    $recordToInsert->msg_type = $fromform->messagetype;

    $DB->insert_record('local_rezzumin_feedback', $recordToInsert);
    // Redirect on success
    redirect($CFG->wwwroot . '/local/rezzumin/manage.php', 'You\'ve created a new feedback with message ' .
        $recordToInsert->content);
}

// [ BEGIN OF FORM ]
// Render part
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();