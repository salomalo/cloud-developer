/**
 * Module - storage
 * Handles persistent key/field value, using localstorage across windows/tabs and page refreshes.
 */

/* global $ */

var storage = (function() {

    var init = function() {
        events();
    };
    
    var save = function() {
        // iterate over .storage elements, populate storage
        $(document).find('.storage').each(function(index) {
            var elm = $(this);
            window.localStorage.setItem('storage-' + elm.attr('id'), elm.val());
        });
    };

    var events = function() {
        // iterate over .storage elements, attach storage event, update value if already in storage
        $(document).find('.storage').each(function(index) {
            var elm = $(this);
            var value = window.localStorage.getItem('storage-' + elm.attr('id'));
            if (value != null && value != "null") {
                $("#" + elm.attr('id')).val(value);
            }
            $(window).bind('storage', function(e) {
                $("#" + elm.attr('id')).val(window.localStorage.getItem('storage-' + elm.attr('id')));
            });
        });

        // on .storage change, store value
        $(document).on('keyup blur propertychange paste', '.storage', function() {
            var elm = $(this);
            window.localStorage.setItem('storage-' + elm.attr('id'), elm.val());
        });
        $(document).on('change', 'select.storage', function() {
            var elm = $(this);
            window.localStorage.setItem('storage-' + elm.attr('id'), elm.val());
        });

        // clear stored items, not efecting other potential keys
        $(document).on('click', '.storage-clear', function() {
            for (var key in window.localStorage) {
                if (key.indexOf('storage-') != -1) {
                    //remove key from storage
                    window.localStorage.removeItem(key);

                    //clear local element
                    var elmId = key.substring('storage-'.length);
                    $("#" + elmId).val('');
                }
            }
        });
    };

    /**
     * Upon form submit clear the element found within the form
     * @param jquery selector object... e.g $('form#myForm')
     */
    var empty_form = function(form) {
        form.find('.storage').each(function(index) {
            window.localStorage.removeItem('storage-' + $(this).attr('id'));
            $(this).val('');
        });
    };

    return {
        init: init,
        empty_form: empty_form,
        save: save
    };
})();



/**
 * Module - timers handler
 * 
 * Polling run by timers become troublesome when your loading content via ajax,
 * as new content comes in global timers wont stop for previous content, 
 * this then can causes issues.
 * 
 * This is to store timer ids which then can be stopped.
 *
 * @usage:  load.script('/load/js/timers.js', function(){});
 */
var timers = (function() {
    var timers = new Array();
    
    var add = function(timer_id) {
        timers.push(timer_id);
    };   

    var stopAll = function(timer_id) {
        // clear all timers
        for (var i = 0; i < timers.length; i++) {
            clearTimeout(timers[i]);
        }
    };
    
    return {
        add: add,
        stopAll: stopAll,
    };
})();


/**
 * Module - app
 */

/* global $, timers, pollTimer */

window.app = (function() {

    /**
     * Init construct
     */
    var init = function() {

        //
        //clipboard.init();

        //app event handlers
        events();

        $.xhrPool = [];
        $.xhrPool.abortAll = function() {
            $(this).each(function(i, jqXHR) { //  cycle through list of recorded connection
                jqXHR.abort(); //  aborts connection
                $.xhrPool.splice(i, 1); //  removes from list by index
            });
        };

        var oldbeforeunload = window.onbeforeunload;

        window.onbeforeunload = function() {
            var r = oldbeforeunload ? oldbeforeunload() : undefined;
            if (r == undefined) {
                $.xhrPool.abortAll();
            }
            return r;
        };
    };

    /**
     * Ajax load content into (.ajax-container)
     * ajax_load('/');
     */
    var ajax_load = function(url, eml, noStateChange, callback) {

        if (!eml) {
            eml = '.ajax-container';
        }

        if (!noStateChange) {
            noStateChange = false;
        }

        if (typeof pollTimer != 'undefined') {
            clearTimeout(pollTimer);
        }

        $.ajax({
            url: url,
            cache: false,
            beforeSend: function(jqXHR) {
                $.xhrPool.push(jqXHR);
            },
            complete: function(jqXHR) {
                var i = $.xhrPool.indexOf(jqXHR);
                if (i > -1) $.xhrPool.splice(i, 1);
            },
            success: function(response, status, request) {

                if (!noStateChange) {
                    window.history.pushState({
                        url: url
                    }, "", url);
                }

                // double or clicking too fast, just refresh page
                if (response == 'Invalid CSRF token') {
                    window.location = url;
                }
                else {
                    $(eml).replaceWith($('<div />').html(response).find(eml)[0]);

                    // re attach events
                    events();
                }
            },
            error: function() {
                $.xhrPool.abortAll();
                timers.stopAll();
            }
        });
    };

    /**
     * Popup Window handler
     * With jQuery attached event handler: [data-type="popup"]
     * <a href="javascript:;" data-type="popup" data-url="/path/to/resource" data-name="Popup Title">...</a>
     */
    var popup = function(url, t, w, h) {
        var screenLeft = (window.screenLeft != undefined) ? window.screenLeft : screen.left,
            screenTop = (window.screenTop != undefined) ? window.screenTop : screen.top,
            width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width,
            height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height,
            left = (((width / 2) - (w / 2)) + screenLeft)+6,
            top = (((height / 2) - (h / 2)) + screenTop)-59,
            popupWindow = window.open(url, t, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        if (window.focus) {
            popupWindow.focus();
        }
    };

    /**
     * Event handlers
     */
    var events = function() {

        /**
         * Attach on click event to open popup window to data-type="popup" elements
         */
        $(document).find('[data-type="popup"]').unbind('click').on('click', function(e) {
            popup($(this).data('url'), $(this).data('name'), 1024, 768);
        });

        /**
         * Bootstrap tooltips
         */
        $(document).find('[data-toggle="tooltip"]').tooltip();

        /**
         * Browser back button
         */
        $(window).on("popstate", function() {
            // check there is a history state
            if (window.history.state && window.history.state.url) {
                var url = window.history.state.url.split('#')[0];
                $.ajax({
                    url: url,
                    dataType: "html",
                    success: function(data) {
                        $('.ajax-container').replaceWith($('<div />').html(data).find('.ajax-container')[0]);
                    }
                });
            }
        });

        // /**
        //  * bootstrap tabs - save selected tab after reload
        //  */
        // if (typeof Storage !== "undefined") {
        //     $(document).find('a[data-toggle="tab"]').unbind('click').on('click', function(e) {
        //         var target = $(e.target).attr("href");
        //         sessionStorage.setItem("current-tab", target);
        //     });
        //     var current_tab = sessionStorage.getItem("current-tab");
        //     if (current_tab !== null) {
        //         if (current_tab.match('#')) {
        //             $('a[href="#' + current_tab.split('#')[1] + '"]').tab('show');
        //         }
        //     }
        // }

        /**
         * Fix bs.dropdown in table-responsive
         */
        /*$(document).find('.table-responsive').on('shown.bs.dropdown', function(e) {
            var $table = $(this),
                $menu = $(e.target).find('.dropdown-menu'),
                tableOffsetHeight = $table.offset().top + $table.height(),
                menuOffsetHeight = 55 + $menu.offset().top + $menu.outerHeight(true);

            if (menuOffsetHeight > tableOffsetHeight) {
                $table.css("padding-bottom", menuOffsetHeight - tableOffsetHeight);
            }
        }).on('hide.bs.dropdown', function() {
            $(this).css("padding-bottom", 0);
        });*/

        /**
         * AJAX links event handler
         */
        $(document).find('.ajax-link').unbind('click').on('click', function(e) {
            e.preventDefault();
            // stop all timers
            timers.stopAll();
            // call ajax load function
            ajax_load($(this).attr('href'), '.ajax-container', $(this).data('keep-state'));
        });

        /**
         * attach AJAX modal links event handler
         */
        $(document).find('.ajax-modal-link').unbind('click').on('click', function(e) {
            e.preventDefault();
            // stop all timers
            timers.stopAll();
            // call ajax load function
            ajax_load($(this).prop('href'), '.modal-content', $(this).data('keep-state'));
        });

        /**
         * AJAX modal event handler
         */
        $(document).find('.ajax-modal').unbind('click').on('click', function(e) {
            e.preventDefault();

            var modal = '.modal-content';

            var default_content = '' +
                '<div class="modal-header primary-color white-text">' +
                '    <h4 class="modal-title">' +
                '        Loading&hellip;' +
                '    </h4>' +
                '    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>' +
                '</div>' +
                '<div class="modal-body">' +
                '    <p class="slow-warning">Please wait&hellip;</p>' +
                '</div>';

            $(modal).html(default_content);

            setTimeout(function() {
                if ($(document).find('.slow-warning').length > 0) {
                    $(document).find('.slow-warning').html('Content failed to load, please refresh your browser and try again.');
                }
            }, 5500);

            var dialog_size = $(this).data('size');

            var request = $.ajax({
                url: $(this).data('url'),
                method: "GET",
                dataType: "html",
                cache: false
            });

            request.done(function(data) {
                var modal = '.modal-content';

                $(modal).replaceWith($('<div />').html(data).find(modal)[0]);

                if (dialog_size == 'modal-lg') {
                    $(modal).parent().removeClass('modal-sm modal-md modal-lg').addClass('modal-lg');
                }
                else if (dialog_size == 'modal-sm') {
                    $(modal).parent().removeClass('modal-sm modal-md modal-lg').addClass('modal-sm');
                }
                else {
                    $(modal).parent().removeClass('modal-sm modal-md modal-lg').addClass('modal-md');
                }

                /**
                 * attach AJAX modal links event handler
                 */
                $(document).find('.ajax-modal-link').unbind('click').on('click', function(e) {
                    e.preventDefault();
                    // stop all timers
                    timers.stopAll();
                    // call ajax load function
                    ajax_load($(this).prop('href'), '.modal-content', $(this).data('keep-state'));
                });
            });

            request.fail(function(jqXHR, textStatus) {
                console.log('modal failed to load', textStatus);
                timers.stopAll();
            });
        });

        if ($(document).find('m' + 'ai' + 'lt' + 'o').length > 0) {
            $(document).find('m' + 'ai' + 'lt' + 'o').replaceWith(atob(
                'PGEgaHJlZj0ibWFpbHRvOmVucXVp' +
                'cmllc0BseGMuc3lzdGVtcyIgc3R5' +
                'bGU9ImNvbG9yOiNiZmM5ZDM7Ij5l' +
                'bnF1aXJpZXNAbHhjLnN5c3RlbXM8' +
                'L2E+'
            ));
        }

        /*Gifffer();*/

        /*if ($('[itemprop="aggregateRating"]').length > 0) {
            $('[itemprop="aggregateRating"]').hide();
        }*/
/*
        // DISQUS delay abit so count.js can see DOM
        if ($(document).find('#disqus_thread').length > 0) {
            setTimeout(function() {
                if (typeof DISQUSWIDGETS !== "undefined") {
                    DISQUSWIDGETS.getCount({
                        reset: true
                    });
                }
            }, 300);
        }
        */
    };

    return {
        init: init,
        ajax_load: ajax_load,
        events: events
    };
})();

$(function() {
    window.app.init();
});
