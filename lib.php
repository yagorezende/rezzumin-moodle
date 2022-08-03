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

function local_rezzumin_before_footer()
{
    global $DB, $USER;

    $sql = "SELECT lm.id , lm.content, lm.msg_type
       FROM {local_rezzumin_feedback} lm left outer join {local_rezzumin_feedback_read} lmr 
         ON lm.id = lmr.feedback_id WHERE lmr.user_id <> :userid or lmr.user_id IS NULL";

    $params = ['userid' => $USER->id];
    $messages = array_values($DB->get_records_sql($sql, $params));

    foreach ($messages as $message) {
        switch ($message->msg_type) {
            case '0':
                $level = \core\notification::SUCCESS;
                break;
            case '1':
                $level = \core\notification::WARNING;
                break;
            case '2':
                $level = \core\notification::ERROR;
                break;
            default:
                $level = \core\notification::INFO;
                break;
        }
        \core\notification::add($message->content, $level);

        $readrecord = new stdClass();
        $readrecord->feedback_id = $message->id;
        $readrecord->user_id = $USER->id;
        $readrecord->timestamp = time();
        $DB->insert_record('local_rezzumin_feedback_read', $readrecord);
    }
}