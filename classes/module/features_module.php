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

namespace theme_foundation\module;

use theme_foundation\admin_setting_configselect;

/**
 * Features module.
 *
 * Implements the features of the theme.
 *
 * @copyright  &copy; 2019-onwards G J Barnard.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class features_module extends \theme_foundation\module_basement implements \templatable {
    /**
     * Add the features settings.
     *
     * @param array $settingspages The setting pages.
     * @param toolbox $toolbox The theme toolbox.
     */
    public function add_settings(&$settingspages, $toolbox) {
        // Create our own settings page.
        $settingspages['features'] = [\theme_foundation\toolbox::SETTINGPAGE => new \admin_settingpage(
            'theme_foundation_features',
            get_string('featuresheading', 'theme_foundation')
        ), \theme_foundation\toolbox::HASSETTINGS => true, ];

        $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add(
            new \admin_setting_heading(
                'theme_foundation_featuresheading',
                get_string('featuresheadingsub', 'theme_foundation'),
                format_text(get_string('featuresheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
            )
        );

        // Alerts.
        // Alerts heading.
        $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add(
            new \admin_setting_heading(
                'theme_foundation_alerts_heading',
                get_string('alertsheading', 'theme_foundation'),
                format_text(get_string('alertsheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
            )
        );

        // Number of alerts.
        $name = 'theme_foundation/numberofalerts';
        $title = get_string('numberofalerts', 'theme_foundation');
        $default = 0;
        $lower = 0;
        $upper = 4;
        $description = get_string(
            'numberofalertsdesc',
            'theme_foundation',
            ['lower' => $lower, 'upper' => $upper]
        );
        $choices = [];
        for ($c = $lower; $c <= $upper; $c++) {
            $choices['' . $c] = $c;
        }
        $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
        $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

        $numberofalerts = $toolbox->get_setting('numberofalerts', 'foundation'); // Stick to ours or could be confusing!
        if ($numberofalerts > 0) {
            for ($alertnum = 1; $alertnum <= $numberofalerts; $alertnum++) {
                // Alert X setting heading.
                $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add(
                    new \admin_setting_heading(
                        'theme_foundation_alert_' . $alertnum . '_heading',
                        get_string('alertsettingheading', 'theme_foundation', ['number' => $alertnum]),
                        ''
                    )
                );

                // Alert enabled.
                $name = 'theme_foundation/enablealert' . $alertnum;
                $title = get_string('enablealert', 'theme_foundation', ['number' => $alertnum]);
                $description = get_string('enablealertdesc', 'theme_foundation', ['number' => $alertnum]);
                $default = false;
                $setting = new \admin_setting_configcheckbox($name, $title, $description, $default, true, false);
                $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

                // Alert type.
                $name = 'theme_foundation/alerttype' . $alertnum;
                $title = get_string('alerttype', 'theme_foundation', ['number' => $alertnum]);
                $description = get_string('alerttypedesc', 'theme_foundation');
                $default = 'info';
                $choices = [
                    'danger' => get_string('alertdanger', 'theme_foundation'),
                    'dark' => get_string('alertdark', 'theme_foundation'),
                    'info' => get_string('alertinfo', 'theme_foundation'),
                    'light' => get_string('alertlight', 'theme_foundation'),
                    'primary' => get_string('alertprimary', 'theme_foundation'),
                    'secondary' => get_string('alertsecondary', 'theme_foundation'),
                    'success' => get_string('alertsuccess', 'theme_foundation'),
                    'warning' => get_string('alertwarning', 'theme_foundation'),
                ];
                $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
                $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

                // Alert title.
                $name = 'theme_foundation/alerttitle' . $alertnum;
                $title = get_string('alerttitle', 'theme_foundation', ['number' => $alertnum]);
                $description = get_string('alerttitledesc', 'theme_foundation', ['number' => $alertnum]);
                $default = '';
                $setting = new \admin_setting_configtext($name, $title, $description, $default);
                $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

                // Alert text.
                $name = 'theme_foundation/alerttext' . $alertnum;
                $title = get_string('alerttext', 'theme_foundation', ['number' => $alertnum]);
                $description = get_string('alerttextdesc', 'theme_foundation', ['number' => $alertnum]);
                $default = '';
                $setting = new \admin_setting_confightmleditor($name, $title, $description, $default);
                $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

                // Alert pages on.
                $name = 'theme_foundation/alertpage' . $alertnum;
                $title = get_string('alertpage', 'theme_foundation', ['number' => $alertnum]);
                $description = get_string('alertpagedesc', 'theme_foundation', ['number' => $alertnum]);
                $default = 'frontpage';
                $choices = [
                    'all' => get_string('all'),
                    'course' => get_string('course'),
                    'mydashboard' => get_string('myhome'),
                    'frontpage' => get_string('frontpage', 'admin'),
                ];
                $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
                $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);
            }
        }

        // Brands.
        // Brands heading.
        $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add(
            new \admin_setting_heading(
                'theme_foundation_brands_heading',
                get_string('brandsheading', 'theme_foundation'),
                format_text(get_string('brandsheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
            )
        );

        // Number of brands.
        $name = 'theme_foundation/numberofbrands';
        $title = get_string('numberofbrands', 'theme_foundation');
        $default = 0;
        $lower = 0;
        $upper = 8;
        $description = get_string(
            'numberofbrandsdesc',
            'theme_foundation',
            ['lower' => $lower, 'upper' => $upper]
        );
        $choices = [];
        for ($c = $lower; $c <= $upper; $c++) {
            $choices['' . $c] = $c;
        }
        $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
        $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

        $numberofbrands = $toolbox->get_setting('numberofbrands', 'foundation'); // Stick to ours or could be confusing!
        if ($numberofbrands > 0) {
            for ($brandnum = 1; $brandnum <= $numberofbrands; $brandnum++) {
                // Brand X setting heading.
                $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add(
                    new \admin_setting_heading(
                        'theme_foundation_brand_' . $brandnum . '_heading',
                        get_string('brandsettingheading', 'theme_foundation', ['number' => $brandnum]),
                        ''
                    )
                );

                // Brand enabled.
                $name = 'theme_foundation/enablebrand' . $brandnum;
                $title = get_string('enablebrand', 'theme_foundation', ['number' => $brandnum]);
                $description = get_string('enablebranddesc', 'theme_foundation', ['number' => $brandnum]);
                $default = false;
                $setting = new \admin_setting_configcheckbox($name, $title, $description, $default, true, false);
                $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

                // Brand icon name.
                $name = 'theme_foundation/brandiconname' . $brandnum;
                $title = get_string('brandiconname', 'theme_foundation', ['number' => $brandnum]);
                $description = get_string('brandiconnamedesc', 'theme_foundation', ['number' => $brandnum]);
                $default = '';
                $setting = new \admin_setting_configtext($name, $title, $description, $default);
                $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

                // Brand icon URL.
                $name = 'theme_foundation/brandiconurl' . $brandnum;
                $title = get_string('brandiconurl', 'theme_foundation', ['number' => $brandnum]);
                $description = get_string('brandiconurldesc', 'theme_foundation', ['number' => $brandnum]);
                $default = '';
                $setting = new \admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
                $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);
            }
        }

        // Login background image heading.
        $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add(
            new \admin_setting_heading(
                'theme_foundation_loginbackground_heading',
                get_string('loginbackgroundheading', 'theme_foundation'),
                format_text(get_string('loginbackgroundheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
            )
        );

        // Login background image.
        $name = 'theme_foundation/loginbackground';
        $title = get_string('loginbackground', 'theme_foundation');
        $description = get_string('loginbackgrounddesc', 'theme_foundation');
        $setting = new \theme_foundation\admin_setting_configstoredfiles(
            $name,
            $title,
            $description,
            'loginbackground',
            ['accepted_types' => '*.jpg,*.jpeg,*.png', 'maxfiles' => 1]
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

        // Login background style.
        $name = 'theme_foundation/loginbackgroundstyle';
        $title = get_string('loginbackgroundstyle', 'theme_foundation');
        $description = get_string('loginbackgroundstyledesc', 'theme_foundation');
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
        $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

        // Overridden course title text background opacity setting.
        $name = 'theme_foundation/loginbackgroundopacity';
        $title = get_string('loginbackgroundopacity', 'theme_foundation');
        $description = get_string('loginbackgroundopacitydesc', 'theme_foundation');
        $default = '0.8';
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            $default,
            \theme_foundation\toolbox::$settingopactitychoices
        );
        $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

        // Syntax highlighting.
        // Syntax highlighting heading.
        $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add(
            new \admin_setting_heading(
                'theme_foundation_syntaxhighlight_heading',
                get_string('syntaxhighlightheading', 'theme_foundation'),
                format_text(get_string('syntaxhighlightheadingdesc', 'theme_foundation'), FORMAT_MARKDOWN)
            )
        );

        // Syntax highlight removed.
        $name = 'theme_foundation/syntaxhighlightremoved';
        $title = get_string('syntaxhighlightremoved', 'theme_foundation');
        $description = get_string('syntaxhighlightremoveddesc', 'theme_foundation');
        $setting = new \admin_setting_description($name, $title, $description);
        $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);
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

        $loginbackgroundurl = $toolbox->setting_file_url('loginbackground', 'loginbackground', $themename);

        if (!empty($loginbackgroundurl)) {
            $scss .= 'body.loginbackground {' . PHP_EOL;
            $scss .= 'background-image: url("' . $loginbackgroundurl . '");' . PHP_EOL;

            $loginbackgroundstyle = $toolbox->get_setting('loginbackgroundstyle', $themename);
            if ($loginbackgroundstyle === 'stretch') {
                $loginbackgroundstyle = '100% 100%';
            }
            $scss .= 'background-size: ' . $loginbackgroundstyle . ';' . PHP_EOL;
            $scss .= '.card,' . PHP_EOL;
            $scss .= '#page-footer, ' . PHP_EOL;
            $scss .= '.navbar {' . PHP_EOL;
            $scss .= 'opacity: ' . $toolbox->get_setting('loginbackgroundopacity', $themename) . ';' . PHP_EOL;
            $scss .= '}' . PHP_EOL;
            $scss .= '}' . PHP_EOL;
        }

        return $scss;
    }

    /**
     * Gets the module bodyclasses.
     *
     * @return array bodyclass strings.
     */
    public function body_classes() {
        global $PAGE;
        $bodyclasses = [];

        if ($PAGE->pagelayout == 'login') {
            $bodyclasses[] = 'loginbackground';
        }

        return $bodyclasses;
    }

    /**
     * Export for template.
     *
     * @param renderer_base $output The renderer.
     *
     * @return stdClass containing the data or null.
     */
    public function export_for_template(\renderer_base $output) {
        $data = new \stdClass();
        $toolbox = \theme_foundation\toolbox::get_instance();

        $this->export_alerts($data, $toolbox);
        $this->export_brands($data, $toolbox);

        return $data;
    }

    /**
     * Export alerts for template.
     *
     * @param array $data The template data array.
     * @param toolbox $toolbox The theme's toolbox instance.
     */
    protected function export_alerts(&$data, $toolbox) {
        $numberofalerts = $toolbox->get_setting('numberofalerts', 'foundation'); // Stick to ours or could be confusing!
        if ($numberofalerts > 0) {
            global $PAGE;
            $alertsenabled = [];
            for ($alertnum = 1; $alertnum <= $numberofalerts; $alertnum++) {
                if ($toolbox->get_setting('enablealert' . $alertnum, 'foundation')) { // Stick to ours or could be confusing!
                    $alertpage = $toolbox->get_setting('alertpage' . $alertnum, 'foundation');
                    switch ($alertpage) {
                        case 'all':
                            $alertsenabled[] = $alertnum; // Alert to be shown on the page.
                            break;
                        default:
                            if ($PAGE->pagelayout == $alertpage) {
                                $alertsenabled[] = $alertnum; // Alert to be shown on the given page.
                            }
                            break;
                    }
                }
            }

            if (!empty($alertsenabled)) {
                $data->thealerts = [];
                foreach ($alertsenabled as $alertnum) {
                    $thealert = new \stdClass();
                    $thealert->alerttype = $toolbox->get_setting('alerttype' . $alertnum, 'foundation');
                    $thealert->alerttitle = $toolbox->get_setting('alerttitle' . $alertnum, 'foundation');
                    $thealert->alerttext = $toolbox->get_setting('alerttext' . $alertnum, 'foundation');

                    $data->thealerts[] = $thealert;
                }
            }
        }
    }

    /**
     * Export brands for template.
     *
     * @param array $data The template data array.
     * @param toolbox $toolbox The theme's toolbox instance.
     */
    protected function export_brands(&$data, $toolbox) {
        $numberofbrands = $toolbox->get_setting('numberofbrands', 'foundation'); // Stick to ours or could be confusing!
        if ($numberofbrands > 0) {
            global $PAGE;
            $brandsenabled = [];
            for ($brandnum = 1; $brandnum <= $numberofbrands; $brandnum++) {
                if ($toolbox->get_setting('enablebrand' . $brandnum, 'foundation')) { // Stick to ours or could be confusing!
                    $brandsenabled[] = $brandnum; // Alert to be shown on the page.
                }
            }

            if (!empty($brandsenabled)) {
                $data->thebrands = [];
                $fav = $toolbox->get_setting('fav', 'foundation');
                if ($fav == 2) {
                    $data->brandclasses = 'fa-brands fa-';
                } else {
                    $data->brandclasses = 'fa fa-';
                }
                $data->brandsenabled = true;
                foreach ($brandsenabled as $brandnum) {
                    $thebrand = new \stdClass();
                    $thebrand->brandiconname = $toolbox->get_setting('brandiconname' . $brandnum, 'foundation');
                    $thebrand->brandiconurl = $toolbox->get_setting('brandiconurl' . $brandnum, 'foundation');

                    $data->thebrands[] = $thebrand;
                }
            }
        }
    }
}
