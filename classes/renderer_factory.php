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
 * @copyright  2022 G J Barnard.
 * @author     G J Barnard -
 *               {@link https://moodle.org/user/profile.php?id=442195}
 *               {@link https://gjbarnard.co.uk}
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

namespace theme_foundation;

use core\output\renderer_factory\theme_overridden_renderer_factory;

/**
 * The renderer factory.
 */
class renderer_factory extends theme_overridden_renderer_factory {
    /**
     * @var object The theme's core renderer.
     */
    protected $core = null;

    /**
     * Implement the subclass method
     *
     * @param moodle_page $page the page the renderer is outputting content for.
     * @param string $component name such as 'core', 'mod_forum' or 'qtype_multichoice'.
     * @param string $subtype optional subtype such as 'news' resulting to 'mod_forum_news'
     * @param string $target one of rendering target constants.
     * @return renderer_base an object implementing the requested renderer interface.
     */
    public function get_renderer(\moodle_page $page, $component, $subtype = null, $target = null) {
        $renderer = null;
        if ((($component == 'core') && (is_null($subtype))) || ($subtype == 'core')) {
            if (is_null($this->core)) {
                $this->core = parent::get_renderer($page, $component, $subtype, $target);
            }
            $renderer = $this->core;
        } else {
            $renderer = parent::get_renderer($page, $component, $subtype, $target);
        }

        return $renderer;
    }
}
