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
 */

namespace theme_foundation\module;

use theme_foundation\admin_setting_configselect;
use stdClass;

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
        $settingspages['header'] = [\theme_foundation\toolbox::SETTINGPAGE => new \admin_settingpage(
            'theme_foundation_header',
            get_string('headerheading', 'theme_foundation')
        ), \theme_foundation\toolbox::HASSETTINGS => true, ];

        $settingspages['header'][\theme_foundation\toolbox::SETTINGPAGE]->add(
            new \admin_setting_heading(
                'theme_foundation_headerheading',
                get_string('headerheadingsub', 'theme_foundation'),
                format_text(get_string('headerheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
            )
        );

        // Header background image.
        $name = 'theme_foundation/headerbackground';
        $title = get_string('headerbackground', 'theme_foundation');
        $description = get_string('headerbackgrounddesc', 'theme_foundation');
        $setting = new \theme_foundation\admin_setting_configstoredfiles(
            $name,
            $title,
            $description,
            'headerbackground',
            ['accepted_types' => '*.jpg,*.jpeg,*.png', 'maxfiles' => 1]
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settingspages['header'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

        // Header background course image.
        $name = 'theme_foundation/headerbackgroundcourseimage';
        $title = get_string('headerbackgroundcourseimage', 'theme_foundation');
        $description = get_string('headerbackgroundcourseimagedesc', 'theme_foundation');
        $default = 'no';
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            $default,
            [
                'no' => get_string('no'),
                'yes' => get_string('yes'),
            ]
        );
        $settingspages['header'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

        // Header background style.
        $name = 'theme_foundation/headerbackgroundstyle';
        $title = get_string('headerbackgroundstyle', 'theme_foundation');
        $description = get_string('headerbackgroundstyledesc', 'theme_foundation');
        $default = 'cover';
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            $default,
            [
                'contain' => get_string('stylecontain', 'theme_foundation'),
                'cover' => get_string('stylecover', 'theme_foundation'),
                'stretch' => get_string('stylestretch', 'theme_foundation'),
            ]
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settingspages['header'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

        // Header background position.
        $name = 'theme_foundation/headerbackgroundposition';
        $title = get_string('headerbackgroundposition', 'theme_foundation');
        $description = get_string('headerbackgroundpositiondesc', 'theme_foundation');
        $default = 'center';
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            $default,
            [
                'center' => get_string('stylecenter', 'theme_foundation'),
                'top' => get_string('styletop', 'theme_foundation'),
                'bottom' => get_string('stylebottom', 'theme_foundation'),
                'left' => get_string('styleleft', 'theme_foundation'),
                'right' => get_string('styleright', 'theme_foundation'),
            ]
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settingspages['header'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

        // Header top background opacity setting.
        $name = 'theme_foundation/headerbackgroundtopopacity';
        $title = get_string('headerbackgroundtopopacity', 'theme_foundation');
        $description = get_string('headerbackgroundtopopacitydesc', 'theme_foundation');
        $default = '0.1';
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            $default,
            \theme_foundation\toolbox::$settingopactitychoices
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settingspages['header'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

        // Header bottom background opacity setting.
        $name = 'theme_foundation/headerbackgroundbottomopacity';
        $title = get_string('headerbackgroundbottomopacity', 'theme_foundation');
        $description = get_string('headerbackgroundbottomopacitydesc', 'theme_foundation');
        $default = '0.9';
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            $default,
            \theme_foundation\toolbox::$settingopactitychoices
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settingspages['header'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

        // Header background top colour setting.
        $name = 'theme_foundation/headerbackgroundtopcolour';
        $title = get_string('headerbackgroundtopcolour', 'theme_foundation');
        $description = get_string('headerbackgroundtopcolourdesc', 'theme_foundation');
        $default = '-';
        $defaultcolour = ['colour' => '#ffaabb', 'selector' => '.pageheadingtop', 'element' => 'color'];
        $setting = new \theme_foundation\admin_setting_configcolourpicker(
            $name,
            $title,
            $description,
            $default,
            $defaultcolour
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settingspages['header'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

        // Header background bottom colour setting.
        $name = 'theme_foundation/headerbackgroundbottomcolour';
        $title = get_string('headerbackgroundbottomcolour', 'theme_foundation');
        $description = get_string('headerbackgroundbottomcolourdesc', 'theme_foundation');
        $default = '-';
        $defaultcolour = ['colour' => '#ffaabb', 'selector' => '.pageheadingbottom', 'element' => 'color'];
        $setting = new \theme_foundation\admin_setting_configcolourpicker(
            $name,
            $title,
            $description,
            $default,
            $defaultcolour
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settingspages['header'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

        // Lang menu.
        $name = 'theme_foundation/headerlangmenu';
        $title = get_string('headerlangmenu', 'theme_foundation');
        $description = get_string('headerlangmenudesc', 'theme_foundation');
        $layoutoptions = $toolbox->get_theme_layout_options('foundation');
        unset($layoutoptions['redirect']); // Does not make sense here.
        $defaults = [];
        $layouts = [];
        foreach ($layoutoptions as $key => $value) {
            $layouts[$key] = get_string($key . 'layout', 'theme_foundation');
            if (!empty($value['langmenu'])) {
                $defaults[] = $key;
            }
        }
        $setting = new \admin_setting_configmultiselect(
            $name,
            $title,
            $description,
            $defaults,
            $layouts
        );
        $settingspages['header'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

        $settingspages['header'][\theme_foundation\toolbox::SETTINGPAGE]->add(
            new \admin_setting_heading(
                'theme_foundation_breadcrumbheading',
                get_string('breadcrumbheadingsub', 'theme_foundation'),
                format_text(get_string('breadcrumbheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
            )
        );

        // Breadcrumb my courses.
        $name = 'theme_foundation/breadcrumbdisplaythiscourse';
        $title = get_string('breadcrumbdisplaymycourses', 'theme_foundation');
        $description = get_string('breadcrumbdisplaymycoursesdesc', 'theme_foundation');
        $default = true;
        $setting = new \admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $settingspages['header'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);
    }

    /**
     * Gets the module pre SCSS.
     *
     * @param string $themename The theme name the SCSS is for.
     * @param toolbox $toolbox The toolbox instance.
     *
     * @return string SCSS.
     */
    public function pre_scss($themename, $toolbox) {
        $prescss = '';

        $headerbackgroundbottomcolour = $toolbox->get_setting('headerbackgroundbottomcolour', $themename);
        if ((!empty($headerbackgroundbottomcolour)) && ($headerbackgroundbottomcolour[0] != '-')) {
            $prescss .= '$breadcrumb-divider-color: ' . $headerbackgroundbottomcolour . ';' . PHP_EOL;
        }

        return $prescss;
    }

    /**
     * Gets the module extra SCSS.
     *
     * @param string $themename The theme name the SCSS is for.
     * @param toolbox $toolbox The toolbox instance.
     * @return string SCSS.
     */
    public function extra_scss($themename, $toolbox) {
        $scss = '';

        $headerbackgroundurl = $toolbox->setting_file_url('headerbackground', 'headerbackground', $themename);
        $headerbackgroundcourseimage = ($toolbox->get_setting('headerbackgroundcourseimage', $themename) == 'yes');

        if ((!empty($headerbackgroundurl)) || ($headerbackgroundcourseimage)) {
            $scss .= '#page-header';
            if ((empty($headerbackgroundurl)) && ($headerbackgroundcourseimage)) {
                $scss .= '.hascourseimage';
            }
            $scss .= ' {' . PHP_EOL;

            if (!empty($headerbackgroundurl)) {
                $scss .= 'background-image: url("' . $headerbackgroundurl . '");' . PHP_EOL;
            }
            $scss .= 'background-position: ' . $toolbox->get_setting('headerbackgroundposition', $themename) . ';' . PHP_EOL;
            $headerbackgroundstyle = $toolbox->get_setting('headerbackgroundstyle', $themename);
            if ($headerbackgroundstyle === 'stretch') {
                $headerbackgroundstyle = '100% 100%';
            }
            $scss .= 'background-size: ' . $headerbackgroundstyle . ';' . PHP_EOL;

            $scss .= '.page-header-background-image-overlay {' . PHP_EOL;
            $scss .= 'background-image: linear-gradient(';
            $scss .= 'rgba(red($body-bg), green($body-bg), blue($body-bg), ' .
                $toolbox->get_setting('headerbackgroundtopopacity', $themename) . '), ';
            $scss .= 'rgba(red($body-bg), green($body-bg), blue($body-bg), ' .
                $toolbox->get_setting('headerbackgroundbottomopacity', $themename) . '));';
            $scss .= '}' . PHP_EOL;

            $scss .= '.card {' . PHP_EOL;
            $scss .= 'background-color: transparent;' . PHP_EOL;
            $scss .= '}' . PHP_EOL;

            $scss .= '.breadcrumb-item a,' . PHP_EOL;
            $scss .= '.pageheadingbutton .btn {' . PHP_EOL;
            $scss .= 'color: inherit;' . PHP_EOL;
            $scss .= '}' . PHP_EOL;

            $headerbackgroundtopcolour = $toolbox->get_setting('headerbackgroundtopcolour', $themename);
            if ((!empty($headerbackgroundtopcolour)) && ($headerbackgroundtopcolour[0] != '-')) {
                $scss .= '.pageheadingtop {' . PHP_EOL;
                $scss .= 'color: ' . $headerbackgroundtopcolour . ';' . PHP_EOL;
                $scss .= '}' . PHP_EOL;
            }
            $headerbackgroundbottomcolour = $toolbox->get_setting('headerbackgroundbottomcolour', $themename);
            if ((!empty($headerbackgroundbottomcolour)) && ($headerbackgroundbottomcolour[0] != '-')) {
                $scss .= '.pageheadingbottom {' . PHP_EOL;
                $scss .= 'color: ' . $headerbackgroundbottomcolour . ';' . PHP_EOL;
                $scss .= '}' . PHP_EOL;
            }

            $scss .= '}' . PHP_EOL;
        }

        return $scss;
    }

    /**
     * Wrapper for header elements.
     *
     * @param core_renderer $output The core renderer instance.
     * @param toolbox $toolbox The toolbox.
     * @return string HTML to display the main header.
     */
    public function header($output, $toolbox) {
        global $COURSE, $PAGE, $USER;
        $header = new stdClass();
        if (empty($PAGE->theme->layouts[$PAGE->pagelayout]['options']['nocontextheader'])) {
            $header->contextheader = $output->context_header();
        } else {
            $header->contextheader = '';
        }
        $header->hasbreadcrumb = (empty($PAGE->theme->layouts[$PAGE->pagelayout]['options']['nobreadcrumb']));
        if ($header->hasbreadcrumb) {
            if ((!empty($USER->auth)) && ($USER->auth == 'lti')) {
                $header->breadcrumb = '';
            } else {
                $header->breadcrumb = $output->navbar();
            }
        } else {
            $header->breadcrumb = '';
        }
        $header->pageheadingbutton = $output->page_heading_button();
        $header->courseheader = $output->course_header();
        $header->headeractions = $PAGE->get_header_actions();

        if (($COURSE->id != SITEID) && ($toolbox->get_setting('headerbackgroundcourseimage') == 'yes')) {
            global $CFG;
            if ($COURSE instanceof stdClass) {
                $course = new \core_course_list_element($COURSE);
            } else {
                $course = $COURSE;
            }
            $imageurl = false;
            foreach ($course->get_course_overviewfiles() as $file) {
                $isimage = $file->is_valid_image();
                if ($isimage) {
                    $imageurl = file_encode_url(
                        "$CFG->wwwroot/pluginfile.php",
                        '/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
                        $file->get_filearea() . $file->get_filepath() . $file->get_filename(),
                        !$isimage
                    );
                    break;
                }
            }
            if ($imageurl) {
                $header->courseimage = $imageurl;
            }
        }

        return $output->render_from_template('core/full_header', $header);
    }
}
