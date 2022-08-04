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


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/folder/backup/moodle2/backup_folder_stepslib.php');

/**
 * Provides the steps to perform one complete backup of the Folder instance
 */
class backup_folder_activity_task extends backup_activity_task {

    /**
     * No specific settings for this activity
     */
    protected function define_my_settings() {
    }

    /**
     * Defines a backup step to store the instance data in the rezzumin.xml file
     */
    protected function define_my_steps() {
        $this->add_step(new backup_rezzumin_activity_structure_step('rezzumin_structure', 'rezzumin.xml'));
    }

    /**
     * Encodes URLs to the index.php and view.php scripts
     *
     * @param string $content some HTML text that eventually contains URLs to the activity instance scripts
     * @return string the content with the URLs encoded
     */
    static public function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot,"/");

        // Link to the list of rezzumin
        $search="/(".$base."\/mod\/rezzumin\/index.php\?id\=)([0-9]+)/";
        $content= preg_replace($search, '$@rezzuminINDEX*$2@$', $content);

        // Link to choice view by moduleid
        $search="/(".$base."\/mod\/rezzumin\/view.php\?id\=)([0-9]+)/";
        $content= preg_replace($search, '$@rezzuminVIEWBYID*$2@$', $content);

        return $content;
    }
}
