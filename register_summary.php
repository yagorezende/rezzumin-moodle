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

global $DB;

$id = $_POST['id'];
$body = $_POST['body'];

echo "values = id: " . $id . " | body: " . $body;
$record = new stdClass();
$record->id = $id;
$record->body = $body;
$record->status = "done";

if($DB->update_record("rezzumin_summarized_text", $record)) {
    echo "Success!";
} else {
    echo "Fail!";
}