/* jshint ignore:start */
define(['jquery', 'core/log'], function($, log) {

    "use strict"; // jshint ;_;

    log.debug('Foundation Collapse Block AMD');

    $(document).ready(function($) {
        $('.block-collapsible').click(function() {
            var instanceId = $(this).data('instanceid');
            var blockInstance = $('#inst' + instanceId);

            $('#inst' + instanceId + ' .card-text').slideToggle('slow', function() {
                if (blockInstance.hasClass('hidden')) {
                    blockInstance.removeClass('hidden');
                    M.util.set_user_preference('block' + instanceId + 'hidden', 0);
                } else {
                    blockInstance.addClass('hidden');
                    M.util.set_user_preference('block' + instanceId + 'hidden', 1);
                }
            });
        });
        log.debug('Foundation Collapse Block AMD init');
    });
});
/* jshint ignore:end */
