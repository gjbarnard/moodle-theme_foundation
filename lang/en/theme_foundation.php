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
 * @copyright  2018 G J Barnard.
 * @author     G J Barnard -
 *               {@link https://moodle.org/user/profile.php?id=442195}
 *               {@link https://gjbarnard.co.uk}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

// phpcs:disable moodle.Files.LangFilesOrdering

$string['choosereadme'] = '
<div class="clearfix">
<h2>Foundation</h2>
<h3>About</h3>
<p>This is a Bootstrap theme that aims to be minimalistic and modern in look.</p>
<h3>Theme Credits</h3>
<p>Author: G J Barnard<br>
Contact: <a href="https://moodle.org/user/profile.php?id=442195" target="_blank">Moodle profile</a><br>
Website: <a href="https://gjbarnard.co.uk" target="_blank">gjbarnard.co.uk</a>
</p>
<h3>More information</h3>
<p><a href="foundation/Readme.md">How to use this theme.</a></p>
</div></div>';

$string['configtitle'] = 'Foundation';
$string['configtabtitle'] = 'Settings';
$string['pluginname'] = 'Foundation';

$string['region-content'] = 'Content';
$string['region-courseend'] = 'Course End';
$string['region-drawer'] = 'Drawer';
$string['region-marketing'] = 'Marketing';
$string['region-poster'] = 'Poster';
$string['region-side-post'] = 'Left';
$string['region-side-pre'] = 'Right';

$string['cachedef_foundationfontawesomeiconmapping'] = 'Caches font awesome icons';

$string['stylebottom'] = 'Bottom';
$string['stylecenter'] = 'Centre';
$string['stylecontain'] = 'Contain';
$string['stylecover'] = 'Cover';
$string['styleleft'] = 'Left';
$string['styleright'] = 'Right';
$string['stylestretch'] = 'Stretch';
$string['styletop'] = 'Top';

// Misc.
$string['addregionblock'] = 'Add block to region';
$string['backtotop'] = 'Back to top';
$string['gotobottom'] = 'Go to the bottom of the page';

// Settings.
// Information.
$string['informationheading'] = 'Information';

$string['themechanges'] = 'Changes';
$string['themereadme'] = 'Readme';
$string['themesupport'] = 'Support';

// General.
$string['generalheading'] = 'General';
$string['generalheadingsub'] = 'General settings';
$string['generalheadingdesc'] = 'Configure the general settings.';

$string['customlogouturl'] = 'Custom logout URL';
$string['customlogouturldesc'] = 'Set a custom URL to use for the \'Log out\' link.  Leave blank for system default.  When set, will only work if Foundation is not installed in $CFG->themedir.';

$string['footerantigravityhorizontaloffset'] = 'Screen anti-gravity horizontal offset';
$string['footerantigravityhorizontaloffsetdesc'] = 'Set the horizontal offset of the anti-gravity buttons on the screen (not the navbar) from the default.  This can be an positive (move right) or negative (move left) number.  The units are pixels.  Note:  Flip this logic for RTL languages.';

$string['footerantigravityverticaloffset'] = 'Screen anti-gravity vertical offset';
$string['footerantigravityverticaloffsetdesc'] = 'Set the vertical offset of the anti-gravity buttons on the screen (not the navbar) from the default.  This can be an positive (move down) or negative (move up) number.  The units are pixels.';

$string['favicon'] = 'Custom favicon';
$string['favicondesc'] = 'Upload a favicon for use on your site using the core setting \'core_admin | favicon\' under \'Site Administration\' -> \'Appearance\' -> \'Logos\'.';

$string['fav'] = 'FontAwesome 6 Free';
$string['favdesc'] = 'Use the themes version of FontAwesome 6 Free for icons.  Note: Please see the license in the fonts folder of the theme.';
$string['favoff'] = 'Core FontAwesome 6 Free version';
$string['fa6name'] = 'Theme FontAwesome 6 Free version';
$string['faiv'] = 'FontAwesome Free v4 shims';
$string['faivdesc'] = 'When using the themes FontAwesome 6 Free for the icons add support for version 4 icon class names.';

$string['courseendblocksperrow'] = 'Course end blocks per row';
$string['courseendblocksperrowdesc'] = 'Number of blocks per row when using the \'Course end\' horizontal block region.  Note: When editing all blocks will be the same size to facilitate ease of use.';

$string['marketingblocksperrow'] = 'Marketing blocks per row';
$string['marketingblocksperrowdesc'] = 'Number of blocks per row when using the \'Marketing\' horizontal block region.  Note: When editing all blocks will be the same size to facilitate ease of use.';

$string['trio'] = 'Three columns';
$string['triodesc'] = 'Use three columns instead of two on two column pages.  The drawer block area does not count as a \'column\' in this terminology.  Note: When changing this setting, the block region(s) might look odd until the page is refreshed.';

$string['blockshowhide'] = 'Show / hide the block';

$string['unaddableblocks'] = 'Unneeded blocks';
$string['unaddableblocksdesc'] = 'The blocks specified will not be listed in the \'Add a block\' menu.  This is a comma separated list without spaces of the block \'folder\' name within the \'blocks\' folder.  If you want to \'Hide\' a block completely, such as the \'Navigation\' block then go to \'Site administration\' -> \'Plugins\' -> \'Blocks\' -> \'Manage blocks\'.';

$string['prescss'] = 'Pre SCSS';
$string['prescssdesc'] = 'State the SCSS that should be used before any other SCSS is added to the theme.';

$string['customscss'] = 'Custom SCSS';
$string['customscssdesc'] = 'Add custom SCSS to the theme.';

// Course.
$string['courseheading'] = 'Course';
$string['courseheadingsub'] = 'Course settings';
$string['courseheadingdesc'] = 'Configure the course settings.';

$string['activitynavigationenabled'] = 'Activity navigation';
$string['activitynavigationenableddesc'] = 'Enabled?';

$string['activitynavigationmodulenames'] = 'Activity navigation module names';
$string['activitynavigationmodulenamesdesc'] = 'Show the module names, set, or previous / next if unset.';

// H5P.
$string['hvpheading'] = 'H5P';
$string['hvpheadingsub'] = 'H5P settings';
$string['hvpheadingdesc'] = 'Configure the H5P settings.  They only take effect if the H5P module (moodle.org/plugins/mod_hvp) is installed.';

$string['hvpcustomcss'] = 'H5P Custom CSS';
$string['hvpcustomcssdesc'] = 'Custom CSS for the H5P module.';

$string['hvpfontcss'] = 'H5P Font CSS';
$string['hvpfontcssdesc'] = 'Font CSS for the H5P module.  Place the \'font-face\' declarations here using Moodle \'font:theme\' syntax for the URL and ensure the font file is in the themes font folder.  Then apply with CSS \'font-family\' declarations in the H5P custom CSS setting.';

// Features.
$string['featuresheading'] = 'Features';
$string['featuresheadingsub'] = 'Features settings';
$string['featuresheadingdesc'] = 'Configure the settings for the features.';

$string['alertsheading'] = 'Alerts settings';
$string['alertsheadingdesc'] = 'Set the alerts settings.';

$string['numberofalerts'] = 'Alerts';
$string['numberofalertsdesc'] = 'Number of alerts between {$a->lower} and {$a->upper}.  After changing click on \'Save changes\' to get the number you set.';

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

$string['brandsheading'] = 'Brand icons settings';
$string['brandsheadingdesc'] = 'Set the brand icon settings.  The brand icon \'name\' is  - this is the FontAwesome name without the prefixing \'fa-\' so \'fa-train\' simply becomes \'train\'.  Depending on the \'fav\' setting to enable use of FontAwsome 5, please look at \'<a href="//fontawesome.com/icons?d=gallery&s=brands&m=free" target="_blank">fontawesome.com/icons?d=gallery&s=brands&m=free</a>\' if set and \'<a href="//fontawesome.com/v4.7.0/icons/#brand" target="_blank">fontawesome.com/v4.7.0/icons/#brand</a>\' if not, for the name of the icon.  The theme will do the rest with the class names.';

$string['numberofbrands'] = 'Brands';
$string['numberofbrandsdesc'] = 'Number of brands between {$a->lower} and {$a->upper}.  After changing click on \'Save changes\' to get the number you set.';

$string['brandsettingheading'] = 'Brand {$a->number} settings';

$string['enablebrand'] = 'Brand {$a->number} enable';
$string['enablebranddesc'] = 'Enable or disable brand {$a->number}.';
$string['brandiconname'] = 'Brand icon {$a->number} name';
$string['brandiconnamedesc'] = 'Icon name for brand {$a->number}.';
$string['brandiconurl'] = 'Brand icon {$a->number} URL';
$string['brandiconurldesc'] = 'URL for brand icon {$a->number}.';

$string['loginbackgroundheading'] = 'Login background image settings';
$string['loginbackgroundheadingdesc'] = 'Set the login background image settings.';
$string['loginbackground'] = 'Login background image';
$string['loginbackgrounddesc'] = 'Upload your own login background image.  Select the style of the image below.';
$string['loginbackgroundstyle'] = 'Login background style';
$string['loginbackgroundstyledesc'] = 'Select the style for the uploaded image.';
$string['loginbackgroundopacity'] = 'Login box background opacity';
$string['loginbackgroundopacitydesc'] = 'Login background opacity for the login box when there is a background image.';

// Syntax highlighter.
$string['syntaxhighlightheading'] = 'Syntax highlighting settings';
$string['syntaxhighlightheadingdesc'] = 'Set the syntax highlighting settings.';
$string['syntaxhighlightremoved'] = 'Syntax highlighting removed';
$string['syntaxhighlightremoveddesc'] = 'The syntax highlighting functionality has been removed.  Please use the \'<a href="https://moodle.org/plugins/filter_synhi" target="_blank">SynHi</a>\' filter instead.';

// Carousels.
$string['frontpagecarouselheading'] = 'Frontpage carousel';
$string['frontpagecarouselheadingsub'] = 'Frontpage carousel settings';
$string['frontpagecarouselheadingdesc'] = 'Configure the settings for the frontpage carousel.';
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

// Header.
$string['headerheading'] = 'Header';
$string['headerheadingsub'] = 'Header settings';
$string['headerheadingdesc'] = 'Configure the header settings.';
$string['header'] = 'Header';
$string['headerdesc'] = 'Header desc.';

$string['headerbackground'] = 'Header background';
$string['headerbackgrounddesc'] = 'Header background image.';
$string['headerbackgroundcourseimage'] = 'Header background course image';
$string['headerbackgroundcourseimagedesc'] = 'If the course summary files contain one or more images, then use the first as a header background image.';

$string['headerbackgroundposition'] = 'Header background position';
$string['headerbackgroundpositiondesc'] = 'Select the position for the uploaded image.';
$string['headerbackgroundstyle'] = 'Header background style';
$string['headerbackgroundstyledesc'] = 'Select the style for the uploaded image.';
$string['headerbackgroundbottomopacity'] = 'Header background bottom opacity.';
$string['headerbackgroundbottomopacitydesc'] = 'Header background bottom opacity when there is a background image.  The colour is set by the \'body-bg\' SCSS variable.';
$string['headerbackgroundtopopacity'] = 'Header background top opacity.';
$string['headerbackgroundtopopacitydesc'] = 'Header background top opacity when there is a background image.  The colour is set by the \'body-bg\' SCSS variable.';

$string['headerbackgroundtopcolour'] = 'Header background top colour';
$string['headerbackgroundtopcolourdesc'] = 'Set the colour of the text for the top of the header when there is a background.  Enter \'-\' to ignore this setting.';
$string['headerbackgroundbottomcolour'] = 'Header background bottom colour';
$string['headerbackgroundbottomcolourdesc'] = 'Set the colour of the text for the bottom of the header when there is a background.  Enter \'-\' to ignore this setting.';

$string['headerlangmenu'] = 'Header language menu';
$string['headerlangmenudesc'] = 'Set the page layouts that you want the language menu to show on.  Use the \'Ctrl\' key to select more than one.  Notes:  Standard Moodle logic may prevent the language menu from showing, in which case this setting will have no effect.  Set the defaults in the theme\'s \'config.php\' file, i.e. "\'options\' => array(\'langmenu\' => true)" for the given layout.';
$string['baselayout'] = "Base";
$string['standardlayout'] = "Standard";
$string['courselayout'] = "Course";
$string['coursecategorylayout'] = "Course category";
$string['incourselayout'] = "In course";
$string['frontpagelayout'] = "Frontpage";
$string['adminlayout'] = "Admin";
$string['mycourseslayout'] = "My courses";
$string['mydashboardlayout'] = "My dashboard";
$string['mypubliclayout'] = "My public";
$string['loginlayout'] = "Login";
$string['popuplayout'] = "Popup";
$string['frametoplayout'] = "Frametop";
$string['embeddedlayout'] = "Embedded";
$string['maintenancelayout'] = "Maintenance";
$string['printlayout'] = "Print";
$string['reportlayout'] = "Report";
$string['securelayout'] = "Secure";
$string['sitelogo'] = "Site logo";

// Breadcrumb.
$string['breadcrumbheadingsub'] = 'Breadcrumb';
$string['breadcrumbheadingdesc'] = 'Configure the settings for the \'Breadcrumb\' navigation here.';
$string['breadcrumbdisplaymycourses'] = 'Display \'My courses\'';
$string['breadcrumbdisplaymycoursesdesc'] = 'Display \'My courses\' in the menu \'Breadcrumb\' when it is not set to be the \'Homepage\' either by the core setting \'defaulthomepage\' (\'Start page for users\') or the user profile preference \'Start page\' when \'defaulthomepage\' is set to \'User preference\'.';

// Menus.
$string['menusheading'] = 'Menus';

// General menus menu.
$string['generalmenuheadingsub'] = 'General';
$string['generalmenuheadingdesc'] = 'Configure the general menu settings.';

$string['navbarposition'] = 'Navbar position';
$string['navbarpositiondesc'] = 'Set the position of the navbar.';

$string['navbarstyle'] = 'Navbar style';
$string['navbarstyledesc'] = 'Light or Dark.';
$string['navbarstyledark'] = 'Dark';
$string['navbarstylelight'] = 'Light';

$string['navbareditswitch'] = 'Edit switch';
$string['navbareditswitchdesc'] = 'Have the edit switch on the navbar.';

$string['navbardisplayicons'] = 'Display icons';
$string['navbardisplayiconsdesc'] = 'Display an icon next to the menu title.';

$string['navbardisplaytitles'] = 'Display titles';
$string['navbardisplaytitlesdesc'] = 'Display the menu title.  Note: Ignored when \'navbardisplayicons\' is unset.';

// Courses menu.
$string['coursesmenuheadingsub'] = 'Courses menu settings';
$string['coursesmenuheadingdesc'] = 'Configure the settings for the courses menu.  Not to be confused with the core \'My courses\' page.';
$string['displaymycourses'] = 'Display courses';
$string['displaymycoursesdesc'] = 'Display courses for users on the \'Navbar\'.  This can be \'Off\', \'My courses menu\', \'My courses page link\' or \'Both\'.';
$string['displaymycoursesoff'] = 'Off';
$string['displaymycoursesmenu'] = 'My courses menu';
$string['displaymycourseslink'] = 'My courses page link';
$string['displaymycoursesboth'] = 'Both';
$string['displayhiddenmycourses'] = 'Display hidden courses';
$string['displayhiddenmycoursesdesc'] = 'Display hidden courses for users in the \'My courses menu\' if they have permission to view hidden courses';
$string['mycoursemanagementoptions'] = 'Course management options';
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

// This course menu.
$string['thiscoursemenuheadingsub'] = 'This course menu settings';
$string['thiscoursemenuheadingdesc'] = 'Configure the settings for the \'This course\' menu.';
$string['displaythiscourse'] = 'Display \'This course\' menu';
$string['displaythiscoursedesc'] = 'Display the \'This course\' menu on the \'Navbar\' in a course.';
$string['thiscourse'] = 'This course';

// Modules.
$string['moduleheading'] = 'Module';
$string['moduleheadingsub'] = 'Module settings';
$string['moduleheadingdesc'] = 'Configure the module settings.';

// Dynamic - will this break plugin validation?  If it does then will have to put the theme strings here, but the concept will still be proven for modules.
/* Future code? -> $toolbox = \theme_foundation\toolbox::get_instance();
   $string = array_merge($string, $toolbox->get_lang_strings('en')); */
// Module strings here for now!
// Swatch.
$string['swatchheading'] = 'Swatch';
$string['swatchheadingsub'] = 'Swatch settings';
$string['swatchheadingdesc'] = 'Configure the swatch settings.';
$string['swatch'] = 'Swatch';
$string['swatchdesc'] = 'Choose the swatch for the theme.  A \'Swatch\' is a way of changing the look of the theme using a preset list of definitions that you attach a name to.  All swatches (bar \'Seventies\' which I created) are from \'Bootswatch.com\' and licensed under the \'MIT License\'.  Note:  The Google font CDN\'s have been removed due to limitations with the PHP SCSS compiler and I don\'t want to have the complications of updating the privacy too.';

// Swatch custom settings.
$string['swatchcustomcolours'] = 'Swatch custom colours';
$string['swatchcustomcoloursdesc'] = 'Activate custom swatch colour settings.  This allows you to set your own colours in the swatch.';
$string['swatchcustomcoloursheading'] = 'Swatch custom colours';
$string['swatchcustomcoloursheadingsub'] = 'Custom swatch colour settings';
$string['swatchcustomcoloursheadingdesc'] = 'Configure the custom swatch colour settings.  The \'current\' is shown in the colour picker box.  Changing the value to \'-\' will use the default value set in the swatch itself not the \'Default\' shown here.';

$string['swatchcustomcolour'] = '{$a} colour';
$string['swatchcustomcolourdesc'] = 'Set the {$a} colour.  Enter \'-\' to ignore this setting.';

$string['swatchcustomtypography'] = 'Swatch custom typography';
$string['swatchcustomtypographydesc'] = 'Activate custom swatch typography settings.  This allows you to set your own typography settings.';
$string['swatchcustomtypographyheading'] = 'Swatch custom typography';
$string['swatchcustomtypographyheadingsub'] = 'Custom swatch typography settings';
$string['swatchcustomtypographyheadingdesc'] = 'Configure the custom swatch typography settings.';

$string['swatchcustomfontsizebase'] = 'Font size base';
$string['swatchcustomfontsizebasedesc'] = 'Set the base font size as a number.  Units are \'rem\' internally.';

$string['swatchcustomlineheightbase'] = 'Line height base';
$string['swatchcustomlineheightbasedesc'] = 'Set the base line height as a number.';

// Accessibility.
$string['navbarmenus'] = 'Navbar menus';
$string['closedrawer'] = 'Close drawer';
$string['opendrawer'] = 'Open drawer';

// Setting foundation_admin_setting_configinteger.
$string['asconfigintlower'] = '{$a->value} is less than the lower range limit of {$a->lower}';
$string['asconfigintupper'] = '{$a->value} is greater than the upper range limit of {$a->upper}';
$string['asconfigintnan'] = '{$a->value} is not a number';

// Properties.
$string['properties'] = 'Import / export settings';
$string['propertiessub'] = 'Current theme settings';
$string['propertiesdesc'] = 'In this section you can import / export current Foundation theme settings (properties) in JSON format. You can also view all current settings on this Moodle installation.';
$string['propertiesproperty'] = 'Property';
$string['propertiesvalue'] = 'Value';
$string['propertiesexport'] = 'Export properties without files<br>as a JSON string';
$string['propertiesexporthelp'] = 'After exporting, copy the JSON from the screen and paste it into the \'putprops\' setting (after clearing it) on the destination installation and click on \'Save changes\'.  A report will then be generated and put in the \'putprops\' setting.  It will list any file settings that need their files manually transferred.';
$string['propertiesexportfilestoo'] = 'Export properties with files<br>as a JSON string';
$string['propertiesexportfilestoohelp'] = 'After exporting, copy the JSON from the screen and paste it into the \'putprops\' setting (after clearing it) on the destination installation and click on \'Save changes\'.  A report will then be generated and put in the \'putprops\' setting.';
$string['propertiesexportfilestoofile'] = 'Export properties with files<br>as a JSON file';
$string['propertiesexportfilestoofilehelp'] = 'After exporting, download the generated JSON file with today\'s date and time from the \'propertyfiles\' setting and upload it into the \'fileputprops\' setting on the destination installation and click on \'Save changes\'.  A report will then be generated and put in the \'putprops\' setting.';
$string['propertiesexportfilestoofilesuccess'] = 'Properties with files as a JSON file exported as {$a}.';
$string['propertiesexportfilestoofilefail'] = 'Maximum properties with files as a JSON file reached.  Please delete one or more.';
$string['propertiesreturn'] = 'Return';
$string['propertyfileadded'] = 'File \'{$a->filename}\' added to setting \'{$a->settingname}\'.';
$string['propertyfileremoved'] = 'File \'{$a->filename}\' removed from setting \'{$a->settingname}\'.';
$string['propertyfiles'] = 'Property files';
$string['propertyfilesdesc'] = 'When the maximum is reached no more will be added until one or more are deleted.';
$string['putpropertiesheading'] = 'Import theme settings';
$string['putpropertiesname'] = 'Import properties';
$string['putpropertiesdesc'] = 'Paste the JSON string and \'Save changes\'.  Warning!  Does not validate setting values and performs a \'Purge all caches\'.';
$string['putpropertiesfilename'] = 'Import properties from a file';
$string['putpropertiesfiledesc'] = 'Drag and drop or upload the JSON file and \'Save changes\'.  Warning!  Does not validate setting values and performs a \'Purge all caches\'.  The report will be in \'putprops\'.';
$string['putpropertyreport'] = 'Report:';
$string['putpropertyversion'] = 'version:';
$string['putpropertyproperties'] = 'Properties';
$string['putpropertyour'] = 'Our';
$string['putpropertiesignorecti'] = 'Ignoring all course title image settings.';
$string['putpropertiesreportfiles'] = 'Remember to upload the following files to their settings:';
$string['putpropertiesreportfileschanged'] = 'Files changed:';
$string['putpropertiessettingsreport'] = 'Settings report:';
$string['putpropertiesvalue'] = '->';
$string['putpropertiesfrom'] = 'from';
$string['putpropertieschanged'] = 'Changed:';
$string['putpropertiesunchanged'] = 'Unchanged:';
$string['putpropertiesadded'] = 'Added:';
$string['putpropertiesignored'] = 'Ignored:';

// Information.
$string['versioninfo'] = 'Release {$a->release}, version {$a->version} on Moodle {$a->moodle}.  Made with {$a->love} in Great Britain.';
$string['love'] = 'love';
$string['versionalpha'] = 'Alpha version - Almost certainly contains bugs.  This is a development version for developers only!  Don\'t even think of installing on a production server!';
$string['versionbeta'] = 'Beta version - Likely to contain bugs.  Ready for testing by administrators on a test server only.';
$string['versionrc'] = 'Release candidate version - May contain bugs.  Check completely on a test server before considering on a production server.';
$string['versionstable'] = 'Stable version - Could contain bugs as there is not enough time left in the universe to find them all.  Check on a test server before installing on your production server, seriously do!  You won\'t regret it and you\'ll be able to customise until you\'re happy, then use the \'Import / export settings\' functionality to transfer the setting values.';

// Privacy.
$string['privacynote'] = 'Note: The Foundation theme stores has settings that pertain to its configuration.  Specific user settings are described in the \'Plugin privacy registry\'.  For the other settings, it is your responsibility to ensure that no user data is entered in any of the free text fields.  Setting a setting will result in that action being logged within the core Moodle logging system against the user whom changed it, this is outside of the themes control, please see the core logging system for privacy compliance for this.  When uploading images, you should avoid uploading images with embedded location data (EXIF GPS) included or other such personal data.  It would be possible to extract any location / personal data from the images.  Please examine the code carefully to be sure that it complies with your interpretation of your privacy laws.  I am not a lawyer and my analysis is based on my interpretation.  If you have any doubt then remove the theme forthwith.';
$string['privacy:metadata:preference:collapseblock'] = 'The state of the blocks on a page.';
$string['privacy:request:preference:collapseblock'] = 'The user preference "{$a->name}" for block id "{$a->blockid}" has the value "{$a->value}" which represents "{$a->decoded}" for the state of the block.';
$string['privacy:metadata:preference:drawerclosed'] = 'The state of the drawer.';
$string['privacy:request:preference:drawerclosed'] = 'The user preference "{$a->name}" has the value "{$a->value}" which represents "{$a->decoded}" for the state of the drawer.';
