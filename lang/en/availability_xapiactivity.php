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
 * Language strings.
 *
 * @package availability_xapiactivity
 * @copyright 2020 tim.stclair@gmail.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['description'] = 'Allow only if an external LRS has a particular verb and activity set for this user.';

$string['label_ratify'] = ' (matched)';
$string['label_ratify_not'] = ' (must not be matched)'; // the condition was passed but the condition says "must not"
$string['label_negate'] = ' (not yet matched)';
$string['label_negate_not'] = ' (must not be not yet matched)'; // yes this makes no sense, but is helpful for admins to see the condition reason

$string['missing'] = '(You must supply a label, verb and activity)';

$string['pluginname'] = 'Restriction by xAPI Lookup';
$string['privacy:metadata'] = 'The course completed availability plugin does not store any personal data.';
$string['requires_completed'] = 'You completed this course';
$string['requires_notcompleted'] = 'You did <b>not</b> complete this course';
$string['title'] = 'xAPI Lookup';


$string['lrsurl'] = 'LRS Url';
$string['username'] = 'User / API Key';
$string['password'] = 'Password / Secret Key';
$string['auth'] = 'Authentication method';
$string['actorlookup'] = 'Actor Lookup type';
$string['basic'] = 'Basic Authentication';
$string['oauth'] = 'oAuth Authentication';

$string['js_verb'] = 'Verb';
$string['js_object'] = 'Activity URL';

$string['js_label'] = 'Condition label';