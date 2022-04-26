/**
 * custom javascript by travelsoft
 * 
 * use jQuery
 * 
 * smart.filter proccessing
 * processing transitions pagination
 * list sorting processing
 * smart.filter map proccessing
 * init map & mapmarkers
 * load json markers by ajax
 * 
 * @author dimabresky
 * 
 * @param {jQuery} $
 * @param {object} window
 */

"use strict";

(function ($, window, moment) {
    
    var 
        
        /**
         * preloader object
         * @type {function}
         */
        preloader = (function ($) {

            var preloaderId = "preloader";

            //init preloader container
            $(window.doument).ready(function () {

                if (!$("#" + preloaderId).length) {

                    var preloaderBody = "<div id=\""+preloaderId+"\">" + 
                                                        "<div class=\"tb-cell\">" + 
                                                            "<div id=\"page-loading\">" + 
                                                                "<div></div>" + 
                                                            "</div>" + 
                                                        "</div>" + 
                                                    "</div>";


                    $("body").append(preloaderBody);


                }
            });

            return {
                show: function () {
                    $("#" + preloaderId).css({display: "table"});
                },

                hide: function () {
                    $("#" + preloaderId).fadeOut(500);
                }
            };

        })($),
        
        __compid = "__travelsoft_ef3b7f993cf1957b8d2007682f9b75fd";
        
    
    /**
     * escape html data
     * @param {string} data
     */
    function __escape (data) {
        
        var
        
            SCRIPT_REGEX = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
                    
            TAGEVENTS_REGEX = /(onclick|onchange|onmouseover|onmouseout|onkeydown|onload)/gi,
            
            __data = data;
        
        while (SCRIPT_REGEX.test(__data)) {
            __data = data.replace(SCRIPT_REGEX, "");
        }
        
        while (TAGEVENTS_REGEX.test(__data)) {
            __data = data.replace(TAGEVENTS_REGEX, "");
        }
        
        return __data;
        
    }
    
        
    /**
     * utilites object
     */
    $.travelsoft = {
        
        /**
         * uniq component id
         * @type String
         */
        __compid: __compid,
        
        /**
         * ajax url for request
         * @type String
         */
        ajaxUrl: "",

        /**
         * css selector container for insertion html
         * @type String
         */
        insertCssSelector: "#" + __compid,
        
        /**
         * need count elements
         * @type boolean
         */
        setCnt: true,
        
        /**
         * css selector container for insertion count elements number
         * @type String
         */
        insertCntElementsCssSelector: "ins#searching__cnt__elements",
        
        /**
         * css selector container for get count elements number
         * @type String
         */
        cntElementsContainerCssSelector: "span#cnt__elements",
        
        /**
        * post ajax request and insert returned html
        * @param {string} url ajax url
        * @param {type} compid ajax id area
        */
        post: function () {
            
            if ($.travelsoft.ajaxUrl !== "" && $.travelsoft.__compid !== "") {

                preloader.show();

                $.post($.travelsoft.ajaxUrl, {__compid: $.travelsoft.__compid}, function (data) {

                    $($.travelsoft.insertCssSelector).html(__escape(data));

                    preloader.hide();

                    window.history.pushState(null, null, $.travelsoft.ajaxUrl);

                    // set count elements
                    if ($.travelsoft.setCnt === true) {
                        $($.travelsoft.insertCntElementsCssSelector).text( $($.travelsoft.cntElementsContainerCssSelector).text() || 0 );
                    }
                    // refresh carousel
                    $('.owl-carousel').trigger( 'refresh.owl.carousel' ); 

                }, "html").fail(function () { preloader.hide(); });

            }

        }
        
    };

    // init state event handler
//    window.addEventListener('popstate', function() {
//        window.location.reload();
//    });
    
    $(window.document).ready(function (){
        
        // wrapper for filter post
        function __filterpost() {
            $.travelsoft.ajaxUrl = $("#smart_filter_form").attr("action") + "?" + $("#smart_filter_form").serialize();
            $.travelsoft.post();
            
        };
        
        // smart.filter proccessing
        $("#smart_filter_form input[type='checkbox']").on("change", function () {
            if($(this).hasClass("check_all")) {
                $('.'+$(this).attr('id')).prop('checked', $(this).prop('checked'))
            }
            if($(this).hasClass("check_all_child")) {
                let parent = $(this).parents('ul');
                let check_all = parent.find('.check_all');
                let checked = $('.'+check_all.attr('id')+':not(:checked)').length == 0;
                check_all.prop('checked', checked);
            }
            __filterpost();
        });
        
        moment.locale();
        $('.ts-daterange').each(function () {
                    var $this = $(this);
                    moment.locale($this.data("locale"));
                    $this.daterangepicker({
                        minDate: moment.unix( $this.data("min-date") ),
                        maxDate: moment.unix( $this.data("max-date") ),
                        startDate: moment.unix( $this.data("start-date") ),
                        endDate: moment.unix( $this.data("end-date") ),
                        autoApply: true,
                        locale: {
                            format: 'DD.MM.YYYY',
                            separator: ' - ',
                            daysOfWeek: moment.weekdaysMin(),
                            monthNames: moment.monthsShort(),
                            firstDay: moment.localeData().firstDayOfWeek()
                        }
                    }).on("apply.daterangepicker", function () {

                        var arVals = $(this).val().split(" - ") || [], parent;

                        if (arVals.length) {

                            parent = $(this).parent(".field-date");

                            parent.find(".minDate").val(arVals[0]);
                            parent.find(".maxDate").val(arVals[1]);

                            __filterpost();

                        }


                    });
        });
        
        $(".select2-smart-filter").select2({
            allowClear: true
        }).on('change', function () { __filterpost(); });
        
        // processing transitions pagination
        $(window.document).on("click", ".sorting, .modern-page-navigation a", function (e) {
            if ($(this).parent().hasClass("modern-page-navigation")) {
                $(window).scrollTop(150);
            }
            $.travelsoft.ajaxUrl = $(this).attr("href");
            $.travelsoft.post();
            e.preventDefault();
        });
        
    });
    
})(jQuery, window, window.moment);