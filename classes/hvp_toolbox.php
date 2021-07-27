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
 * @package     theme_foundation
 * @copyright   2021 Gareth J Barnard
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_foundation;

defined('MOODLE_INTERNAL') || die;

/**
 * Trait theme_foundation_core_h5p_renderer.
 *
 * See: https://tracker.moodle.org/browse/MDL-69087 and
 *      https://github.com/sarjona/h5pmods-moodle-plugin.
 *
 * @package     theme_foundation
 * @copyright   2021 Gareth J Barnard
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait hvp_toolbox {
    /**
     * Add styles when an H5P is displayed.
     *
     * @param array $styles Styles that will be applied.
     * @param array $libraries Libraries that will be shown.
     * @param string $embedtype How the H5P is displayed.
     */
    protected function fhvp_alter_styles(&$styles, $libraries, $embedtype) {
        $toolbox = \theme_foundation\toolbox::get_instance();
        $content = $toolbox->get_setting('hvpcustomcss');
        if (!empty($content)) {
            $styles[] = (object) array(
                'path' => $this->get_style_url($content),
                'version' => ''
            );
        }
    }

    /**
     * Get style URL when an H5P is displayed.
     *
     * @param string $content Content.
     *
     * @return moodle_url the URL.
     */
    protected function get_style_url($content) {
        global $CFG;

        $syscontext = \context_system::instance();
            $itemid = md5($content);
                return \moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php",
                    "/$syscontext->id/theme_foundation/hvp/$itemid/themehvp.css");
    }
}
