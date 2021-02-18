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
 * @copyright  &copy; 2021-onwards G J Barnard.
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation\module;

defined('MOODLE_INTERNAL') || die();

use html_writer;
use moodle_url;
use theme_foundation\admin_setting_configselect;
use theme_foundation\admin_setting_configinteger;

/**
 * Header module.
 *
 * Implements the header of the theme.
 *
 * @copyright  &copy; 2021-onwards G J Barnard.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class header_module extends \theme_foundation\module_basement {

    /**
     * Add the header settings.
     *
     * @param array $settingspages The setting pages.
     * @param toolbox $toolbox The theme toolbox.
     */
    public function add_settings(&$settingspages, $toolbox) {
        // Create our own settings page.
        $settingspages['header'] = array(\theme_foundation\toolbox::SETTINGPAGE => new \admin_settingpage('theme_foundation_header',
            get_string('headerheading', 'theme_foundation')), \theme_foundation\toolbox::HASSETTINGS => true);

        $settingspages['header'][\theme_foundation\toolbox::SETTINGPAGE]->add(
            new \admin_setting_heading(
                'theme_foundation_headerheading',
                get_string('headerheadingsub', 'theme_foundation'),
                format_text(get_string('headerheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
            )
        );
    }

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function header($output) {
        global $PAGE;
        $header = new \stdClass();
        if (empty($PAGE->theme->layouts[$PAGE->pagelayout]['options']['nocontextheader'])) {
            $header->contextheader = $output->context_header();
        } else {
            $header->contextheader = '';
        }
        $header->hasbreadcrumb = (empty($PAGE->theme->layouts[$PAGE->pagelayout]['options']['nobreadcrumb']));
        if ($header->hasbreadcrumb) {
            $header->breadcrumb = $output->navbar();
        } else {
            $header->breadcrumb = '';
        }
        $header->pageheadingbutton = $output->page_heading_button();
        $header->courseheader = $output->course_header();
        $header->headeractions = $PAGE->get_header_actions();
        return $output->render_from_template('core/full_header', $header);
    }
}
