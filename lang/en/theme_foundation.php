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
 * @copyright  &copy; 2018-onwards G J Barnard.
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

$string['choosereadme'] = '
<div class="clearfix">
<h2>Foundation</h2>
<h3>About</h3>
<p>Foundation is a basic theme.</p>
<h3>Theme Credits</h3>
<p>Author: G J Barnard<br>
Contact: <a href="http://moodle.org/user/profile.php?id=442195">Moodle profile</a><br>
Website: <a href="http://about.me/gjbarnard">about.me/gjbarnard</a>
</p>
<h3>More information</h3>
<p><a href="foundation/Readme.md">How to use this theme.</a></p>
</div></div>';

$string['configtitle'] = 'Foundation';
$string['pluginname'] = 'Foundation';

$string['region-side-pre'] = 'Left';

// Settings.
$string['generalheading'] = 'General';
$string['generalheadingsub'] = 'General settings';
$string['generalheadingdesc'] = 'Configure the general settings for Foundation here.';

$string['prescss'] = 'Pre SCSS';
$string['prescssdesc'] = 'State the SCSS that should be used before any other SCSS is added to the theme.';

$string['customscss'] = 'Custom SCSS';
$string['customscssdesc'] = 'Add custom SCSS to the theme.';

$string['moduleheading'] = 'Module';
$string['moduleheadingsub'] = 'Module settings';
$string['moduleheadingdesc'] = 'Configure the module settings for Foundation here.';

// Dynamic - will this break plugin validation?  If it does then will have to put the theme strings here, but the concept will still be proven for modules.
$toolbox = \theme_foundation\toolbox::get_instance();
$string = array_merge($string, $toolbox->get_lang_strings('en'));

// Accessibility.
$string['navbarmenus'] = 'Navbar menus';

// Privacy.
$string['privacynote'] = 'Note: The Foundation theme stores has settings that pertain to its configuration.  Specific user settings are described in the \'Plugin privacy registry\'.  For the other settings, it is your responsibilty to ensure that no user data is entered in any of the free text fields.  Setting a setting will result in that action being logged within the core Moodle logging system against the user whom changed it, this is outside of the themes control, please see the core logging system for privacy compliance for this.  When uploading images, you should avoid uploading images with embedded location data (EXIF GPS) included or other such personal data.  It would be possible to extract any location / personal data from the images.  Please examine the code carefully to be sure that it complies with your interpretation of your privacy laws.  I am not a lawyer and my analysis is based on my interpretation.  If you have any doubt then remove the theme forthwith.';
$string['privacy:metadata:preference:collapseblock'] = 'The state of the blocks on a page.';
$string['privacy:request:preference:collapseblock'] = 'The user preference "{$a->name}" for block id "{$a->blockid}" has the value "{$a->value}" which represents "{$a->decoded}" for the state of the block.';
