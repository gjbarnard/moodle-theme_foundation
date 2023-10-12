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
 * Collapse block.
 *
 * @module     theme_foundation/collapseblock
 * @copyright  &copy; 2019-onwards G J Barnard.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/* jshint ignore:start */
define(['jquery', 'core/log', 'theme_foundation/util'], function($, log, FoundationUtil) {

    "use strict"; // jshint ;_;

    log.debug('Foundation Collapse Block AMD');

    $(document).ready(function($) {
        $('.block-collapsible').click(function() {
            var instanceId = $(this).data('instanceid');
            var blockInstance = $('#inst' + instanceId);

            $('#inst' + instanceId + ' .card-text').slideToggle('slow', function() {
                if (blockInstance.hasClass('hidden')) {
                    blockInstance.removeClass('hidden');
                    FoundationUtil.setUserPreference('block' + instanceId + 'hidden', 0);
                } else {
                    blockInstance.addClass('hidden');
                    FoundationUtil.setUserPreference('block' + instanceId + 'hidden', 1);
                }
            });
        });
        log.debug('Foundation Collapse Block AMD init');
    });
});
/* jshint ignore:end */
