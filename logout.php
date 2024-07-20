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
 * @copyright  2021 G J Barnard.
 * @author     G J Barnard -
 *               {@link https://moodle.org/user/profile.php?id=442195}
 *               {@link https://gjbarnard.co.uk}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 * Adapted from code written by:
 * @copyright  1999 onwards Martin Dougiamas  http://dougiamas.com
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 *
 * Notes:
 *   If /login/logout.php ever changes, then change this!
 *   Only works when Foundaion is not installed in $CFG->themedir.
 */

// Need to ignore code checker complaining about require_login() etc.
// phpcs:disable moodle.Files.RequireLogin.Missing

require_once(__DIR__ . '/../../config.php');

$PAGE->set_url('/theme/foundation/logout.php');
$PAGE->set_context(context_system::instance());

$sesskey = optional_param('sesskey', '__notpresent__', PARAM_RAW); // We want not null default to prevent required sesskey warning.

$loggedin = isloggedin();

if (($loggedin) && (!confirm_sesskey($sesskey))) {
    $PAGE->set_title($SITE->fullname);
    $PAGE->set_heading($SITE->fullname);
    echo $OUTPUT->header();
    echo $OUTPUT->confirm(get_string('logoutconfirm'), new moodle_url(
        $PAGE->url,
        ['sesskey' => sesskey()]
    ), $CFG->wwwroot . '/');
    echo $OUTPUT->footer();
    die;
}

// Logout URL.
$toolbox = \theme_foundation\toolbox::get_instance();
$redirect = $toolbox->get_setting('customlogouturl');

if (!$loggedin) {
    // User has already logged out.
    require_logout();
    redirect($redirect);
}

$authsequence = get_enabled_auth_plugins(); // Auths, in sequence.
foreach ($authsequence as $authname) {
    $authplugin = get_auth_plugin($authname);
    $authplugin->logoutpage_hook();
}

require_logout();

redirect($redirect);
