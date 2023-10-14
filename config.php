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

defined('MOODLE_INTERNAL') || die;

$THEME->doctype = 'html5';
$THEME->name = 'foundation';
$THEME->parents = [];
$THEME->sheets = [];
$THEME->editor_sheets = [];
$THEME->usefallback = true;
$THEME->precompiledcsscallback = 'theme_foundation_get_precompiled_css';
$THEME->enable_dock = false;

$THEME->supportscssoptimisation = false;
$THEME->yuicssmodules = [];

$empty = [];
$regions = ['side-pre', 'drawer'];
if (!empty(\theme_foundation\toolbox::get_config_setting('trio'))) {
    // Have three columns on 'two column' pages, the mustache file will be changed in the core renderer toolbox.
    $regions[] = 'side-post';
}

$THEME->layouts = [
    // Most backwards compatible layout without the blocks - this is the layout used by default.
    'base' => [
        'file' => 'layout.php',
        'mustache' => 'columns2',
        'regions' => $regions,
        'defaultregion' => 'side-pre',
    ],
    // Standard layout with blocks, this is recommended for most pages with general information.
    'standard' => [
        'file' => 'layout.php',
        'mustache' => 'columns2',
        'regions' => $regions,
        'defaultregion' => 'side-pre',
    ],
    // Main course page.
    'course' => [
        'file' => 'layout.php',
        'mustache' => 'columns2',
        'regions' => array_merge($regions, ['courseend']),
        'defaultregion' => 'side-pre',
        'options' => ['langmenu' => true],
    ],
    'coursecategory' => [
        'file' => 'layout.php',
        'mustache' => 'columns2',
        'regions' => $regions,
        'defaultregion' => 'side-pre',
    ],
    'incourse' => [
        'file' => 'layout.php',
        'mustache' => 'columns2',
        'regions' => $regions,
        'defaultregion' => 'side-pre',
    ],
    // The site home page.
    'frontpage' => [
        'file' => 'layout.php',
        'mustache' => 'columns2',
        'regions' => array_merge($regions, ['marketing', 'poster']),
        'defaultregion' => 'side-pre',
        'options' => ['langmenu' => true],
    ],
    // Server administration scripts.
    'admin' => [
        'file' => 'layout.php',
        'mustache' => 'columns2',
        'regions' => $regions,
        'defaultregion' => 'side-pre',
    ],
    // My courses page.
    'mycourses' => [
        'file' => 'layout.php',
        'mustache' => 'columns2',
        'regions' => $regions,
        'defaultregion' => 'side-pre',
        'options' => ['langmenu' => true],
    ],
    // My dashboard page.
    'mydashboard' => [
        'file' => 'layout.php',
        'mustache' => 'columns2',
        'regions' => $regions,
        'defaultregion' => 'side-pre',
        'options' => ['langmenu' => true, 'nocontextheader' => true],
    ],
    // My public page.
    'mypublic' => [
        'file' => 'layout.php',
        'mustache' => 'columns2',
        'regions' => $regions,
        'defaultregion' => 'side-pre',
    ],
    'login' => [
        'file' => 'layout.php',
        'mustache' => 'columns1',
        'regions' => $empty,
        'options' => ['langmenu' => true],
    ],
    // Pages that appear in pop-up windows - no navigation, no blocks, no header.
    'popup' => [
        'file' => 'layout.php',
        'mustache' => 'popup',
        'regions' => $empty,
        'options' => ['nofooter' => true, 'nobreadcrumb' => true, 'nonavbar' => true],
    ],
    // No blocks and minimal footer - used for legacy frame layouts only!
    'frametop' => [
        'file' => 'layout.php',
        'mustache' => 'columns1',
        'regions' => $empty,
        'options' => ['nofooter' => true, 'nocoursefooter' => true],
    ],
    // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible.
    'embedded' => [
        'file' => 'plainlayout.php',
        'mustache' => 'embedded',
        'regions' => $empty,
    ],
    /* Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
       This must not have any blocks, and it is good idea if it does not have links to
       other places - for example there should not be a home link in the footer... */
    'maintenance' => [
        'file' => 'maintenance.php',
        'mustache' => 'maintenance',
        'regions' => $empty,
    ],
    // Should display the content and basic headers only.
    'print' => [
        'file' => 'layout.php',
        'mustache' => 'columns1',
        'regions' => $empty,
        'options' => ['nofooter' => true, 'nobreadcrumb' => false, 'nonavbar' => true],
    ],
    // The pagelayout used when a redirection is occuring.
    'redirect' => [
        'file' => 'plainlayout.php',
        'mustache' => 'embedded',
        'regions' => $empty,
    ],
    // The pagelayout used for reports.
    'report' => [
        'file' => 'layout.php',
        'mustache' => 'columns2',
        'regions' => $regions,
        'defaultregion' => 'side-pre',
    ],
    // The pagelayout used for safebrowser and securewindow.
    'secure' => [
        'file' => 'layout.php',
        'mustache' => 'secure',
        'regions' => $regions,
        'defaultregion' => 'side-pre',
        'options' => ['nofooter' => true],
    ],
];

$THEME->rendererfactory = '\\theme_foundation\\renderer_factory';

$THEME->prescsscallback = 'theme_foundation_pre_scss';
$THEME->scss = function (theme_config $theme) {
    $toolbox = \theme_foundation\toolbox::get_instance();
    $scss = $toolbox->get_main_scss_content($theme);

    return $scss;
};
$THEME->extrascsscallback = 'theme_foundation_extra_scss';

$THEME->iconsystem = '\\theme_foundation\\output\\icon_system_fontawesome';
$THEME->haseditswitch = \theme_foundation\toolbox::get_config_setting('navbareditswitch');
$THEME->usescourseindex = (file_exists("$CFG->dirroot/blocks/course_index/block_course_index.php"));
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_DEFAULT;
