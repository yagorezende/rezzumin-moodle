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

function httpPost($url, $data)
{
    $content = json_encode($data);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
        array("Content-type: application/json"));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

    $json_response = curl_exec($curl);

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($status != 200) {
        die("Error: call to URL $url failed with status $status, response $json_response, curl_error " .
            curl_error($curl) . ", curl_errno " . curl_errno($curl));
    }
    curl_close($curl);

    return json_decode($json_response, true);
}

function requestSummary($id){
    global $DB, $CFG;
    $raw_text = $DB->get_record('rezzumin_entry_text', array('id' => $id));
    $summary_text = $DB->get_record('rezzumin_summarized_text', array('origin_id' => $id));
    $referal = $CFG->wwwroot . '/mod/rezzumin/register_summary.php';

    $url = 'http://localhost:8181/send_text';
    $data = array(
        'id' => $summary_text->id,
        'text' => $raw_text->body,
        'percent' => $summary_text->coverage,
        'ref' => $referal
    );

    return httpPost($url, $data);
}
