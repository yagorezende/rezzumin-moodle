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
require_once('process_text.php');
require_once($CFG->dirroot . '/mod/rezzumin/classes/form/new_text.php');

global $DB, $USER;

$PAGE->set_url(new moodle_url('/mod/rezzumin/new_text.php'));
$PAGE->set_title("Rezzumin Activity Text");
// operations before render

//Instantiate simplehtml_form
$mform = new rezzumin_new_text_form();

// [ BEGIN OF FORM ]
$id = $_COOKIE['rezzumin_id'];
list ($course, $cm) = get_course_and_cm_from_cmid($id, 'rezzumin');
//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    // On cancel return to manage page
    redirect($CFG->wwwroot . '/mod/rezzumin/view.php?id='.$id, 'You cancel the form');
} else if ($fromform = $mform->get_data()) {
    // Insert the data into our database table
    $recordToInsert = new stdClass();
    $recordToInsert->title = $fromform->textTitle;
    $recordToInsert->body = $fromform->textBody;
    $recordToInsert->owner_id = $USER->id;
    $recordToInsert->course_id = $course->id;
    $recordToInsert->timestamp = time();
//    var_dump($recordToInsert);

    $summarizedRecordToInsert = new stdClass();
    $summarizedRecordToInsert->coverage = $fromform->textCoverage;
    $summarizedRecordToInsert->status = 'processing'; //TODO: change this to import value from const file
    $summarizedRecordToInsert->body = 'Processing...';


    $summarizedRecordToInsert->origin_id = $DB->insert_record('rezzumin_entry_text', $recordToInsert);
    $DB->insert_record('rezzumin_summarized_text', $summarizedRecordToInsert);

    // Request summary
    requestSummary($summarizedRecordToInsert->origin_id);

    // Redirect on success
    redirect($CFG->wwwroot . '/mod/rezzumin/view.php?id=' . $id,
        'Your text is been processed and will be available soon');
}else{
    // [ BEGIN OF FORM ]
    // Render part
    echo $OUTPUT->header();
    $mform->display();
    echo $OUTPUT->footer();
}