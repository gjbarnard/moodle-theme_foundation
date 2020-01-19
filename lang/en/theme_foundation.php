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

$string['region-drawer'] = 'Drawer';
$string['region-horizontal'] = 'Horizontal';
$string['region-side-pre'] = 'Left';

$string['stylecover'] = 'Cover';
$string['stylestretch'] = 'Stretch';

// Misc.
$string['gotobottom'] = 'Go to the bottom of the page';
$string['backtotop'] = 'Back to top';

// Settings.
// General.
$string['generalheading'] = 'General';
$string['generalheadingsub'] = 'General settings';
$string['generalheadingdesc'] = 'Configure the general settings for Foundation here.';

$string['favicon'] = 'Custom favicon';
$string['favicondesc'] = 'Upload your own favicon.  It should be an .ico file.';

$string['fav'] = 'FontAwesome 5 Free';
$string['favdesc'] = 'Use FontAwesome 5 Free for icons.  Note: Please see the license in the fonts folder of the theme.';

$string['prescss'] = 'Pre SCSS';
$string['prescssdesc'] = 'State the SCSS that should be used before any other SCSS is added to the theme.';

$string['customscss'] = 'Custom SCSS';
$string['customscssdesc'] = 'Add custom SCSS to the theme.';

// H5P.
$string['hvpheading'] = 'H5P';
$string['hvpheadingsub'] = 'H5P settings';
$string['hvpheadingdesc'] = 'Configure the H5P settings for Foundation here.  They only take effect if the H5P module (moodle.org/plugins/mod_hvp) is installed.';

$string['hvpcustomcss'] = 'H5P Custom CSS';
$string['hvpcustomcssdesc'] = 'Custom CSS for the H5P module.';

$string['hvpfontcss'] = 'H5P Font CSS';
$string['hvpfontcssdesc'] = 'Font CSS for the H5P module.  Place the \'font-face\' declarations here using Moodle \'font:theme\' syntax for the URL and ensure the font file is in the themes font folder.  Then apply with CSS \'font-family\' declarations in the H5P custom CSS setting.';

// Features.
$string['featuresheading'] = 'Features';
$string['featuresheadingsub'] = 'Features settings';
$string['featuresheadingdesc'] = 'Configure the settings for the features of Foundation here.';

$string['alertsheading'] = 'Alerts settings';
$string['alertsheadingdesc'] = 'Set the alerts settings.';

$string['numberofalerts'] = 'Alerts';
$string['numberofalertsdesc'] = 'Number of alerts between {$a->lower} and {$a->upper}.  After changing and \'Saving changes\', refresh the page.';

$string['alertsettingheading'] = 'Alert {$a->number} settings';

$string['enablealert'] = 'Alert {$a->number} enable';
$string['enablealertdesc'] = 'Enable or disable alert {$a->number}.';

$string['alerttype'] = 'Alert {$a->number} type';
$string['alerttypedesc'] = 'Set the appropriate alert type to best inform your users.';
$string['alertprimary'] = 'Primary';
$string['alertsecondary'] = 'Secondary';
$string['alertsuccess'] = 'Success';
$string['alertdanger'] = 'Danger';
$string['alertwarning'] = 'Warning';
$string['alertinfo'] = 'Information';
$string['alertlight'] = 'Light';
$string['alertdark'] = 'Dark';

$string['alerttitle'] = 'Alert {$a->number} title';
$string['alerttitledesc'] = 'Title for alert {$a->number}.  If the title is empty, it will not be shown but any text will.';
$string['alerttext'] = 'Alert {$a->number} text';
$string['alerttextdesc'] = 'Text for alert {$a->number}.';
$string['alertpage'] = 'Alert {$a->number} page';
$string['alertpagedesc'] = 'Show alert {$a->number} on the given page or \'All\'.';

$string['loginbackgroundheading'] = 'Login background image settings';
$string['loginbackgroundheadingdesc'] = 'Set the login background image settings.';
$string['loginbackground'] = 'Login background image';
$string['loginbackgrounddesc'] = 'Upload your own login background image.  Select the style of the image below.';
$string['loginbackgroundstyle'] = 'Login background style';
$string['loginbackgroundstyledesc'] = 'Select the style for the uploaded image.';
$string['loginbackgroundopacity'] = 'Login box background opacity when there is a background image';
$string['loginbackgroundopacitydesc'] = 'Login background opacity for the login box when there is a background image.';

// Syntax highlighter.
$string['syntaxhighlightheading'] = 'Syntax highlighting settings';
$string['syntaxhighlightheadingdesc'] = 'Set the syntax highlighting settings.';
$string['syntaxhighlight'] = 'Activate syntax highlighting';
$string['syntaxhighlightdesc'] = 'Activate syntax highlighting in courses.  When \'Yes\' you will be able to select the desired categories with the \'syntaxhighlightcat\' setting.';
$string['syntaxhighlightpage'] = 'Syntax highlighting help';
$string['syntaxhelpone'] = 'When editing anything (such as a label) with the text editor surround your code with a \'pre\' tag and add the class="brush: alias" where \'alias\' is one of the following:';
$string['syntaxhelptwo'] = 'Brush name';
$string['syntaxhelpthree'] = 'Brush alias';
$string['syntaxhelpfour'] = 'For example:';
$string['syntaxhelpfive'] = 'becomes:';
$string['syntaxhelpsix'] = 'If you cannot see this help on a course then ask the administrator to activate \'Syntax highlighting\' for the courses\' category.';
$string['syntaxhelpseven'] = 'Note: The \'&lt;\' and \'&gt;\' symbols seem to be problematic within in a \'pre\' tag, change them to \'&amp;lt;\' and \'&amp;gt;\' respectively for your code.  More information on';
$string['syntaxhighlightcat'] = 'Syntax highlighting course categories';
$string['syntaxhighlightcatdesc'] = 'Syntax highlighting in courses within the selected categories.  A help button that brings up a popup will be added to the footer of courses within the selected categories for course editors when editing.  To select none, use the \'Ctrl\ key.';
$string['syntaxhighlightversion'] = 'Syntax highlighting version';
$string['syntaxhighlightversiondesc'] = 'Select which version you would like to use.  Choose between 3.0.83 and 4.0.1.';

// Carousels.
$string['frontpagecarouselheading'] = 'Frontpage carousel';
$string['frontpagecarouselheadingsub'] = 'Frontpage carousel settings';
$string['frontpagecarouselheadingdesc'] = 'Configure the settings for the frontpage carousel of Foundation here.';
$string['frontpagecarouselslides'] = 'Frontpage slides';
$string['frontpagecarouselslidesdesc'] = 'Number of frontpage slides between {$a->lower} and {$a->upper}.  After changing and \'Saving changes\', refresh the page.';
$string['frontpageslideno'] = 'Frontpage slide {$a->number}';
$string['frontpageslidenodesc'] = 'Enter the settings for frontpage slide {$a->number}.';
$string['frontpageenableslide'] = 'Frontpage slide {$a->number} enable';
$string['frontpageenableslidedesc'] = 'Enable or disable frontpage slide {$a->number}.';
$string['frontpageslidetitle'] = 'Frontpage slide {$a->number} title';
$string['frontpageslidetitledesc'] = 'Title for frontpage slide {$a->number}.';
$string['frontpageslidecaption'] = 'Frontpage slide {$a->number} caption';
$string['frontpageslidecaptiondesc'] = 'Caption for frontpage slide {$a->number}.';
$string['frontpageslideimage'] = 'Frontpage slide {$a->number} image';
$string['frontpageslideimagedesc'] = 'Image for frontpage slide {$a->number}.';
$string['frontpageslideurl'] = 'Frontpage slide link {$a->number}';
$string['frontpageslideurldesc'] = 'Enter the target destination of frontpage slide {$a->number} image link';
$string['urltarget'] = 'Link target';
$string['urltargetdesc'] = 'Choose how the link should be opened';
$string['urltargetself'] = 'Current page';
$string['urltargetnew'] = 'New page';
$string['urltargetparent'] = 'Parent frame';

// Courses menu.
$string['coursesmenuheading'] = 'Courses menu';
$string['coursesmenuheadingsub'] = 'Courses menu settings';
$string['coursesmenuheadingdesc'] = 'Configure the settings for the courses menu of Foundation here.';
$string['displaymycourses'] = 'Display courses';
$string['displaymycoursesdesc'] = 'Display enrolled courses for users on the \'Navbar\'.';
$string['displayhiddenmycourses'] = 'Display hidden courses';
$string['displayhiddenmycoursesdesc'] = 'Display hidden courses for users in the \'Courses menu\' if they have permission to view hidden courses';
$string['mycoursesorderidorder'] = 'Course ID order';
$string['mycoursesorderidorderdesc'] = 'Course ID order for when \'Course ID\' is set as the \'Course sort order\'.';
$string['mycoursesorderidasc'] = 'Ascending';
$string['mycoursesorderiddes'] = 'Descending';

$string['mycoursesorder'] = 'Courses order';
$string['mycoursesorderdesc'] = 'State how the courses should be ordered.  The course sort order can be is set by the core navigation setting \'navsortmycoursessort\'.';
$string['mycoursesordersort'] = 'Course sort order';
$string['mycoursesorderid'] = 'Course ID';
$string['mycoursesorderlast'] = 'Last accessed time or enrolment start time if never accessed';
$string['mycoursesorderidorder'] = 'Course ID order';
$string['mycoursesorderidorderdesc'] = 'Course ID order for when \'Course ID\' is set as the \'Course sort order\'.';
$string['mycoursesorderidasc'] = 'Ascending';
$string['mycoursesorderiddes'] = 'Descending';
$string['mycoursesmax'] = 'Max courses';
$string['mycoursesmaxdesc'] = 'State up to how many courses should be listed between {$a->lower} and {$a->upper} where \'{$a->lower}\' represents all.';
$string['mycoursesorderenrolbackcolour'] = 'Enrolled and not accessed course background colour';
$string['mycoursesorderenrolbackcolourdesc'] = 'The background colour for enrolled but not accessed courses.  For when \'mycoursesorder\' is set to \'Last accessed...\'.';
$string['mycoursetitle'] = 'Terminology';
$string['mycoursetitledesc'] = 'Change the terminology for the "My courses" menu title.  When \'mycoursesorder\' is set to \'Last accessed...\' then the word \'latest\' will be added.';
$string['mycourses'] = 'My courses';
$string['mylatestcourses'] = 'My latest courses';
$string['myunits'] = 'My units';
$string['mylatestunits'] = 'My latest units';
$string['mymodules'] = 'My modules';
$string['mylatestmodules'] = 'My latest modules';
$string['myclasses'] = 'My classes';
$string['mylatestclasses'] = 'My latest classes';
$string['noenrolments'] = 'You have no current enrolments';

// Modules.
$string['moduleheading'] = 'Module';
$string['moduleheadingsub'] = 'Module settings';
$string['moduleheadingdesc'] = 'Configure the module settings for Foundation here.';

// Dynamic - will this break plugin validation?  If it does then will have to put the theme strings here, but the concept will still be proven for modules.
/* Future code? -> $toolbox = \theme_foundation\toolbox::get_instance();
   $string = array_merge($string, $toolbox->get_lang_strings('en')); */
// Module strings here for now!
$string['swatch'] = 'Swatch';
$string['swatchdesc'] = 'Choose the swatch for the theme.  A \'Swatch\' is a way of changing the look of the theme using a preset list of definitions that you attach a name to.  All swatches are from \'Bootswatch.com\' and licensed under the \'MIT License\'.  Note:  The Google font CDN\'s have been removed due to limitations with the PHP SCSS compiler and I don\'t want to have the complications of updating the privacy too.';
$string['swatchheading'] = 'Swatch';
$string['swatchheadingsub'] = 'Swatch settings';
$string['swatchheadingdesc'] = 'Configure the swatch settings for Foundation here.';

// Accessibility.
$string['navbarmenus'] = 'Navbar menus';
$string['closedrawer'] = 'Close drawer';
$string['opendrawer'] = 'Open drawer';

// Setting foundation_admin_setting_configinteger.
$string['asconfigintlower'] = '{$a->value} is less than the lower range limit of {$a->lower}';
$string['asconfigintupper'] = '{$a->value} is greater than the upper range limit of {$a->upper}';
$string['asconfigintnan'] = '{$a->value} is not a number';

// Privacy.
$string['privacynote'] = 'Note: The Foundation theme stores has settings that pertain to its configuration.  Specific user settings are described in the \'Plugin privacy registry\'.  For the other settings, it is your responsibilty to ensure that no user data is entered in any of the free text fields.  Setting a setting will result in that action being logged within the core Moodle logging system against the user whom changed it, this is outside of the themes control, please see the core logging system for privacy compliance for this.  When uploading images, you should avoid uploading images with embedded location data (EXIF GPS) included or other such personal data.  It would be possible to extract any location / personal data from the images.  Please examine the code carefully to be sure that it complies with your interpretation of your privacy laws.  I am not a lawyer and my analysis is based on my interpretation.  If you have any doubt then remove the theme forthwith.';
$string['privacy:metadata:preference:collapseblock'] = 'The state of the blocks on a page.';
$string['privacy:request:preference:collapseblock'] = 'The user preference "{$a->name}" for block id "{$a->blockid}" has the value "{$a->value}" which represents "{$a->decoded}" for the state of the block.';
$string['privacy:metadata:preference:drawerclosed'] = 'The state of the drawer.';
$string['privacy:request:preference:drawerclosed'] = 'The user preference "{$a->name}" has the value "{$a->value}" which represents "{$a->decoded}" for the state of the drawer.';
