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

require_once('../../config.php');
require_once('lib.php');

global $DB;


try {
    $id = required_param('id', PARAM_INT);
    setcookie("rezzumin_id", $id, time() + 60*60*24);
} catch (coding_exception $e) {
    $id = $_COOKIE['rezzumin_id'];
}

list ($course, $cm) = get_course_and_cm_from_cmid($id, 'rezzumin');
$rezzumin = $DB->get_record('rezzumin', array('id'=> $cm->instance), '*', MUST_EXIST);
var_dump($course->id);
$sql = "SELECT ret.id, ret.title, ret.timestamp, ret.owner_id, ret.course_id, rst.coverage, rst.status, rst.id AS sid
   FROM {rezzumin_entry_text} ret join {rezzumin_summarized_text} rst
     ON ret.id = rst.origin_id WHERE ret.course_id = :courseid";
$params = ['courseid' => $course->id];
$texts = array_values($DB->get_records_sql($sql, $params));
//var_dump($texts);
//die();
$PAGE->set_title("Rezzumin");


echo $OUTPUT->header();

$i=0;
foreach ($texts as $text){
    $text->index = ++$i;
}

$template_context = (object)[
    'display_msg' => $rezzumin->display_msg,
    'new_text_url' => $CFG->wwwroot . '/mod/rezzumin/new_text.php?id='.$id,
    'texts' => array_values($texts),
];

echo $OUTPUT->render_from_template('mod_rezzumin/view', $template_context);

echo $OUTPUT->footer();
