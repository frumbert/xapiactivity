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
 * Condition main class.
 *
 * @package availability_xapiactivity
 * @copyright 2020 tim.stclair@gmail.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_xapiactivity;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->dirroot . '/availability/condition/xapiactivity/classes/tincan/autoload.php');

/**
 * Condition main class.
 *
 * @package availability_xapiactivity
 * @copyright 2015 iplusacademy (www.iplusacademy.org)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class condition extends \core_availability\condition {

    protected $allowed;

    protected $lrsUrl;
    protected $lrsUser;
    protected $lrsPass;

    protected $verb;
    protected $activity;
    protected $label;

    /**
     * Constructor.
     *
     * @param \stdClass $structure Data structure from JSON decode
     * @throws \coding_exception If invalid data.
     */
    public function __construct($structure) {

        $this->allowed = false;

        if (($this->lrsUrl = get_config('availability_xapiactivity','lrsurl')) === false) {
            throw new \coding_exception('Missing lrs url for profile condition');
        }

        if (($this->lrsUser = get_config('availability_xapiactivity','username')) === false) {
            throw new \coding_exception('Missing lrs username for profile condition');
        }

        if (($this->lrsPass = get_config('availability_xapiactivity','password')) === false) {
            throw new \coding_exception('Missing lrs password for profile condition');
        }

        if (isset($structure->verb) && is_string($structure->verb)) {
            $this->verb = $structure->verb;
        } else {
            throw new \coding_exception('Missing or invalid ->verb for profile condition');
        }

        if (isset($structure->activity) && is_string($structure->activity)) {
            $this->activity = $structure->activity;
        } else {
            throw new \coding_exception('Missing or invalid ->activity for profile condition');
        }

        if (isset($structure->label) && is_string($structure->label)) {
            $this->label = $structure->label;
        } else {
            throw new \coding_exception('Missing or invalid ->activity for profile condition');
        }
    }

    /**
     * Saves tree data back to a structure object.
     *
     * @return \stdClass Structure object (ready to be made into JSON format)
     */
    public function save() {
        return (object)[
            'type' => 'xapiactivity',
            'verb' => $this->verb,
            'activity' => $this->activity,
            'label' => $this->label
        ];
    }

    /**
     * Determines whether a particular item is currently available
     * according to this availability condition.
     *
     * @param bool $not Set true if we are inverting the condition
     * @param info $info Item we're checking
     * @param bool $grabthelot Performance hint: if true, caches information
     *   required for all course-modules, to make the front page and similar
     *   pages work more quickly (works only for current user)
     * @param int $userid User ID to check availability for
     * @return bool True if available
     */
    public function is_available($not, \core_availability\info $info, $grabthelot, $userid) {
        global $USER;

        $statements = $this->tincanlaunch_get_statements($this->lrsUrl,
                                                        $this->lrsUser,
                                                        $this->lrsPass,
                                                        '1.0.1',
                                                        $this->activity,
                                                        $this->verb,
                                                        $userid);

        if (property_exists($statements, "content")) {
            if (is_array($statements->content) && count($statements->content) > 0) {
                $this->allowed = true; // there is content when this actor has this verb for this activity
            }
        }

        if ($not) { // when $not is true, this means "must not match the condition"
            return (!$this->allowed);
        } else { // when $not is false, this means "must match the condition"
            return ($this->allowed === true);
        }

    }

    /**
     * Obtains a string describing this restriction (whether or not
     * it actually applies). Used to obtain information that is displayed to
     * students if the activity is not available to them, and for staff to see
     * what conditions are.
     *
     * @param bool $full Set true if this is the 'full information' view
     * @param bool $not Set true if we are inverting the condition
     * @param info $info Item we're checking
     * @return string Information string (for admin) about all restrictions on
     *   this item
     */
    public function get_description($full, $not, \core_availability\info $info) {

        if ($this->allowed) {
            if ($not) {
                return $this->label . get_string('label_ratify_not', 'availability_xapiactivity');
            } else {
                return $this->label . get_string('label_ratify', 'availability_xapiactivity');
            }
        } else {
            if ($not) {
                return $this->label . get_string('label_negate_not', 'availability_xapiactivity');
            } else {
                return $this->label . get_string('label_negate', 'availability_xapiactivity');
            }
        }
    }

    /**
     * Obtains a representation of the options of this condition as a string,
     * for debugging.
     *
     * @return string Text representation of parameters
     */
    protected function get_debug_string() {
        return $this->allowed ? '#' . 'True' : 'False';
    }

    /**
     * Fetches Statements from the LRS. This is used for completion tracking - we check for a statement matching certain criteria for each learner.
     * cut n paste n modified from https://github.com/barrymckay397/moodle-mod_tincanlaunch/blob/master/lib.php
     *
     * @package  mod_tincanlaunch
     * @category tincan
     * @param string $url LRS endpoint URL
     * @param string $basicLogin login/key for the LRS
     * @param string $basicPass pass/secret for the LRS
     * @param string $version version of xAPI to use
     * @param string $activityid Activity Id to filter by
     * @param TinCan Agent $agent Agent to filter by
     * @param string $verb Verb Id to filter by
     * @param integer $userid User in moodle to look up
     * @return TinCan LRS Response
     */
    protected function tincanlaunch_get_statements($url, $basicLogin, $basicPass, $version, $activityid, $verb, $userid)
    {


        $lrs = new \TinCan\RemoteLRS($url, $version, $basicLogin, $basicPass);

        $statementsQuery = array(
            "agent" => $this->tincan_getactor($userid),
            "verb" => new \TinCan\Verb(array("id"=> trim($verb))),
            "activity" => new \TinCan\Activity(array("id"=> trim($activityid))),
            // "related_activities" => true, // "false",
            //"limit" => 1, //Use this to test the "more" statements feature
            //"format"=>"ids"
        );

        //Get all the statements from the LRS
        $statementsResponse = $lrs->queryStatements($statementsQuery);

        if ($statementsResponse->success == false) {
            return $statementsResponse;
        }

        $allTheStatements = $statementsResponse->content->getStatements();
        $moreStatementsURL = $statementsResponse->content->getMore(); // returns an empty, not a null
        // while (!is_null($moreStatementsURL)) {
        while (!empty($moreStatementsURL)) {
            $moreStmtsResponse = $lrs->moreStatements($moreStatementsURL);
            if ($moreStmtsResponse->success == false) {
                return $moreStmtsResponse;
            }
            $moreStatements = $moreStmtsResponse->content->getStatements();
            $moreStatementsURL = $moreStmtsResponse->content->getMore();
            //Note: due to the structure of the arrays, array_merge does not work as expected.
            foreach ($moreStatements as $moreStatement) {
                array_push($allTheStatements, $moreStatement);
            }
        }
        return new \TinCan\LRSResponse(
            $statementsResponse->success,
            $allTheStatements,
            $statementsResponse->httpResponse
        );
    }

    /**
     * Build a TinCan Agent based on the specified user
     * cut n paste n modified from https://github.com/barrymckay397/moodle-mod_tincanlaunch/blob/master/lib.php
     *
     * @package  mod_tincanlaunch
     * @category tincan
     * @return TinCan Agent $agent Agent
     */
    protected function tincan_getactor($userid = 0)
    {
        global $USER, $CFG;

        if ($userid > 0) {
            $user = \core_user::get_user($userid);
        } else {
            $user = $USER;
        }

        $setting = get_config('availability_xapiactivity','actorlookup');

        if ($USER->idnumber && $setting === 'idnumber') {
            $homepage = new moodle_url('/user/profile.php', ['id' => $user->id]);
            return array(
                "name" => fullname($user),
                "account" => array(
                    "homePage" => $homepage,
                    "name" => $user->idnumber
                ),
                "objectType" => "Agent"
            );
        } elseif ($user->email && $setting === 'mbox') {
            $agent = array(
                "name" => fullname($user), // "imacingridson"
                "mbox" => "mailto:" . $user->email,   // "imacingridson@invalid.url"
                "objectType" => "Agent"
            );
        } else {
            $agent = array(
                "name" => fullname($user),
                "account" => array(
                    "homePage" => $CFG->wwwroot,
                    "name" => $user->username
                ),
                "objectType" => "Agent"
            );
        }

        return new \TinCan\Agent($agent);
    }


}