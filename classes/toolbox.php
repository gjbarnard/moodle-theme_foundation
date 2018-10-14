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
 * @package    theme
 * @subpackage foundation
 * @copyright  &copy; 2018-onwards G J Barnard.
 * @author     G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_foundation;

defined('MOODLE_INTERNAL') || die();

use theme_config;

class toolbox {

    protected $corerenderer = null;
    protected static $instance;

    private function __construct() {
    }

    public static function get_instance() {
        if (!is_object(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get_theme_renderer(theme_config $theme) {
        global $PAGE;
        return $PAGE->get_renderer('theme_'.$theme->name, 'core');
    }

    public function get_main_scss_content($theme_config) {
        //global $CFG;

        //$scss = file_get_contents($CFG->dirroot . '/theme/foundation/scss/preset/default.scss');
        //$scss .= file_get_contents($CFG->dirroot . '/theme/foundation/scss/theme/theme.scss');

        $scss = $this->generate_css_from_scss($theme_config);

        return $scss;
    }

    protected function generate_css_from_scss($theme_config) {
        global $CFG;

        $boostpath = $CFG->dirroot . '/theme/boost/scss';

        list($paths, $scss) = $theme_config->get_scss_property();
        $paths[] = $boostpath;

        $scss = file_get_contents($CFG->dirroot . '/theme/foundation/scss/preset/default.scss');
        //$scss .= file_get_contents($CFG->dirroot . '/theme/foundation/scss/theme/theme.scss');
        //$scss = file($CFG->dirroot . '/theme/foundation/scss/preset/default.scss');
        //$handle = fopen($CFG->dirroot . '/theme/foundation/scss/preset/default.scss', "rb");
        //$scss = fread($handle, filesize($CFG->dirroot . '/theme/foundation/scss/preset/default.scss'));
        //fclose($handle);

        //$scss .= file($CFG->dirroot . '/theme/foundation/scss/theme/theme.scss');

        // We might need more memory/time to do this, so let's play safe.
        \raise_memory_limit(MEMORY_EXTRA);
        \core_php_time_limit::raise(300);

        // Set-up the compiler.
        $compiler = new \core_scss();

        $compiler->append_raw_scss($scss);
        $compiler->setImportPaths($paths);

        try {
            // Compile!
            $compiled = $compiler->to_css();
error_log(print_r($compiler->getParsedFiles(), true));
        } catch (\Exception $e) {
            $compiled = false;
            debugging('Error while compiling SCSS: ' . $e->getMessage(), DEBUG_DEVELOPER);
        }

        // Try to save memory.
        $compiler = null;
        unset($compiler);

        return $compiled;
    }

    /**
     * Return an instance of the mustache class.
     *
     * @since 2.9
     * @return Mustache_Engine
     */
    public function get_mustache() {
        global $PAGE;
        $renderer = $PAGE->get_renderer('theme_foundation', 'mustache');

        return $renderer->getmustache();
    }

    public function extra_scss() {
        //$us = \theme_config::load('foundation');
        //$css = $this->generate_css_from_scss($us);
        $css = '';

        return $css;
    }
}
