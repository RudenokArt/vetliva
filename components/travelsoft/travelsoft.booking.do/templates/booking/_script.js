/* 
 * Jquery script ajax
 * 
 *  use bootstrap css classes 
 * 
 * travelsoft.booking.do component
 * 
 * регистрация
 * авторизация 
 * удаление позиций корзины
 * 
 * Доступные action запроса:
 *  
 *    check_auth - проверка существования пользователя по email
 *    do_registration - попытка регистрации пользоватя
 *    do_authorize - попытка авторизации пользователя
 *    delete - удаление позиции из корзины 
 * 
 * Статусы ответов:
 *                  0 - не удалось удалить позицию в корзине
 *                  1 - удаление позиции прошло удачно
 *                  2 - введён некорректный email
 *                  3 - удачная авторизация
 *                  4 - ошибка авторизации
 *                  5 - email и подтверждение email не совпадают
 *                  6 - успешная регистрация
 *                  7 - ошибка регистрации
 *                  8 - пользователя с введенным $email не существует
 *                  9 - пользователь с введенным $email существует
 *                  10 - необходимо ввести промокод
 *                  11 - промокод не найден или срок его действия истек
 *                  12 - промокод уже применен
 *                  13 - промокод неактивен
 *                  14 - данный промокод закончился
 *                  15 - общая стоимость корзины недостаточна, чтобы применить данный промокод
 *                  16 - промокод не действует на данные виды услуг
 *                  17 - промокод успешно применен
 *                  18 - промокод недоступен для данной группы пользователей 
 *                  19 - гражданство туристов соответсвует услугам в корзине 
 *                  20 - гражданство туристов не соответсвует услугам в корзине 
 *                  21 - вернулся результат пересчета корзины в зависимости от гражданства
 *                  22 - корзина установлена
 * 
 */

/**
 * @param {jQuery} $
 * @param {Object} JSON
 * @returns {undefined}
 */

"use strict";

(function ($, JSON, bx) {
    var __ajax_url = "/local/components/travelsoft/travelsoft.booking.do/ajax.php",
            __global_data_scope = {

                sessid: null,

                citizenships: {},

                cart_item_class_container: "item-cart",

                booking_form_id: "booking",

                preloader: null,

                active_service_count: 0,

                cart_items_count: 0,

                empty_cart_message: "your cart is empty",

                del_pos_array: null,

                messages: {
                    booking_btn: "BOOKING NOW",
                    confirm_email_error: "error",
                    confirm_email_not_equal_email: "error",
                    placeholder_confirm_email: "Confirm email",
                    placeholder_password: "Password",
                    do_registration_button: "Register",
                    do_authorize_button: "Authorize",
                    forgot_password: bx.message('DIALOG_FORGOT_PASSWORD'),
                    empty_email: "empty email",
                    enter_the_password: "Enter the password",
                    _404: "System error. Please contact the site administrator (_404)",
                    _500: "System error. please contact the site administrator (_500)",
                    default_error_text: "Error. Please contact the site administrator (_default)",
                    status_0: "error",
                    status_1: "error",
                    status_2: "error",
                    status_3: "error",
                    status_4: "error",
                    status_5: "error",
                    status_6: "error",
                    status_7: "error",
                    status_8: "error",
                    status_9: "error",
                    citizen_popup: {},
                    now_regisrer: bx.message('NOW_REGISTER')
                },

                email_input_id: null,

                forgot_password_link: null,

                message_box_id: "__message-box",

                is_authorized: false,

                error_box_html_id: "__error-box",

                registration_box_id: "__registration_box",

                authorize_box_id: "__authorize_box",

                ajax_flag: false,

                $email_input: null,

                $mount_point: null,

                $booking_btn: null

            };

    /**
     * проверка email на корректность 
     * @param {String} email
     * @returns {Boolean}
     */
    function __email_validation(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    /**
     * @param {mixed} data
     * @returns {undefined}
     */
    function __log(data) {
        console.log(data);
    }

    /**
     * отрисовка области текста ошибки
     * @param {String} message
     * @returns {undefined}
     */
    function __render_error_box(message, block) {

        var error_box = __global_data_scope.$mount_point.siblings("#" + __global_data_scope.error_box_html_id), next = null;

        if (!error_box.length) {
            next = __global_data_scope.$mount_point.next();
            if (typeof block === "undefined") {
                if (next.length) {
                    next.after("<div id='" + __global_data_scope.error_box_html_id + "' style='color: red; display:none'></div>");
                } else {
                    __global_data_scope.$email_input.before("<div id='" + __global_data_scope.error_box_html_id + "' style='color: red; display:none'></div>");
                }
            }
            else {
                $("#" + __global_data_scope.error_box_html_id).remove();
                $("input[name='"+block+"']").after("<div id='" + __global_data_scope.error_box_html_id + "' style='color: red; display:none'></div>");
            }
            
            error_box = $("#" + __global_data_scope.error_box_html_id);
        }

        error_box.text(message);
        error_box.slideDown();

    }

    /**
     * удаление области текста ошибки
     * @returns {undefined}
     */
    function __delete_error_box() {

        $("#" + __global_data_scope.error_box_html_id).remove();

    }

    /**
     * отрисовка области текста сообщения
     * @param {String} message
     * @returns {undefined}
     */
    function __render_message_box(message, ) {

        var message_box = __global_data_scope.$mount_point.next("#" + __global_data_scope.message_box_id);

        if (!message_box.length) {
            __global_data_scope.$mount_point.after("<div id='" + __global_data_scope.message_box_id + "' style='color: green; display:none'></div>");
            message_box = $("#" + __global_data_scope.message_box_id);
        }

        message_box.text(message);
        message_box.slideDown();

    }

    /**
     * удаление области текста сообщения
     * @returns {undefined}
     */
    function __delete_message_box() {
        $("#" + __global_data_scope.message_box_id).remove();
    }

    /**
     * инициализация области для регистрации пользователя
     * отправка запроса
     * @returns {undefined}
     */
    function __init_registration_box(message) {

        var reg_box = __global_data_scope.$mount_point.next("#" + __global_data_scope.registration_box_id);

        if (!reg_box.length) {
            $("input[name='make_booking[email]']").parent().find('h3').append('<span id="now_register_alert" class="input-description">'+ __global_data_scope.messages.now_regisrer +'</span>');
            $("input[name='make_booking[email]']").css('margin-top', '0');
            // отрисовка
           // $('#main-email').before();
            __global_data_scope.$mount_point.parent().after(
                    "<div id='" + __global_data_scope.registration_box_id + "'><div class=\"form-field\"><h3>"+message+"</h3>" +
                    "<input  name=\"confirm_email\" required=\"\" type=\"email\" placeholder=\"" + __global_data_scope.messages.placeholder_confirm_email + "\" class=\"field-input\"></div>" +
                    "<div style='text-align: right; margin-top: 5px;'>" +
                    "<button class='awe-btn awe-btn-lager  awe-btn-1' id='__reg_button'>" + __global_data_scope.messages.do_registration_button + "</button>" +
                    "</div>" +
                    "</div>"
                    );
                    
                    $("input[name='confirm_email']").on("change", function () {
                        if(document.querySelector('#__reg_button').classList.contains('heartBeat')){
                            [].slice.call(document.querySelectorAll('#__reg_button')).forEach(function (item) {
                                item.classList.remove('heartBeat');
                            })
                        }
                        setTimeout(function () {
                            [].slice.call(document.querySelectorAll('#__reg_button')).forEach(function (item) {
                                item.classList.add('heartBeat');
                            })
                        },300)
                    });
                    $("body").on("click", function () {
						if(document.querySelector('#__reg_button'))
						{
							if(document.querySelector('#__reg_button').classList.contains('heartBeat')){
								[].slice.call(document.querySelectorAll('#__reg_button')).forEach(function (item) {
									item.classList.remove('heartBeat');
								})
							}
						}
                        setTimeout(function () {
                            [].slice.call(document.querySelectorAll('#__reg_button')).forEach(function (item) {
                                item.classList.add('heartBeat');
                            })
                        },300)
                    });

            $("#__reg_button").on("click", function (e) {
//            $("#" + __global_data_scope.booking_btn_id).on("click", function (e) {

                var email = __global_data_scope.$email_input.val(),
                        confirm_email = $("#" + __global_data_scope.registration_box_id).find("input[name='confirm_email']").val(), $this = $(this);
                
                var name=$("input[name='make_booking[tourist][0][name]']").val(),
                    last_name=$("input[name='make_booking[tourist][0][last_name]']").val(),
                    birthdate=$("input[name='make_booking[tourist][0][birthdate]']").val(),
                    citizenship=$("select[name='make_booking[tourist][0][citizenship]']").val(),
                    male=$("select[name='make_booking[tourist][0][male]']").val();

                e.preventDefault();

                if (!__email_validation(confirm_email)) {
                    __render_error_box(__global_data_scope.messages.confirm_email_error, 'confirm_email');
                    return;
                }

                if (confirm_email.toString() !== email.toString()) {
                    __render_error_box(__global_data_scope.messages.confirm_email_not_equal_email, 'confirm_email');
                    return;
                }

				if(citizenship == bx.message('PLACEHOLDER_CITIZEN_SELECT'))
				{
					$('.citizen-empty').remove();
					var msg = "<br class='citizen-empty'><span class='error-container citizen-empty' style='color:red;font-size: 13px'>" + bx.message('WRONG_CITIZENSHIP') + "</span>";
					$("#citizen_empty").after(msg);
					$('html, body').animate({
                    	scrollTop: $("#citizen_empty").offset().top - 100
                	}, 1000);
					return;
				}

                // запрос на регистрацию
                __send_request({
                    beforeSend: function () {
                        $('#form-shading').show();
                        //$this.html("<img src='" + __global_data_scope.preloader + "' alt='gif-preloader'>");
                    },
                    complete: function () {
                        $('#form-shading').hide();
                        $this.html(__global_data_scope.messages.booking_btn);
                    },
                    action: "do_registration",
                    email: email,
                    confirm_email: confirm_email,
                    name: name,
                    last_name: last_name,
                    birthdate: birthdate,
                    male: male,
                    citizenship: citizenship
                });

            });

			$("select[name='make_booking[tourist][0][citizenship]']").on("change", function () {
				$('.citizen-empty').remove();
			});

        }

    }

    /**
     * Возвращает options для select выбора туристов
     * @param {Object} tourists
     * @returns {String}
     */
    function __get_tourists_select_options(tourists) {
        
        var tourists_options = ``;
        
        for (var index in tourists) {
            tourists_options += `<option value="${index}">${tourists[index].name} ${tourists[index].last_name} (${__global_data_scope.citizenships[tourists[index].citizenship]})</option>`;
        }

        return tourists_options;
    }

    /**
     * Возвращает select для выбора введенных туристов
     * @param {Object} cart_item
     * @param {Object} tourists
     * @returns {String}
     */
    function __get_tourists_select_for_verification_popup(options_cnt, cart_item_postion, people_type, tourists) {
        
        var selects = ``;
        
        while (options_cnt) {
            selects += `
            <select onchange="$(this).removeClass('red-border')" data-cart-item-position="${cart_item_postion}" data-people-type="${people_type}">
                <option value="">...</value>
                ${__get_tourists_select_options(tourists)}
            </select>
            `;
            options_cnt--;
        }

        return selects;

    }

    /** удаление области для регистрации **/
    function __delete_registration_box() {

        // $("#__reg_button").off("click");
        $("#" + __global_data_scope.booking_btn_id).off("click");
        $("#" + __global_data_scope.registration_box_id).remove();
        $("#now_register_alert").remove();
        $("input[name='make_booking[email]']").css('margin-top', '25');
    }

    /** инициализация блока авторизации **/
    function __init_authorize_box(message) {

        var auth_box = $("#" + __global_data_scope.authorize_box_id);

        if (!auth_box.length) {
            // отрисовка
            __global_data_scope.$mount_point.parent().after(
                    "<div id='" + __global_data_scope.authorize_box_id + "'>" +
                    "<div class=\"form-field\"><h3>"+message+"</h3><input name=\"password\" required=\"\" type=\"password\" placeholder=\"" + __global_data_scope.messages.placeholder_password + "\" class=\"field-input\"></div>" +
                    "<div style='text-align: right'>" +
                    "<div style='padding: 5px 0'><a href='" + __global_data_scope.forgot_password_link + "'>" + __global_data_scope.messages.forgot_password + "</a></div>" +
                    "<button class='btn btn-primary' id='__auth_button'>" + __global_data_scope.messages.do_authorize_button + "</button>" +
                    "</div>" +
                    "</div>"
                    );

            $("#__auth_button").on("click", function (e) {
//            $("#" + __global_data_scope.booking_btn_id).on("click", function (e) {

                var password = $("#" + __global_data_scope.authorize_box_id + " input[name='password']").val(), $this = $(this);

                if (password.toString() === "") {
                    __render_error_box(__global_data_scope.messages.enter_the_password);
                    return;
                }

                __send_request({
                    beforeSend: function () {
                        $('#form-shading').show();
//                        $this.html("<img src='" + __global_data_scope.preloader + "' alt='gif-preloader'>");
                    },
                    complete: function () {
                        $('#form-shading').hide();
//                        $this.html(__global_data_scope.messages.booking_btn);
                    },
                    email: __global_data_scope.$email_input.val(),
                    password: password,
                    action: "do_authorize"
                });

                e.preventDefault();
            });

        }

    }

    /** удаление блока атовризации **/
    function __delete_authorize_box() {

//        $("#__auth_button").off("click");
        $("#" + __global_data_scope.booking_btn_id).off("click");
        $("#" + __global_data_scope.authorize_box_id).remove();

    }

    /**
     * @param {Boolean} full
     * @returns {undefined}
     */
    function __destroy(full) {

        if (full) {

            __global_data_scope.$email_input.off("focusout");
            __delete_error_box();
            __delete_registration_box();
            __delete_authorize_box();

        } else {

            __delete_error_box();
            __delete_message_box();
            __delete_registration_box();
            __delete_authorize_box();

        }

    }

    /**
     * @param {Object} data
     * @param {Object} scope
     * @returns {String}
     */
    function __get_popup_cart_items_content(data, scope, text) {

        return `
                <div class="container">
                    <div class="text-block">${text}</div>


                        ${(function () {

            var cart_items_content = ``;

            for (var i = 0; i < data.cart_items.length; i++) {

                cart_items_content += `<div class="popup-cart-item blueborder row">`;
                cart_items_content += `<div class="text-center col-md-2 col-sm-4 col-xs-8">${data.cart_items[i].image_src ? `<img src="${data.cart_items[i].image_src}">` : ``}</div>`;
                cart_items_content += `
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <div class="mt-5"><b>${__global_data_scope.messages.citizen_popup.name}:</b> ${data.cart_items[i].name}</div>
                                            <div class="mt-5"><b>${__global_data_scope.messages.citizen_popup.rate}:</b> ${data.cart_items[i].rate_name}</div>
                                            <div class="mt-5"><b>${__global_data_scope.messages.citizen_popup.date_from}:</b> ${data.cart_items[i].date_from_formatted}</div>
                                            <div class="mt-5"><b>${__global_data_scope.messages.citizen_popup.date_to}:</b> ${data.cart_items[i].date_to_formatted}</div>
                                            ${
                        data.cart_items[i].recalculated ?
                        `<div class="mt-5"><b>Взрослых:</b> ${data.cart_items[i].adults}</div>
                                                    <div class="mt-5"><b>Детей:</b> ${data.cart_items[i].children}</div>` : ``
                        }
                                        </div>
                                `;
                cart_items_content += `
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            ${
                        !data.cart_items[i].can_buy ?
                        `<b class="red">${__global_data_scope.messages.citizen_popup.not_avail_offer}</b>` :
                        `<table>
                                                    ${
                        !data.cart_items[i].recalculated ?
                        `<tr>
                                                            <td><b>${__global_data_scope.messages.citizen_popup.choose_adults}:</b></td>
                                                            <td>
                                                                ${__get_tourists_select_for_verification_popup(data.cart_items[i].adults, data.cart_items[i].position, "adults", scope.tourist)}
                                                            </td>
                                                        </tr>` : ``
                        }
                                                    ${
                        !data.cart_items[i].recalculated && data.cart_items[i].children > 0 ?
                        (function () {
                            return `
                                                            <tr>
                                                                <td><b>${__global_data_scope.messages.citizen_popup.choose_children}:</b></td>
                                                                <td>
                                                                    ${__get_tourists_select_for_verification_popup(data.cart_items[i].children, data.cart_items[i].position, "children", scope.tourist)}
                                                                </td>
                                                            </tr>
                                                            `;
                        })() : ``
                        }
                                                    ${
                        typeof data.cart_items[i].price_before != "undefined" ?
                        `<tr>
                                                            <td><b>${__global_data_scope.messages.citizen_popup.cost_before}:</b></td>
                                                            <td>
                                                                <b>${data.cart_items[i].price_before}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><b class="green">${__global_data_scope.messages.citizen_popup.cost_after}:</b></td>
                                                            <td>
                                                                <b class="green">${data.cart_items[i].price_after}</b>
                                                            </td>
                                                        </tr>` :
                        `<tr>
                                                            <td><b>${__global_data_scope.messages.citizen_popup.cost}:</b></td>
                                                            <td>
                                                                <b>${data.cart_items[i].price}</b>
                                                            </td>
                                                        </tr>`
                        }
                                                </table>`
                        }
                                        </div>
                                `;
                cart_items_content += `</div>`;
            }

            return cart_items_content;
        })()}
                    <div class="text-block text-right">
                        <div class="mt-5"><b>${__global_data_scope.messages.citizen_popup.discount}: ${data.discount}</b></div>
                        <div class="mt-5"><b>${__global_data_scope.messages.citizen_popup.total_cost}: ${data.total_cost}</b></div>
                    </div>
                </div>
            `;
    }

    /**
     * отправка ajax запроса
     * @param {Object} scope
     * @returns {undefined}
     */
    function __send_request(scope) {

        var request_data = {
            sessid: __global_data_scope.sessid,
            action: scope.action
        };

        if (scope.email) {
            request_data.email = scope.email;
        }

        if (scope.confirm_email) {
            request_data.confirm_email = scope.confirm_email;
        }
        
        if (scope.name) {
            request_data.name = scope.name;
        }
        
        if (scope.last_name) {
            request_data.last_name = scope.last_name;
        }
        
        if (scope.birthdate) {
            request_data.birthdate = scope.birthdate;
        }
        
        if (scope.male) {
            request_data.male = scope.male;
        }
        
        if (scope.citizenship) {
            request_data.citizenship = scope.citizenship;
        }
        
        if (scope.password) {
            request_data.password = scope.password;
        }

        if (typeof scope.position === "number") {
            request_data.position = scope.position;
            if (scope.action=='delete_pos')
                request_data.position = scope.that.attr('id').replace('delete-position-', '');
        }

        if (typeof scope.promo === "string") {
            request_data.promo = scope.promo;
        }

        if (typeof scope.tourist === "object") {
            request_data.tourist = scope.tourist;
        }

        if (typeof scope.tourists_for_service === "object") {
            request_data.tourists_for_service = scope.tourists_for_service;
        }
        $.ajax({
            url: __ajax_url,
            data: request_data,
            method: "POST",
            dataType: "Json",
            complete: function () {
                __global_data_scope.ajax_flag = false;
                if (typeof scope.complete === "function") {
                    scope.complete();
                }
            },
            beforeSend: function (xhr) {

                if (__global_data_scope.ajax_flag) {
                    xhr.abort();
                    return false;
                }

                __global_data_scope.ajax_flag = false;

                if (typeof scope.beforeSend === "function") {
                    scope.beforeSend();
                }

            },
            statusCode: {
                404: function () {
                    alert(__global_data_scope.messages._404);
                }
            },
            success: function (data) {

                var popup = null;

                data = JSON.parse(data);
                
                if (typeof data.status !== "undefined") {

                    if (data.status === 0) {

                        // крестик вместо preloader
                        scope.that.html("&times");

                        // вывод ошибки
                        alert(__global_data_scope.messages.status_0);
                        return true;

                    }

                    if (data.status === 1) {

                        // удаление обработчика удаленеия элемента корзины
                        scope.that.off("click");
                        if (data.totalbasket) $('.switch.basket #basket-count-item').text(data.totalbasket);
                        else $('.switch.basket #basket-count-item').hide();
                        // уменьшаем количество активных услуг на 1
                        __global_data_scope.active_service_count--;

                        // уменьшаем количество всех услуг на 1
                        __global_data_scope.cart_items_count--;

                        if (__global_data_scope.active_service_count <= 0) {
                            // удаляем форму для бронироваия, так как нет активных услуг
                            $("#" + __global_data_scope.booking_form_id).remove();
                        }

                        if (__global_data_scope.cart_items_count <= 0) {
                            // показываем сообщение о том, что карзина пуста
                            scope.that.closest("." + __global_data_scope.cart_item_class_container).replaceWith(__global_data_scope.empty_cart_message);
                            $('.promo-row').hide();
                            $('.basket-title').hide();
                            $('.addittional-booking-links').hide();
                        } else {
                            // удаляем позицию
                            scope.that.closest("." + __global_data_scope.cart_item_class_container).remove();
                            // отображаем текущее состояние цен
                            $(__global_data_scope.discountAreaSelector).text(data.discount);
                            $(__global_data_scope.costAreaSelector).text(data.cost);
                            $(__global_data_scope.totalAreaSelector).text(data.total);
                            $(__global_data_scope.totalAreaSelectorFooter).text(data.total);
                            $(__global_data_scope.promoAreaSelector).text(__global_data_scope.messages.no_promo);
                            for (var i = 0; i < data.promocodes.length; i++) {
                                $(__global_data_scope.promoAreaSelector).text(data.promocodes.join(', '));
                            }

                            if (data.count_of_people <= __global_data_scope.totalCountOfPeople) {
                                $('#add-tourist-btn').remove();
                            }
                        }
                        
                        if (scope.action=='delete_pos') {
                            for (var i=0; i<$('.delete-position').length; i++) {
                                var item1 = $('.delete-position span')[i];
                                $( ".container" ).find( item1 ).attr( "id", "delete-position-"+i );
                            }
                        }

                        return true;

                    }

                    if (data.status === 4 || data.status === 5 || data.status === 7) {

                        // вывод ошибки
                        __render_error_box(__global_data_scope.messages["status_" + data.status] || data.message);
                        return true;

                    }

                    if (data.status === 6 || data.status === 3) {

                        // вывод сообщения
                        __render_message_box(__global_data_scope.messages["status_" + data.status] || data.message);

                        __global_data_scope.is_authorized = true;
                        __global_data_scope.is_agent = data.is_agent;

                        __destroy(true);

//                        $("#" + __global_data_scope.booking_btn_id).trigger("click");

                        if (data.status === 3 && data.is_agent) {

                            $("#accept").parent().remove();

                        }
                        
                        if (data.status === 3) {
                            // отображаем текущее состояние цен
                            $(__global_data_scope.discountAreaSelector).text(data.discount);
                            $(__global_data_scope.costAreaSelector).text(data.cost);
                            $(__global_data_scope.totalAreaSelector).text(data.total);
                            $(__global_data_scope.totalAreaSelectorFooter).text(data.total);
                        }

                        return true;

                    }

                    if (data.status === 8) {

                        // инициализируем блок регистрации
                        __init_registration_box(__global_data_scope.messages.status_8);

                        // вывод сообщения
                       // __render_message_box(__global_data_scope.messages.status_8);

                        return true;

                    }

                    if (data.status === 9) {

                        // инициализируем блок с авторизацией
                        __init_authorize_box(__global_data_scope.messages.status_9);

                        // вывод сообщения
                        //__render_message_box(__global_data_scope.messages.status_9);

                        return true;
                    }

                    if (data.status === 19) {

                        $("#" + __global_data_scope.booking_form_id).off("submit");
                        $("#" + __global_data_scope.booking_btn_id).trigger("click");
                        return true;
                    }

                    if (data.status === 20) {

                        $(__global_data_scope.form_shading_selector).hide();

                        popup = new bx.PopupWindow('tourists-by-services-popup', window.body, {
                            content: __get_popup_cart_items_content(data, scope, __global_data_scope.messages.citizen_popup.title),
                            lightShadow: true,
                            closeIcon: true,
                            closeByEsc: true,
                            overlay: {
                                backgroundColor: '#000', opacity: '80'
                            },
                            buttons: [
                                new bx.PopupWindowButton({
                                    text: __global_data_scope.messages.citizen_popup.recalculate,
                                    className: "btn-primary btn popup-btn",
                                    events: {
                                        click: function () {

                                            var tourists_for_service = {};

                                            var emptySelectLinkContainer = [];

                                            this.disabled = true;

                                            $('#tourists-by-services-popup').find("select").each(function () {
                                                var $this = $(this);
                                                if (!this.value) {
                                                    emptySelectLinkContainer.push($this);
                                                }
                                                if (typeof tourists_for_service[$this.data("cart-item-position")] === "undefined") {
                                                    tourists_for_service[$this.data("cart-item-position")] = {};
                                                }
                                                if (typeof tourists_for_service[$this.data("cart-item-position")][$this.data("people-type")] === "undefined") {
                                                    tourists_for_service[$this.data("cart-item-position")][$this.data("people-type")] = [];
                                                }
                                                tourists_for_service[$this.data("cart-item-position")][$this.data("people-type")].push(this.value);
                                            });

                                            if (emptySelectLinkContainer.length) {

                                                emptySelectLinkContainer.forEach(function ($select) {
                                                    $select.addClass('red-border');
                                                });
                                                alert(__global_data_scope.messages.citizen_popup.not_choose_tourist);
                                                return;
                                            }

                                            __send_request({
                                                action: "pre-recalculation-basket-with-citizenship",
                                                tourists_for_service: tourists_for_service,
                                                tourist: scope.tourist,
                                                popup_rel: popup
                                            });
                                        }
                                    }
                                })
                            ]
                        });

                        popup.show();

                        return true;


                    }

                    if (data.status === 21) {
                        scope.popup_rel.close();
                        popup = new bx.PopupWindow('tourists-by-services-popup', window.body, {
                            content: __get_popup_cart_items_content(data, scope, __global_data_scope.messages.citizen_popup.recalculated),
                            lightShadow: true,
                            closeIcon: true,
                            closeByEsc: true,
                            overlay: {
                                backgroundColor: '#000', opacity: '80'
                            },
                            buttons: (function (data) {
                                var cant_buy = data.cart_items.filter(function (item) {
                                    if (!item.can_buy) {
                                        return true;
                                    } 
                                    return false;
                                });

                                if (!cant_buy.length) {
                                    return [
                                        new bx.PopupWindowButton({
                                            text: __global_data_scope.messages.citizen_popup.continue_booking,
                                            className: "btn-primary btn popup-btn",
                                            events: {
                                                click: function () {

                                                    __send_request({
                                                        action: "reset-basket"
                                                    });
                                                }
                                            }
                                        })
                                    ];
                                } else {
                                    return [
                                        new bx.PopupWindowButton({
                                            text: __global_data_scope.messages.citizen_popup.close,
                                            className: "btn-primary btn popup-btn",
                                            events: {
                                                click: function () {

                                                    this.popupWindow.close();
                                                }
                                            }
                                        })
                                    ];
                                }

                            })(data)
                        });

                        popup.show();

                        return true;

                    }

                    if (data.status === 22) {
                        $("#" + __global_data_scope.booking_form_id).off("submit");
                        $("#" + __global_data_scope.booking_btn_id).trigger("click");
                        return true;
                    }

                    // обработка результата ввода промокода
                    if ([10, 11, 12, 13, 14, 15, 16, 17, 18].indexOf(data.status) !== -1) {
                        if (data.status === 17) {
                            // отображаем текущее состояние цен
                            $(__global_data_scope.discountAreaSelector).text(data.discount);
                            $(__global_data_scope.costAreaSelector).text(data.cost);
                            $(__global_data_scope.totalAreaSelector).text(data.total);
                            $(__global_data_scope.totalAreaSelectorFooter).text(data.total);
                            $(__global_data_scope.promoAreaSelector).text(__global_data_scope.messages.no_promo);
                            for (var i = 0; i < data.promocodes.length; i++) {
                                $(__global_data_scope.promoAreaSelector).text(data.promocodes.join(', '));
                            }

                        }
                        alert(__global_data_scope.messages["status_" + data.status]);

                        return true;
                    }

                }

                alert(__global_data_scope.messages.default_error_text);

            },
            error: function () {
                alert(__global_data_scope.messages._500);
            }
        });

    }

    /**
     * @returns {undefined}
     */
    function __init_ajax_user_dialog() {

        __global_data_scope.$email_input.on("focusout", function () {

            var email = __global_data_scope.$email_input.val();

            __destroy();

            if (email === "") {

                __render_error_box(__global_data_scope.messages.empty_email);
                return false;

            }

            if (!__email_validation(email)) {
                // отрисовываем блок с ошибками
                __render_error_box(__global_data_scope.messages.status_2);

                return false;

            }

            // запрос на проверку пользователя
            __send_request({
                email: email,
                action: "check_auth"
            });

        });

    }

    /**
     * @returns {undefined}
     */
    function __verification_citizenship_in_services() {

        __send_request({
            action: "verification-citizenship-in-services",
            tourist: (function () {

                var data = {};
                var match = null;
                var match_number = null;
                var match_fname = null;

                $(__global_data_scope.tourists_fields_selector).each(function () {

                    match = this.name.match(/\[[0-9]+\]\[[a-zA-Z\_\-]+\]$/);

                    if (match) {
                        match_number = match[0].match(/[0-9]+/);
                        match_fname = match[0].match(/[a-zA-Z\_\-]+/);
                        if (match_number && match_fname) {
                            
                            if (typeof data[match_number] === "undefined") {
                                data[match_number] = {};
                                data[match_number][match_fname] = "";
                            }
                            data[match_number][match_fname] = this.value;
                        }
                    }


                });
                
                return data;
            })()
        });
    }

    /**
     * 
     * @param {String} html_id
     * @param {Number} position
     * @returns {undefined}
     */
    function __set_del_position(html_id, position) {

        $("#" + html_id).on("click", function () {

            var $this = $(this);

            __send_request({
                action: "delete_pos",
                position: position,
                that: $this,
                beforeSend: function () {
                    $this.html("<img src='" + __global_data_scope.preloader + "' alt='gif-preloader'>");
                }

            });

        });

    }

    function  __init_ajax_delete_cart_position() {

        var i;

        for (i in __global_data_scope.del_pos) {
            __set_del_position(__global_data_scope.del_pos[i].html_id, __global_data_scope.del_pos[i].position);
        }

    }

    $.make_booking_component_dialog = function (options) {

        if (typeof options.sessid === "string" && options.sessid !== "") {
            __global_data_scope.sessid = options.sessid;
        }

        if (typeof options.forgot_password_link === "string" || options.forgot_password_link !== "") {
            __global_data_scope.forgot_password_link = options.forgot_password_link;
        }

        if (typeof options.is_authorized === "boolean") {
            __global_data_scope.is_authorized = options.is_authorized;
        }

        if (typeof options.tourists_fields_selector === "string") {
            __global_data_scope.tourists_fields_selector = options.tourists_fields_selector;
        }

        if (typeof options.citizenships === "object") {

            __global_data_scope.citizenships = options.citizenships;
        }

        if (typeof options.messages === "object") {

            if (typeof options.messages.booking_btn === "string") {
                __global_data_scope.messages.booking_btn = options.messages.booking_btn;
            }

            if (typeof options.messages.confirm_email_error === "string") {
                __global_data_scope.messages.confirm_email_error = options.messages.confirm_email_error;
            }

            if (typeof options.messages.confirm_email_not_equal_email === "string") {
                __global_data_scope.messages.confirm_email_not_equal_email = options.messages.confirm_email_not_equal_email;
            }

            if (typeof options.messages.placeholder_confirm_email === "string") {
                __global_data_scope.messages.placeholder_confirm_email = options.messages.placeholder_confirm_email;
            }

            if (typeof options.messages.placeholder_password === "string") {
                __global_data_scope.messages.placeholder_password = options.messages.placeholder_password;
            }

            if (typeof options.messages.do_registration_button === "string") {
                __global_data_scope.messages.do_registration_button = options.messages.do_registration_button;
            }

            if (typeof options.messages.do_authorize_button === "string") {
                __global_data_scope.messages.do_authorize_button = options.messages.do_authorize_button;
            }

            if (typeof options.messages.enter_the_password === "string") {
                __global_data_scope.messages.enter_the_password = options.messages.enter_the_password;
            }

            if (typeof options.messages._404 === "string") {
                __global_data_scope.messages._404 = options.messages._404;
            }

            if (typeof options.messages._500 === "string") {
                __global_data_scope.messages._500 = options.messages._500;
            }

            if (typeof options.messages.default_error_text === "string") {
                __global_data_scope.messages.default_error_text = options.messages.default_error_text;
            }

            if (typeof options.messages.status_0 === "string") {
                __global_data_scope.messages.status_0 = options.messages.status_0;
            }

            if (typeof options.messages.status_1 === "string") {
                __global_data_scope.messages.status_1 = options.messages.status_1;
            }

            if (typeof options.messages.status_2 === "string") {
                __global_data_scope.messages.status_2 = options.messages.status_2;
            }

            if (typeof options.messages.status_3 === "string") {
                __global_data_scope.messages.status_3 = options.messages.status_3;
            }

            if (typeof options.messages.status_4 === "string") {
                __global_data_scope.messages.status_4 = options.messages.status_4;
            }

            if (typeof options.messages.status_5 === "string") {
                __global_data_scope.messages.status_5 = options.messages.status_5;
            }

            if (typeof options.messages.status_6 === "string") {
                __global_data_scope.messages.status_6 = options.messages.status_6;
            }

            if (typeof options.messages.status_7 === "string") {
                __global_data_scope.messages.status_7 = options.messages.status_7;
            }

            if (typeof options.messages.status_8 === "string") {
                __global_data_scope.messages.status_8 = options.messages.status_8;
            }

            if (typeof options.messages.status_9 === "string") {
                __global_data_scope.messages.status_9 = options.messages.status_9;
            }

            if (typeof options.messages.empty_email === "string") {
                __global_data_scope.messages.empty_email = options.messages.empty_email;
            }

            if (typeof options.preloader === "string" && options.preloader !== "") {
                __global_data_scope.preloader = options.preloader;
            }

        }

        if (typeof options.booking_btn_id === "string") {
            __global_data_scope.booking_btn_id = options.booking_btn_id;
        }

        if (typeof options.mount_point_id === "string") {
            __global_data_scope.$mount_point = $("#" + options.mount_point_id);
        }

        if (!__global_data_scope.is_authorized && typeof options.email_input_id === "string") {
            __global_data_scope.$email_input = $("#" + options.email_input_id);
            if (__global_data_scope.$email_input.length) {
                __init_ajax_user_dialog();
            }
        }

        if (typeof options.empty_cart_message === "string") {
            __global_data_scope.empty_cart_message = options.empty_cart_message;
        }

        if (typeof options.cart_items_count === "number") {
            __global_data_scope.cart_items_count = options.cart_items_count;
        }

        if (typeof options.active_service_count === "number") {
            __global_data_scope.active_service_count = options.active_service_count;
        }

        if (typeof options.cart_item_class_container === "string") {
            __global_data_scope.cart_item_class_container = options.cart_item_class_container;
        }

        if (typeof options.booking_form_id === "string") {

            __global_data_scope.booking_form_id = options.booking_form_id;
            $("#" + __global_data_scope.booking_form_id).on("submit", function () {

                if (typeof options.sendBeforeForm === "function") {
                    if (options.sendBeforeForm(__global_data_scope) === true) {
                        __verification_citizenship_in_services();
                    }
                } else {
                    __verification_citizenship_in_services();
                }

                return false;
            });
        }

        if (typeof options.form_shading_selector === "string") {
            __global_data_scope.form_shading_selector = options.form_shading_selector;
        }

        if (typeof options.del_pos === "object") {

            __global_data_scope.del_pos = options.del_pos;

            __init_ajax_delete_cart_position();

        }

        if (typeof options.additional_tourists === 'object') {

            var opt = options.additional_tourists,
                    count = opt.count,
                    index = opt.index;

            __global_data_scope.totalCountOfPeople = opt.count;
            
            
            $(document).on('click', '.delete-blocktourist', function (e) {
               if ($(this).parent().parent().find('.additional-tourist-area').length) $(this).parent().parent().remove();
               else {
                    $(this).parent().parent().append('<span class="additional-tourist-area"></span>');
                    $(this).parent().remove();
               }
               count++;
               index--;
               if (count > 0)  $('#add-tourist-btn').show();
               e.preventDefault();
            });

            $(document).on('click', '#add-tourist-btn', function (e) {

                var area = null, lastRow = null, template = opt.template;

                if (count > 0) {

                    lastRow = $('.tourist-row:last');

                    area = lastRow.find('.additional-tourist-area');

                    template = template.replace('#number#', index + 1);

                    while (template.indexOf('#index#') !== -1) {

                        template = template.replace('#index#', index);
                    }


                    if (area.length) {

                        area.replaceWith(template);
                        
                    } else if (lastRow.length) {

                        lastRow.after('<div class="row form bg-none tourist-row">' + template + '<span class="additional-tourist-area"></span></div>');
                    }
                    index++;
                    count--;
                    if (count === 0) {

                        $('#add-tourist-btn').hide();
                    }

                    $(".birthdate-field").mask("99.99.9999");
                    $(".selectpicker").selectpicker();
					
                }

                e.preventDefault();
            });
        }

        if (typeof options.promoSelectorBtn === 'string') {

            if (typeof options.messages.status_10 === "string") {
                __global_data_scope.messages.status_10 = options.messages.status_10;
            }

            if (typeof options.messages.status_11 === "string") {
                __global_data_scope.messages.status_11 = options.messages.status_11;
            }

            if (typeof options.messages.status_12 === "string") {
                __global_data_scope.messages.status_12 = options.messages.status_12;
            }

            if (typeof options.messages.status_13 === "string") {
                __global_data_scope.messages.status_13 = options.messages.status_13;
            }

            if (typeof options.messages.status_14 === "string") {
                __global_data_scope.messages.status_14 = options.messages.status_14;
            }

            if (typeof options.messages.status_15 === "string") {
                __global_data_scope.messages.status_15 = options.messages.status_15;
            }

            if (typeof options.messages.status_16 === "string") {
                __global_data_scope.messages.status_16 = options.messages.status_16;
            }

            if (typeof options.messages.status_17 === "string") {
                __global_data_scope.messages.status_17 = options.messages.status_17;
            }

            if (typeof options.messages.status_18 === "string") {
                __global_data_scope.messages.status_18 = options.messages.status_18;
            }

            if (typeof options.messages.no_promo === "string") {
                __global_data_scope.messages.no_promo = options.messages.no_promo;
            }
            
            if (typeof options.messages.citizen_popup === "object") {
                __global_data_scope.messages.citizen_popup = options.messages.citizen_popup;
            }

            if (typeof options.totalAreaSelector === 'string') {
                __global_data_scope.totalAreaSelector = options.totalAreaSelector;
            }
            
            if (typeof options.totalAreaSelectorFooter === 'string') {
                __global_data_scope.totalAreaSelectorFooter = options.totalAreaSelectorFooter;
            }
            
            if (typeof options.costAreaSelector === 'string') {
                __global_data_scope.costAreaSelector = options.costAreaSelector;
            }

            if (typeof options.discountAreaSelector === 'string') {
                __global_data_scope.discountAreaSelector = options.discountAreaSelector;
            }

            if (typeof options.promoAreaSelector === 'string') {
                __global_data_scope.promoAreaSelector = options.promoAreaSelector;
            }

            $(document).on('click', options.promoSelectorBtn, function (e) {

                var $input = $($(this).data('input-link'));
                var promo = $input.val().toString();

                if (promo !== '') {
                    __send_request({
                        action: 'apply_promo',
                        promo: promo,
                        complete: function () {
                            $input.val('');
                        }
                    });
                } else {
                    alert(__global_data_scope.messages.status_10);
                }

                e.preventDefault();
            });

        }


    };
})(jQuery, JSON, BX);