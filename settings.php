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
 * xAPI Activity - Settings file
 *
 * @package    availability_xapiactivity
 * @copyright  2020 tim.stclair@gmail.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $setting = new admin_setting_configtext('availability_xapiactivity/lrsurl',
        new lang_string('lrsurl', 'availability_xapiactivity'),
        '',
        '');
    $settings->add($setting);

    $setting = new admin_setting_configtext('availability_xapiactivity/username',
        new lang_string('username', 'availability_xapiactivity'),
        '',
        '');
    $settings->add($setting);

    $setting = new admin_setting_configpasswordunmask('availability_xapiactivity/password',
        new lang_string('password', 'availability_xapiactivity'),
        '',
        '');
    $settings->add($setting);

    $opts = [
        'basic' => new lang_string('basic', 'availability_xapiactivity'),
        //    'oauth' => new lang_string('oauth', 'availability_xapiactivity')
    ];
    $setting = new admin_setting_configselect('availability_xapiactivity/auth',
            new lang_string('auth', 'availability_xapiactivity'),
            '',
            'basic',
            $opts);
    $settings->add($setting);

    $opts = [
        'mbox' => get_string('email'),
        'idnumber' => get_string('idnumber'),
        'username' => get_string('username'),
        // could be more one day
    ];
    $setting = new admin_setting_configselect('availability_xapiactivity/actorlookup',
            new lang_string('actorlookup', 'availability_xapiactivity'),
            '',
            'mbox',
            $opts);
    $settings->add($setting);

}