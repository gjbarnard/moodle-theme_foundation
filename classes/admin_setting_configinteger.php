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
 * Integer admin setting with lower and upper limits.
 *
 * @package    theme_foundation
 * @copyright  2019 G J Barnard.
 * @author     G J Barnard -
 *               {@link https://moodle.org/user/profile.php?id=442195}
 *               {@link https://gjbarnard.co.uk}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation;

/**
 * Integer admin setting with lower and upper limits.
 */
class admin_setting_configinteger extends \admin_setting_configtext {
    /** @var int lower range limit */
    public $lower;
    /** @var int upper range limit */
    public $upper;

    /**
     * Config integer constructor
     *
     * @param string $name unique ascii name, either 'mysetting' for settings that in config, or 'myplugin/mysetting' for ones in
     * config_plugins.
     * @param string $visiblename localised
     * @param string $description long localised info
     * @param string $defaultsetting
     * @param int $lower lower range limit
     * @param int $upper upper range limit
     */
    public function __construct($name, $visiblename, $description, $defaultsetting, $lower, $upper) {
        if ($upper < $lower) {
            throw new coding_exception('Upper range limit is less than the lower range limit.');
        }
        $this->lower = $lower;
        $this->upper = $upper;
        parent::__construct($name, $visiblename, $description, $defaultsetting, PARAM_INT);
    }

    /**
     * Checks if data has empty html.
     *
     * @param string $data
     *
     * @return string Empty when no errors.
     */
    public function write_setting($data) {
        // Trim any spaces to avoid spaces typo.
        $data = trim($data);
        if ($data === '') {
            // Override parent behaviour and set to default if empty string.
            $data = $this->get_defaultsetting();
        }
        return parent::write_setting($data);
    }

    /**
     * Validate data before storage
     * @param string $data
     *
     * @return mixed true if ok string if error found
     */
    public function validate($data) {
        if (!is_number($data)) {
            $validated = get_string('asconfigintnan', 'theme_foundation', ['value' => $data]);
        } else {
            $validated = parent::validate($data); // Pass parent validation first.

            if ($validated == true) {
                if ($data < $this->lower) {
                    $validated = get_string('asconfigintlower', 'theme_foundation', ['value' => $data, 'lower' => $this->lower]);
                } else if ($data > $this->upper) {
                     $validated = get_string('asconfigintupper', 'theme_foundation', ['value' => $data, 'upper' => $this->upper]);
                } else {
                    $validated = true;
                }
            }
        }

        return $validated;
    }
}
