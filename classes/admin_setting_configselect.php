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
 * @package    theme_foundation
 * @copyright  2018 G J Barnard.
 * @author     G J Barnard -
 *               {@link https://moodle.org/user/profile.php?id=442195}
 *               {@link https://gjbarnard.co.uk}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation;

/**
 * Config select setting that sets the default on first run.
 */
class admin_setting_configselect extends \admin_setting_configselect {
    /**
     * Returns XHTML select field and wrapping div(s)
     *
     * @see output_select_html()
     *
     * @param string $data the option to show as selected
     * @param string $query
     * @return string XHTML field and wrapping div
     */
    public function output_html($data, $query = '') {
        if (!$this->load_choices() || empty($this->choices)) {
            return '';
        }

        $current = $this->get_setting();
        if (is_null($current)) {
            // First run.
            $default = $this->get_defaultsetting();
            if ((!is_null($default)) && (empty($data))) {
                $data = $default;
            }
        }

        return parent::output_html($data, $query);
    }
}
