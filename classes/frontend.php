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
 * Front-end class.
 *
 * @package availability_xapiactivity
 * @copyright 2020 tim.stclair@gmail.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_xapiactivity;

defined('MOODLE_INTERNAL') || die();

class frontend extends \core_availability\frontend {

    // the $string['name'] names to make available to client-side js, e.g.
    // M.str.availability_xapiactivity.name
    protected function get_javascript_strings() {
        return array('js_verb','js_object','js_label');
    }


    // the result of this function is passed to
    // M.availability_xapiactivity.form.initInner = function(json) {}
    // (must be an array)
    protected function get_javascript_init_params($course, \cm_info $cm = null, \section_info $section = null) {
        return [];
    }

    // whether this restriction type can be added to the course
    // $cm will be null if you are editing to a section
    protected function allow_add($course, \cm_info $cm = null, \section_info $section = null) {

        // no, if it's the frontpage
        if ($course->id === SITEID) {
            return false;
        }

        // no, if editing section 0 itself
        if (is_null($cm) && !is_null($section) && $section->section === 0) {
            return false;
        }

        // yes, if the lrs details have been set
        if (get_config('availability_xapiactivity','lrsurl') !== false &&
            get_config('availability_xapiactivity','username') !== false &&
            get_config('availability_xapiactivity','password') !== false) {
            return true;
        }

        // otherwise no
        return false;
    }
}