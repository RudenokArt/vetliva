(function ($, document) {

    "use strict";

    // адрес запроса ajax
    var __ajaxUrl = "/local/components/travelsoft/travelsoft.service.price.result/ajax.php",
            // html id области навигации по слайдеру картинок (маленькие изображения)
            smallGalleryId = "owl-small-slides",
            // html id области основного слайдера картинок (большие изображения)
            bigGalleryId = "owl-big-slides",
            // jquery объекты для уничтожения их событий
            toOff = [];

    function __strValInArray(array) {

        var i, cnt = array.length, result = [];

        for (i = 0; i < cnt; i++) {
            result.push(__escape(array[i].toString()));
        }

        return result;
    }

    // экранирование строки
    function __escape(data) {

        var
                SCRIPT_REGEX = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
                __data = data;

        while (SCRIPT_REGEX.test(__data)) {
            __data = __data.replace(SCRIPT_REGEX, "");
        }

        return __data;

    }

    // отрисовка html popup описания услуг
    function __renderServicePopup(data, messages) {

        var html = "", smallImgsHtml = "", bigImgsHtml = "", _i, liHtml = "", _cnt;

        for (_i = 0; _i < data.small_imgs.length; _i++) {
            smallImgsHtml += "<img src='" + data.small_imgs[_i] + "'>";
        }

        if (smallImgsHtml !== "") {
            for (_i = 0; _i < data.big_imgs.length; _i++) {
                bigImgsHtml += "<img class='lazyOwl' data-src='" + data.big_imgs[_i] + "'>";
            }
        }

        if (bigImgsHtml !== "") {
            html += "<div class=\"w-66 gallery-wrapper detail-slider mr-15\">";
            html += "<div class=\"slide-room-lg\">";
            html += "<div id=\"" + bigGalleryId + "\">";
            html += bigImgsHtml;
            html += "</div>";
            html += "</div>";
            html += "<div class=\"slide-room-sm\">";
            html += "<div class=\"row\">";
            html += "<div class=\"col-md-8 col-md-offset-2\">";
            html += "<div id=\"" + smallGalleryId + "\">";
            html += smallImgsHtml;
            html += "</div>";
            html += "</div>";
            html += "</div>";
            html += "</div>";
            html += "</div>";
        }

        html += "<div class=\"service-description\">";

        if (data.name) {
            html += "<h3>" + data.name + "</h3>";
        }

        if (data.desc) {
            html += "<div class=\"desc\">" + data.desc + "</div>";
        }

        if (data.square) {
            liHtml += "<li><b>" + messages.square + "</b>: " + data.square + "</li>";
        }

        if (data.bad1 !== null) {
            liHtml += "<li><b>" + messages.cntBad1 + "</b>: " + data.bad1 + "</li>";
        }

        if (data.bad2 !== null) {
            liHtml += "<li><b>" + messages.cntBad2 + "</b>: " + data.bad2 + "</li>";
        }

        if (data.sofa_bad !== null) {
            liHtml += "<li><b>" + messages.cntSofaBad + "</b>: " + data.sofa_bad + "</li>";
        }

        if (data.places_add) {
            liHtml += "<li><b>" + messages.cntAddPlaces + "</b>: " + data.places_add + "</li>";
        }

        if (data.places_main) {
            liHtml += "<li><b>" + messages.cntMainPlaces + "</b>: " + data.places_add + "</li>";
        }

        if (data.people) {
            liHtml += "<li><b>" + messages.maxPeople + "</b>: " + data.people + "</li>";
        }

        if (data.servicesIn) {
            liHtml += "<li><b>" + messages.servicesIn + "</b>: <ul>";
            _cnt = data.servicesIn.length;
            for (_i = 0; _i < _cnt; _i++) {
                liHtml += "<li>" + data.servicesIn[_i] + "</li>";
            }
            liHtml += "</ul></li>";
        }

        if (liHtml !== "") {
            html += "<ul>" + liHtml + "</ul>";
        }

        html += "</div>";
        html += "<div class=\"clearfix\"></div>";

        return html;

    }

    // отрисовка html popup описания тарифов
    function __renderRatePopup(data) {

        var html = "";

        html += "<div class=\"rate-description\">";
        if (data.title) {
            html += "<h3>" + data.title + "</h3>";
        }
        if (data.desc) {
            html += "<div class=\"desc\">";
            html += data.desc;
            html += "</div>";
        }
        html += "</div>";
        html += "<div class=\"clearfix\"></div>";

        return html;
    }

    // отрисовка html popup условий отмены
    function __renderCancellationPolicyPopup(data, messages) {

        var html = "";

        data.text = data.text || messages.cancellationPolicyDefaultText;

        html += "<div class=\"cancellation-policy\">";
        if (data.text) {
            html += "<div class=\"cancellation-policy-text\">";
            html += data.text || messages.cancellationPolicyDefaultText;
            html += "</div>";
        }
        html += "</div>";
        html += "<div class=\"clearfix\"></div>";

        return html;

    }

    // фильтрация данных для описания условий отмены
    function __dataCancellationPolicyFilter(data) {

        var __data = {text: null};
        if (typeof data === "object") {

            if (typeof data.CANCELLATION_POLICY_TEXT === "string") {
                __data.text = __escape(data.CANCELLATION_POLICY_TEXT);
            }

        }

        return __data;
    }

    // фильтрация данных для описания тарифов
    function __dataRateFilter(data) {

        var __data = {
            title: null,
            desc: null
        };

        if (typeof data === "object") {

            if (typeof data.NAME === "string") {
                __data.title = __escape(data.NAME);
            }

            if (typeof data.NOTE === "string") {
                __data.desc = __escape(data.NOTE);
            }

        }

        return __data;
    }

    // фильтрация данных для описания услуг
    function __dataServiceFilter(data) {

        var __data = {
            name: null,
            desc: null,
            small_imgs: [],
            big_imgs: [],
            people: null,
            sofa_bad: null,
            square: null,
            places_add: null
        };

        if (typeof data === "object") {

            if (typeof data.NAME === "string") {
                __data.name = __escape(data.NAME);
            }

            if (typeof data.DESC === "string") {
                __data.desc = __escape(data.DESC);
            }

            if (data.PICTURES && typeof data.PICTURES === "object") {

                if ($.isArray(data.PICTURES.small)) {
                    __data.small_imgs = __strValInArray(data.PICTURES.small);
                }

                if ($.isArray(data.PICTURES.big)) {
                    __data.big_imgs = __strValInArray(data.PICTURES.big);
                }

            }

            if ($.isArray(data.SERVICES)) {
                __data.servicesIn = data.SERVICES;
            }

            if (typeof data.PEOPLE !== "undefined") {
                __data.people = Number(data.PEOPLE);
            }

            if (typeof data.BAD1 !== "undefined") {
                __data.bad1 = Number(data.BAD1);
            }

            if (typeof data.BAD2 !== "undefined") {
                __data.bad2 = Number(data.BAD2);
            }

            if (typeof data.SOFA_BAD !== "undefined") {
                __data.sofa_bad = Number(data.SOFA_BAD);
            }

            if (typeof data.PLACES_ADD !== "undefined") {
                __data.places_add = Number(data.PLACES_ADD);
            }

            if (typeof data.SQUARE !== "undefined") {
                __data.square = Number(data.SQUARE);
            }

        }

        return __data;

    }

    // подготовка общих параметров
    function __totalOptions(options) {

        var _options = {};
        if (typeof options.anchor === "undefined" || !options.anchor) {
            throw new Error("Укажите корректный якорь");
        }

        _options.anchor = options.anchor;

        if (typeof options.sessid === "undefined" || !options.sessid) {
            throw new Error("Неизвестеный идентификатор пользователя");
        }

        _options.sessid = options.sessid;

    }

    // подготовка параметров языковых фраз
    function __messagesOptions(options, keys) {

        var _options = {messages: {}};

        if (typeof options.messages !== "object" || !options.messages) {
            throw new Error("Введите языковы фаразы");
        }

        $(keys).each(function (i, el) {
            if (typeof options.messages[el] === "string" && options.messages[el].toString() !== "") {
                _options.messages[el] = options.messages[el];
            }
        });

    }

    // подготовка параметров для формирования popup
    function __popupOptions(options) {

        var _options = $.extend({}, __messagesOptions(options, ["maxPeople", "cntSofaBad", "square", "servicesIn"]));

        return _options;

    }

    // подготовка параметров для добавления в корзину
    function __addToCartOptions(options) {

        var _options = {};

        if (typeof options.redirect !== "string" || !options.redirect.length) {
            throw new Error("Следует указать страницу перехода для бронирования");
        }

        _options.redirect = options.redirect;

        return _options;

    }

    // подготовка параметров для цен для граждан
    function __citizenOptions(options) {

        var _options = $.extend({}, __messagesOptions(options, ["no_result"]));

        return _options;

    }

    // проверка данных аякс ответа на наличие ошибки
    // инициирует исключение, если ошибка
    function __checkError(data) {
        if (typeof data.error_message === "string" && !data.error_message.length) {
            alert(data.error_message);
            throw new Error(data.error_message);
        }
    }

    // инициализация popup
    function __init(options) {

        options.that.one("click", function (e) {

            var $this = $(this);

            delete toOff[options.index];

            function _popupInitAndOpen($this) {
                $this.magnificPopup({
                    type: "inline",
                    midClick: true
                }).magnificPopup("open");
            }

            if ($("#" + options.popupAreaId).length) {
                _popupInitAndOpen($this);
            } else {

                options.that.after("<div id='" + options.popupAreaId + "'><div class=\"defmess\">" + options.messages.loadingMessage + "</div></div>");
                _popupInitAndOpen($this);

                $.ajax({
                    url: __ajaxUrl,
                    data: {type: options.type, id: options.id, sessid: options.sessid},
                    dataType: "json",
                    statusCode: {
                        404: function () {
                            options.that.on("click", function (e) {
                                e.preventDefault();
                            });
                        }
                    },
                    success: function (data) {

                        var popupWrapper, smallGallery, bigGallery, popupHtml, defmessArea;

                        if (!data) {
                            return;
                        }

                        __checkError(data);

                        popupHtml = options.renderFn(options.dataFilterFn(data), options.messages);

                        popupWrapper = $("#" + options.popupAreaId);

                        defmessArea = popupWrapper.find('.defmess');

                        if (defmessArea.length > 0) {

                            defmessArea.replaceWith(popupHtml);
                        } else {

                            popupWrapper.html(popupHtml);
                        }

                        smallGallery = popupWrapper.find("#" + smallGalleryId);

                        bigGallery = popupWrapper.find("#" + bigGalleryId);

                        bigGallery.owlCarousel({
                            items: 1,
                            lazyLoad: true,
                            navigation: true,
                            navigationText: ["<span class='prev-next-room prev-room'></span>", "<span class='prev-next-room next-room'></span>"],
                            pagination: false,
                            itemsCustom: [[320, 1], [480, 1], [768, 1], [992, 1], [1200, 1]]
                        });

                        smallGallery.owlCarousel({
                            mouseDrag: false,
                            navigation: false,
                            itemsCustom: [[320, 3], [480, 3], [768, 3], [992, 3], [1200, 3]],
                            pagination: false,

                        });

                        smallGallery.on("click", ".owl-item", function (e) {
                            e.preventDefault();
                            if ($(this).hasClass('synced')) {
                                return false;
                            } else {
                                $('.synced').removeClass('synced');
                                $(this).addClass('synced');
                                var number = $(this).data("owlItem");
                                bigGallery.data('owlCarousel').goTo(number);
                            }
                        });

                    }

                });
            }

            e.preventDefault();
        });

    }

    function __initCommonPopup(options, parameters) {

        options = $.extend({}, options, __totalOptions(options), __popupOptions(options));
        ;

        $(options.anchor).each(function () {

            var $this = $(this),
                    initParameters = {
                        that: $this,
                        type: parameters.type,
                        id: $this.data("id"),
                        sessid: options.sessid,
                        dataFilterFn: parameters.dataFilterFn,
                        popupAreaId: $this.attr("href").replace("#", ""),
                        renderFn: parameters.renderFn,
                        messages: options.messages
                    };

            if (typeof parameters.setToOff !== "undefined" && parameters.setToOff === true) {
                initParameters.index = toOff.push($this) - 1;
            }

            __init(initParameters);

        });

    }

    function __initSettlingByRoomsOffers(parameters) {
        if (parameters.dynamic_calculation_placements_add2cart) {

            if (parameters.dynamic_calculation_placements_add2cart.initialization) {

                var rooms = document.querySelectorAll('.resultRoom');

                [].slice.call(rooms).forEach(function (room) {
                    room.onclick = function (e) {
                        var options = null;
                        var i = 0;

                        if (!e.target.closest('.optionsRoom')) {
                            return;
                        }

                        options = e.target.closest('.optionsRoom').querySelectorAll('.optionRoom');

                        for (i = 0; i < options.length; i++) {

                            options[i].classList.remove('active');
                            options[i].querySelector('input').checked = false;

                        }
                        i = [].indexOf.call(options, e.target.closest('.optionRoom'));
                        options[i].classList.toggle('active');
                        options[i].querySelector('input').checked = true;


                        var result_price = 0.00;
                        $(options[i]).closest('.rooms').find('.optionsRoom').find(".optionRoom.active").each(function () {
                            result_price += $(this).data('price-value');
                        });

                        $(options[i]).closest('.resultRoom').find('.result').find('.price-value').text(new Intl.NumberFormat('ru-RU').format(result_price).replace(",", "."));
                    };
                });

                $('.add2basket').each(function () {

                    this.onclick = function (e) {
                        e.preventDefault();

                        var $this = $(this);
                        var optionsRoom = $this.closest(".resultRoom").find('.rooms').find('.optionsRoom');
                        var activeOptions = optionsRoom.find(".optionRoom.active");
                        var need = optionsRoom.length - activeOptions.length;

                        var add2basket_offers = [];

                        if (need) {
                            alert(`Для продолжения бронирования необходимо выбрать еще ${need} ${need === 1 ? 'тариф' : 'тарифа'}`);
                            return;
                        }

                        activeOptions.each(function () {
                            add2basket_offers.push($(this).data('add2cart-request'));
                        });

                        if (add2basket_offers.length) {
                            $.ajax({
                                url: __ajaxUrl,
                                data: {type: "settling_by_rooms_add2basket", sessid: parameters.sessid, add2basket_offers: add2basket_offers},
                                dataType: "json",
                                success: function (resp) {
                                    if (resp.status === "ok") {
                                        location = $this.attr('href');
                                    }
                                }
                            });
                        }

                    };

                });
            }
        }
    }

    // инициализация popup по услугам
    $.initServicePopup = function (options) {

        __initCommonPopup(options, {
            renderFn: __renderServicePopup,
            dataFilterFn: __dataServiceFilter,
            type: 'service',
            setToOff: true
        });


    };

    // инициализация popup по тарифам
    $.initRatePopup = function (options) {

        __initCommonPopup(options, {
            renderFn: __renderRatePopup,
            dataFilterFn: __dataRateFilter,
            type: 'rate',
            setToOff: true
        });

    };

    $.initCancellationPolicyPopup = function (options) {

        __initCommonPopup(options, {
            renderFn: __renderCancellationPolicyPopup,
            dataFilterFn: __dataCancellationPolicyFilter,
            type: 'cancellation_policy',
            setToOff: true
        });

    };

    // инициализация добавления в корзину
    $.addToCartInit = function (options) {

        options = $.extend({}, options, __totalOptions(options), __addToCartOptions(options));

        $(options.anchor).each(function () {

            $(this).on("click", function (e) {
                var $this = $(this);
                e.preventDefault();
                if ($this.hasClass('add-to-cart')) {
                    $.ajax({
                        url: __ajaxUrl,
                        dataType: "Json",
                        data: {add2cart: $this.data("add2cart"), sessid: options.sessid},
                        success: function (data) {
    
                            __checkError(data);
    
                            if (typeof data.message_ok === "string" && data.message_ok === "ok") {
                                $this.removeClass('add-to-cart');
                                $this.addClass('green-button');
                                $this.text(BX.message('GO_TO_BASKET'));
                                $this.attr('href',options.redirect);
                                $this.attr('data-add2cart','');
                                $('.switch.basket').html(data.baskethtml);
                                $this.click(function(){e.preventDefault(); document.location.href = options.redirect });
                            }
    
    					}
    				});
                }
                
					/*var popup = null;
		
					popup = BX.PopupWindowManager.create("popup-booking-stop-notify", window.body, {
						content: $("#booking-stop-notify").html(),
						autoHide: true,
						closeByEsc : true,
						overlay: {
							backgroundColor: '#000', 
							opacity: 10
						}
					});
				
					popup.show();
					
					$("#popup-booking-stop-notify .arcticmodal-close").on("click", function () {
						popup.close();
					});*/
            });

        });

    }

    $.citizenPricesInit = function (options) {

        var cache = {};

        options = $.extend({}, options, __totalOptions(options), __citizenOptions(options), __addToCartOptions(options), __popupOptions(options));

        cache[$(options.anchor).val()] = $(options.insertContainer).html();

        function __insert(content) {

            $(toOff).each(function (i, el) {
                if (typeof el !== "undefined" && el) {
                    el.off("click");
                }
            });

            toOff = [];

            if (content) {
                $(options.insertContainer).html(content);

                if (options.initServicePopup) {
                    $.initServicePopup({
                        sessid: options.sessid,
                        anchor: options.servicesAnchor,
                        messages: {
                            maxPeople: options.messages.maxPeople,
                            cntSofaBad: options.messages.cntSofaBad,
                            square: options.messages.square,
                            servicesIn: options.messages.servicesIn,
                            cntAddPlaces: options.messages.cntAddPlaces,
                            cntBad1: options.messages.cntBad1,
                            cntBad2: options.messages.cntBad2,
                            cntMainPlaces: options.messages.cntMainPlaces
                        }
                    });
                }

                if (options.initRatePopup) {
                    $.initRatePopup({
                        sessid: options.sessid,
                        anchor: options.rateAnchor,
                        messages: {}
                    });
                }

                if (options.initCancellationPolicyPopup) {
                    $.initCancellationPolicyPopup({
                        sessid: options.sessid,
                        anchor: options.cancellationPolicyAnchor,
                        messages: {cancellationPolicyDefaultText: options.messages.cancellationPolicyDefaultText}
                    });
                }

                if (options.initAddToCart) {
                    $.addToCartInit({
                        sessid: options.sessid,
                        anchor: options.addToCartAnchor,
                        redirect: options.redirect
                    });
                }
                
                __initSettlingByRoomsOffers(options);

            } else {
                $(options.insertContainer).html(options.messages.no_result);
            }

        }

        $(options.anchor).on("change", function () {

            var val = $(this).val();

            if (typeof cache[val] === "undefined") {
                options.sparams.citizen_price = val;
                $.ajax({
                    url: __ajaxUrl,
                    dataType: "Json",
                    data: {type: "cprice", sessid: options.sessid, sparams: options.sparams},
                    success: function (data) {

                        __checkError(data);

                        if (typeof data.content !== "undefined") {

                            cache[val] = __escape(data.content);
                        } else {

                            cache[val] = null;

                        }

                        __insert(cache[val]);

                    }
                });

            } else {

                __insert(cache[val]);

            }

        });

    };

    $.runSearchOffersResultDialog = function (parameters) {

        if (parameters.lazyLoad) {

            $.ajax({
                url: __ajaxUrl,
                dataType: "Json",
                timeout: 40000,
                error: function () {
                    $(parameters.insertContainer).html(parameters.messages.no_result);
                },
                data: {type: "get_search_offers_result", sessid: parameters.sessid, sparams: parameters.sparams, filter_by_prices_for_citizen: parameters.initCitizenPricePopup},
                success: function (resp) {

                    __checkError(resp);

                    if (typeof resp.content !== "undefined") {
                        $(parameters.insertContainer).html(resp.content);

                        $(parameters.specifyingFormBtnAnchor).prop("disabled", false);

                        parameters.lazyLoad = false;
                        if (
                                (typeof resp.parameters.CALCULATION !== "undefined" && resp.parameters.CALCULATION) ||
                                (typeof resp.parameters.SETTLING_BY !== "undefined" && resp.parameters.SETTLING_BY)
                                ) {

                            $(parameters.citizenPriceAnchor).closest("select").prop("disabled", false);

                            parameters.initServicePopup = resp.parameters.SERVICE_POPUP_JS;
                            parameters.initRatePopup = resp.parameters.RATE_POPUP_JS;
                            parameters.initCancellationPolicyPopup = resp.parameters.CANCELLATION_POLICY_POPUP_JS;

                            if (typeof resp.parameters.CANCELLATION_POLICY === "string") {
                                parameters.cancellationPolicyDefaultText = resp.parameters.CANCELLATION_POLICY;
                            }
                        }

                        parameters.dynamic_calculation_placements_add2cart = resp.parameters.dynamic_calculation_placements_add2cart;

                        $.runSearchOffersResultDialog(parameters);
                    }
                }
            });

        } else {

            if (parameters.initServicePopup) {
                $.initServicePopup({
                    sessid: parameters.sessid,
                    anchor: parameters.servicesAnchor,
                    messages: {
                        maxPeople: parameters.messages.maxPeople,
                        cntSofaBad: parameters.messages.cntSofaBad,
                        square: parameters.messages.square,
                        servicesIn: parameters.messages.servicesIn,
                        cntAddPlaces: parameters.messages.cntAddPlaces,
                        cntBad1: parameters.messages.cntBad1,
                        cntBad2: parameters.messages.cntBad2,
                        cntMainPlaces: parameters.messages.cntMainPlaces
                    }
                });
            }

            if (parameters.initRatePopup) {
                $.initRatePopup({
                    sessid: parameters.sessid,
                    anchor: parameters.rateAnchor,
                    messages: {}
                });
            }

            if (parameters.initCancellationPolicyPopup) {
                $.initCancellationPolicyPopup({
                    sessid: parameters.sessid,
                    anchor: parameters.cancellationPolicyAnchor,
                    messages: {cancellationPolicyDefaultText: parameters.messages.cancellationPolicyDefaultText}
                });
            }

            if (parameters.initAddToCart) {
                $.addToCartInit({
                    sessid: parameters.sessid,
                    anchor: parameters.addToCartAnchor,
                    redirect: parameters.redirect
                });
            }

            if (parameters.initCitizenPricePopup) {

                parameters.anchor = parameters.citizenPriceAnchor;
                $.citizenPricesInit(parameters);
            }

            __initSettlingByRoomsOffers(parameters);
        }

    };
})(jQuery, document);

