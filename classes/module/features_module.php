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
 * @copyright  &copy; 2019-onwards G J Barnard.
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation\module;

defined('MOODLE_INTERNAL') || die();

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
     * @param boolean $adminfulltree If the full tree is required.
     * @param toolbox $toolbox The theme toolbox.
     */
    public function add_settings(&$settingspages, $adminfulltree, $toolbox) {
        // Create our own settings page.
        $settingspages['features'] = array(\theme_foundation\toolbox::SETTINGPAGE => new \admin_settingpage('theme_foundation_features',
            get_string('featuresheading', 'theme_foundation')), \theme_foundation\toolbox::HASSETTINGS => true);
        if ($adminfulltree) {
            global $CFG;
            if (file_exists("{$CFG->dirroot}/theme/foundation/foundation_admin_setting_configselect.php")) {
                require_once($CFG->dirroot . '/theme/foundation/foundation_admin_setting_configselect.php');
                require_once($CFG->dirroot . '/theme/foundation/foundation_admin_setting_configinteger.php');
            } else if (!empty($CFG->themedir) && file_exists("{$CFG->themedir}/foundation/foundation_admin_setting_configselect.php")) {
                require_once($CFG->themedir . '/foundation/foundation_admin_setting_configselect.php');
                require_once($CFG->themedir . '/foundation/foundation_admin_setting_configinteger.php');
            }

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
            $description = get_string('numberofalertsdesc', 'theme_foundation',
                array('lower' => $lower, 'upper' => $upper));
            $setting = new \foundation_admin_setting_configinteger($name, $title, $description, $default, $lower, $upper);
            $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

            $numberofalerts = $toolbox->get_setting('numberofalerts', 'foundation'); // Stick to ours or could be confusing!
            if ($numberofalerts > 0) {
                for ($alertnum = 1; $alertnum <= $numberofalerts; $alertnum++) {
                    // Alert X setting heading.
                    $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add(
                        new \admin_setting_heading(
                            'theme_foundation_alert_'.$alertnum.'_heading',
                            get_string('alertsettingheading', 'theme_foundation', array('number' => $alertnum)),
                            ''
                        )
                    );

                    // Alert enabled.
                    $name = 'theme_foundation/enablealert'.$alertnum;
                    $title = get_string('enablealert', 'theme_foundation', array('number' => $alertnum));
                    $description = get_string('enablealertdesc', 'theme_foundation', array('number' => $alertnum));
                    $default = false;
                    $setting = new \admin_setting_configcheckbox($name, $title, $description, $default, true, false);
                    $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

                    // Alert type.
                    $name = 'theme_foundation/alerttype'.$alertnum;
                    $title = get_string('alerttype', 'theme_foundation', array('number' => $alertnum));
                    $description = get_string('alerttypedesc', 'theme_foundation');
                    $default = 'info';
                    $choices = array(
                        'danger' => get_string('alertdanger', 'theme_foundation'),
                        'dark' => get_string('alertdark', 'theme_foundation'),
                        'info' => get_string('alertinfo', 'theme_foundation'),
                        'light' => get_string('alertlight', 'theme_foundation'),
                        'primary' => get_string('alertprimary', 'theme_foundation'),
                        'secondary' => get_string('alertsecondary', 'theme_foundation'),
                        'success' => get_string('alertsuccess', 'theme_foundation'),
                        'warning' => get_string('alertwarning', 'theme_foundation')
                    );
                    $setting = new \foundation_admin_setting_configselect($name, $title, $description, $default, $choices);
                    $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

                    // Alert title.
                    $name = 'theme_foundation/alerttitle'.$alertnum;
                    $title = get_string('alerttitle', 'theme_foundation', array('number' => $alertnum));
                    $description = get_string('alerttitledesc', 'theme_foundation', array('number' => $alertnum));
                    $default = '';
                    $setting = new \admin_setting_configtext($name, $title, $description, $default);
                    $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

                    // Alert text.
                    $name = 'theme_foundation/alerttext'.$alertnum;
                    $title = get_string('alerttext', 'theme_foundation', array('number' => $alertnum));
                    $description = get_string('alerttextdesc', 'theme_foundation', array('number' => $alertnum));
                    $default = '';
                    $setting = new \admin_setting_confightmleditor($name, $title, $description, $default);
                    $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

                    // Alert pages on.
                    $name = 'theme_foundation/alertpage'.$alertnum;
                    $title = get_string('alertpage', 'theme_foundation', array('number' => $alertnum));
                    $description = get_string('alertpagedesc', 'theme_foundation', array('number' => $alertnum));
                    $default = 'frontpage';
                    $choices = array(
                        'all' => get_string('all'),
                        'course' => get_string('course'),
                        'mydashboard' => get_string('myhome'),
                        'frontpage' => get_string('frontpage', 'admin')
                    );
                    $setting = new \foundation_admin_setting_configselect($name, $title, $description, $default, $choices);
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
            $setting = new \admin_setting_configstoredfile($name, $title, $description, 'loginbackground');
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

            // Login background style.
            $name = 'theme_foundation/loginbackgroundstyle';
            $title = get_string('loginbackgroundstyle', 'theme_foundation');
            $description = get_string('loginbackgroundstyledesc', 'theme_foundation');
            $default = 'cover';
            $setting = new \foundation_admin_setting_configselect($name, $title, $description, $default,
                array(
                    'cover' => get_string('stylecover', 'theme_foundation'),
                    'stretch' => get_string('stylestretch', 'theme_foundation')
                )
            );
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

            $opactitychoices = array(
                '0.0' => '0.0',
                '0.1' => '0.1',
                '0.2' => '0.2',
                '0.3' => '0.3',
                '0.4' => '0.4',
                '0.5' => '0.5',
                '0.6' => '0.6',
                '0.7' => '0.7',
                '0.8' => '0.8',
                '0.9' => '0.9',
                '1.0' => '1.0'
            );

            // Overridden course title text background opacity setting.
            $name = 'theme_foundation/loginbackgroundopacity';
            $title = get_string('loginbackgroundopacity', 'theme_foundation');
            $description = get_string('loginbackgroundopacitydesc', 'theme_foundation');
            $default = '0.8';
            $setting = new \foundation_admin_setting_configselect($name, $title, $description, $default, $opactitychoices);
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

            // Activate syntax highlighting - 1 = no, 2 = yes.
            $name = 'theme_foundation/syntaxhighlight';
            $title = get_string('syntaxhighlight', 'theme_foundation');
            $description = get_string('syntaxhighlightdesc', 'theme_foundation');
            $default = 1;
            $choices = array(
                1 => new \lang_string('no'), // No.
                2 => new \lang_string('yes') // Yes.
            );
            $setting = new \admin_setting_configselect($name, $title, $description, $default, $choices);
            $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

            $shchoices = array(
                '3.0.83' => '3.0.83',
                '4.0.1' => '4.0.1'
            );

            // Syntax highlighter version.
            $name = 'theme_foundation/syntaxhighlightversion';
            $title = get_string('syntaxhighlightversion', 'theme_foundation');
            $description = get_string('syntaxhighlightversiondesc', 'theme_foundation');
            $default = '3.0.83';
            $setting = new \foundation_admin_setting_configselect($name, $title, $description, $default, $shchoices);
            $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);

            if (\theme_foundation\toolbox::get_config_setting('syntaxhighlight') == 2) {
                // Syntax highlighting categories.
                $coursecats = \theme_foundation\toolbox::get_categories_list();
                $coursecatsoptions = array();
                foreach ($coursecats as $catkey => $catvalue) {
                    $coursecatsoptions[$catkey] = join(' / ', $catvalue->namechunks);
                }
                $name = 'theme_foundation/syntaxhighlightcat';
                $title = get_string('syntaxhighlightcat', 'theme_foundation');
                $description = get_string('syntaxhighlightcatdesc', 'theme_foundation');
                $default = array();
                $setting = new \admin_setting_configmultiselect($name, $title, $description, $default, $coursecatsoptions);
                $settingspages['features'][\theme_foundation\toolbox::SETTINGPAGE]->add($setting);
            }
        }
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
            $scss .= 'body.loginbackground {'.PHP_EOL;
            $scss .= 'background-image: url("'.$loginbackgroundurl.'");'.PHP_EOL;

            $loginbackgroundstyle = $toolbox->get_setting('loginbackgroundstyle', $themename);
            $replacementstyle = 'cover';
            if ($loginbackgroundstyle === 'stretch') {
                $replacementstyle = '100% 100%';
            }
            $scss .= 'background-size: '.$replacementstyle.';'.PHP_EOL;
            $scss .= '.card,'.PHP_EOL;
            $scss .= '#page-footer {'.PHP_EOL;
            $loginbackgroundopacity = $toolbox->get_setting('loginbackgroundopacity', $themename);
            $scss .= 'background-color: rgba(red($card-bg), green($card-bg), blue($card-bg), '.$loginbackgroundopacity.') !important;'.PHP_EOL;
            $scss .= '}'.PHP_EOL;
            $scss .= '}'.PHP_EOL;
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
        $bodyclasses = array();

        if ($PAGE->pagelayout == 'login') {
            $bodyclasses[] = 'loginbackground';
        }

        return $bodyclasses;
    }

    /**
     * Export for template.
     *
     * @param renderer_base $output The renderer.
     * @return stdClass containing the data or null.
     */
    public function export_for_template(\renderer_base $output) {
        $data = null;
        $toolbox = \theme_foundation\toolbox::get_instance();

        $numberofalerts = $toolbox->get_setting('numberofalerts', 'foundation'); // Stick to ours or could be confusing!
        if ($numberofalerts > 0) {
            global $PAGE;
            $alertsenabled = array();
            for ($alertnum = 1; $alertnum <= $numberofalerts; $alertnum++) {
                $alertenabled = $toolbox->get_setting('enablealert'.$alertnum, 'foundation'); // Stick to ours or could be confusing!
                if ($alertenabled) {
                    $alertpage = $toolbox->get_setting('alertpage'.$alertnum, 'foundation');
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
                $data = new \stdClass;
                $data->thealerts = array();
                foreach ($alertsenabled as $alertnum) {
                    $thealert = new \stdClass;
                    $thealert->alerttype = $toolbox->get_setting('alerttype'.$alertnum, 'foundation');
                    $thealert->alerttitle = $toolbox->get_setting('alerttitle'.$alertnum, 'foundation');
                    $thealert->alerttext = $toolbox->get_setting('alerttext'.$alertnum, 'foundation');

                    $data->thealerts[] = $thealert;
                }
            }
        }

        return $data;
    }
}
