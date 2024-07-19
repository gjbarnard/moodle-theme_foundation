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

//
// Drawer.
//
// @module     theme_foundation/drawer
// @copyright  2019 G J Barnard.
// @author     G J Barnard -
//               {@link https://moodle.org/user/profile.php?id=442195}
//               {@link https://gjbarnard.co.uk}
// @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
//

import $ from 'jquery';
import * as FoundationUtil from 'theme_foundation/util';
import log from 'core/log';

const drawer = () => {
        $('#drawer').click(function() {
            var closeDrawer = $('#drawerclose');
            var openDrawer = $('#draweropen');
            var drawer = $('[data-region="blocks-drawer"]');

            if (closeDrawer.hasClass('d-none')) {
                // Drawer closed -> open.
                openDrawer.addClass('d-none');
                closeDrawer.removeClass('d-none');
                drawer.removeClass('drawer-hidden');
                $('body').addClass('drawer-open');
                drawer.attr('aria-hidden', 'false');
                openDrawer.attr('aria-hidden', 'true');
                closeDrawer.attr('aria-hidden', 'false');
                FoundationUtil.setUserPreference('drawerclosed', false);
            } else {
                // Drawer open -> closed.
                closeDrawer.addClass('d-none');
                openDrawer.removeClass('d-none');
                drawer.addClass('drawer-hidden');
                $('body').removeClass('drawer-open');
                drawer.attr('aria-hidden', 'true');
                closeDrawer.attr('aria-hidden', 'true');
                openDrawer.attr('aria-hidden', 'false');
                FoundationUtil.setUserPreference('drawerclosed', true);
            }
        });
        log.debug('Foundation ES6 Drawer drawer');
};

/**
 * Init.
 */
export const drawerInit = () => {
    log.debug('Foundation ES6 Drawer drawerInit');
    if (document.readyState !== 'loading') {
        drawer();
    } else {
        document.addEventListener('DOMContentLoaded', function () {
            drawer();
        });
    }
};
