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

global $DB;

$PAGE->set_url(new moodle_url('/local/rezzumin/manage.php'));
// TODO: check this context on https://docs.moodle.org/310/en/Context
$PAGE->set_context(\context_system::instance());
$PAGE->set_title("Rezzumin manage");

$messages = $DB->get_records('local_rezzumin_feedback');

echo $OUTPUT->header();

$template_context = (object)[
    'messages' => array_values($messages),
    'edit_url' => $CFG->wwwroot . '/local/rezzumin/edit.php',
];
echo $OUTPUT->render_from_template('local_rezzumin/manage', $template_context);

echo $OUTPUT->footer();