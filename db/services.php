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
 * Foundation theme.
 *
 * @package    theme_foundation
 * @copyright  2019 G J Barnard.
 * @author     G J Barnard -
 *               {@link https://moodle.org/user/profile.php?id=442195}
 *               {@link https://gjbarnard.co.uk}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

defined('MOODLE_INTERNAL') || die;

$functions = [
    'theme_foundation_output_load_fontawesome_icon_map' => [
        'classname' => 'theme_foundation\output\external',
        'methodname' => 'load_fontawesome_icon_map',
        'description' => 'Load the mapping of names to icons',
        'type' => 'read',
        'loginrequired' => false,
        'ajax' => true,
    ],
    'theme_foundation_user_set_user_preferences' => [
        'classname' => 'theme_foundation\output\external',
        'methodname' => 'set_user_preferences',
        'description' => 'Set user preferences.',
        'type' => 'write',
        'loginrequired' => false,
        'ajax' => true,
    ],
];

$services = [
    'Foundation theme FontAwesome map' => [
        'functions' => ['theme_foundation_output_load_fontawesome_icon_map'],
        'restrictedusers' => 0,
        'enabled' => 1,
    ],
];
