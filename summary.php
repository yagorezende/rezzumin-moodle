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
 * @license    https://opensource.org/licenses/BSD-2-Clause BSD-2 or later
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


$text_id = required_param('summary', PARAM_INT);

$record = $DB->get_record('rezzumin_summarized_text', array('id' => $text_id));
$original = $DB->get_record('rezzumin_entry_text', array('id' => $record->origin_id));

$PAGE->set_title($original->title);

echo $OUTPUT->header();

echo "<h1>" . $original->title . " (Summary):</h1>";
echo "<p>" . $record->body . ".</p>";

echo $OUTPUT->footer();
