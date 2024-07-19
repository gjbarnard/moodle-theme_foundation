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
// Foundation theme.
//
// @module     theme_foundation/anti_gravity
// @copyright  2019 G J Barnard.
// @author     G J Barnard -
//               {@link https://moodle.org/user/profile.php?id=442195}
//               {@link https://gjbarnard.co.uk}
// @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
//

import $ from 'jquery';
import log from 'core/log';

/**
 * Bottom check.
 */
const botcheck = () => {
    var botdocoffset = $(document).height() - $(window).height();
    var botmainoffset = $('#region-main').height();

    if (botdocoffset < botmainoffset) {
        return botdocoffset;
    } else {
        return botmainoffset;
    }
};

/**
 * Anti Gravity.
 */
const antiGravity = () => {
    log.debug('Foundation ES6 Anti Gravity');
    var topoffset = 220;
    var duration = 1000;
    var botoffset = botcheck();

    const gravitycheck = () => {
        var sc = $(window).scrollTop();
        if (sc > topoffset) {
            $('.backtotop').fadeIn(duration);
        } else {
            $('.backtotop').fadeOut(duration);
        }
        if (sc < botoffset) {
            $('.gotobottom').fadeIn(duration);
        } else {
            $('.gotobottom').fadeOut(duration);
        }
    };

    $(window).scroll(function () {
        gravitycheck();
    });
    gravitycheck();

    $('.backtotop').click(function (event) {
        event.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, duration);
        return false;
    });

    $('.gotobottom').click(function (e) {
        e.preventDefault();
        var target = $('#region-main');
        $('html, body').animate({ scrollTop: target.position().top + target.height() }, duration);
        return false;
    });
};

/**
 * Anti Gravity Init.
 */
export const init = () => {
    log.debug('Foundation ES6 Anti Gravity init');
    if (document.readyState !== 'loading') {
        log.debug("Foundation ES6 Anti Gravity init DOM content already loaded");
        antiGravity();
    } else {
        log.debug("Foundation ES6 Anti Gravity init JS DOM content not loaded");
        document.addEventListener('DOMContentLoaded', function () {
            log.debug("Foundation ES6 Anti Gravity init JS DOM content loaded");
            antiGravity();
        });
    }
};
