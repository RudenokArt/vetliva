/* 
 * Crossale component
 * 
 * @author dimabresky
 */

$(document).ready(function () {

    var config = window.__tsconfig_transfers_crossale;

    var utils = {

        /**
         * @param {$} context
         */
        initOwlCarousel: function (context) {

            context.owlCarousel({
                items: 4,
                loop: true,
                margin: 10,
                nav: true,
                navigation: true,
                dot: true,
                center: true,
                navigationText: ['‹', '›'],
                responsive: {
                    0: {
                        nav: true
                    },
                    600: {

                        nav: true,
                    },
                    1000: {
                        nav: true,
                        loop: false
                    }
                }
            });
        },

        /**
         * @param {$} context
         * @returns {undefined}
         */
        destroyOwlCarousel: function (context) {
            context.data("owlCarousel").destroy();
        },

        /**
         * @param {$} context
         * @returns {undefined}
         */
        initAdd2cartBtn: function (context) {
            context.each(function () {
                var $this = $(this);
                $this.on("click", function (e) {

                    e.preventDefault();
                    $.ajax({
                        url: "/local/components/travelsoft/travelsoft.service.price.result/ajax.php",
                        dataType: "Json",
                        data: {add2cart: $this.data("add2cart"), sessid: config.sessid},
                        success: function (data) {

                            if (typeof data.message_ok === "string" && data.message_ok === "ok") {
                                document.location.reload();
                            }

                        }
                    });
                });
            });
        },
        initTransfersForm: function () {
            var form = $("#crossale-transfers-form");
            form.find('.cars-select').on('change', function () {
                form.trigger('submit');
            });
            form.find('input[name="crossale_transfers[roundtrip]"]').on('change', function () {
                form.trigger('submit');
            });
            form.on('submit', function (e) {

                $.ajax({
                    url: config.ajax_url,
                    data: form.serialize(),
                    beforeSend: function () {
                        $(".link-see-all a").attr("href", config.detail_links[form.find('.cars-select').val()]);
                    },
                    success: function (resp) {

                        utils.destroyOwlCarousel($('#interesting-slide-transfers'));
                        $('#interesting-slide-transfers').html(utils.getTransfersOffersContent(resp.TRANSFERS_OFFERS));
                        utils.initAdd2cartBtn($('#interesting-slide-transfers').find("a.add-to-cart"));
                        
                        setTimeout(function () {
                            utils.initOwlCarousel($('#interesting-slide-transfers'));
                        }, 500);
                    }
                });

                e.preventDefault();
            });
        },
        getTransfersOffersContent: function (transfers_offers) {
            var template = $("#crossale-transfers-offer-template").html();
            var content = '';
            for (var k in transfers_offers) {
                if (transfers_offers.hasOwnProperty(k)) {
                    content += template.replace("{{src}}", (function () {
                        var src = config.no_photo_src;
                        if (transfers_offers[k].PICTURE !== '') {
                            src = transfers_offers[k].PICTURE;
                        }
                        return src;
                    })()).replace("{{class_name}}", transfers_offers[k].CLASS_NAME)
                            .replace("{{route}}", transfers_offers[k].ROUTE)
                            .replace("{{auto_name}}", transfers_offers[k]["UF_AUTO" + config.postfix_property])
                            .replace("{{price_formatted}}", transfers_offers[k].PRICE_FORMATTED)
                            .replace("{{add2cart}}", transfers_offers[k].ADD2CART);
                }
            }
            return content;
        }
    };
        
    // run app
    $.ajax({
        url: config.ajax_url,
        data: {sessid: config.sessid, type: config.type},
        dataType: "Json",
        success: function (resp) {

            var transfers_content = '';
            
            var first = true;
            
            var dep_id = null;
            
            if (typeof resp.TRANSFERS_DEPARTURE_POINTS !== "undefined" && !$.isArray(resp.TRANSFERS_DEPARTURE_POINTS)) {

                transfers_content = $('#crossale-transfers-template')
                        .html()
                        .replace('{{departure_points_options}}', (function () {

                            var options = '';
                            for (var k in resp.TRANSFERS_DEPARTURE_POINTS) {
                                if (resp.TRANSFERS_DEPARTURE_POINTS.hasOwnProperty(k)) {
                                    options += `<option ${resp.TRANSFERS_DEPARTURE_POINTS[k].SELECTED ? 'selected=""' : ''} value="${k}">${config.CROSSALE_TRANSFERS_SELECT_POINT_TITLE.replace("#NAME#", resp.TRANSFERS_DEPARTURE_POINTS[k].NAME).replace("#PRICE#", resp.TRANSFERS_DEPARTURE_POINTS[k].PRICE)}</option>`;
                                    if (first) {
                                        first = false;
                                        dep_id = k;
                                    }
                                }
                            }
                            return options;
                        })())
                        .replace('{{offers_container}}',
                                $("#crossale-transfers-offers-template").html()
                                .replace("{{offers}}", utils.getTransfersOffersContent(resp.TRANSFERS_OFFERS))
                                );

                $('#transfers-crossale-container').html(transfers_content);

                utils.initOwlCarousel($('#interesting-slide-transfers'));
                utils.initAdd2cartBtn($('#interesting-slide-transfers').find("a.add-to-cart"));
                utils.initTransfersForm();
                document.querySelector('#transfers-crossale-container').classList.toggle('active');

                document.querySelector('.arrow-drop-down').onclick = (event) =>{
                    event.currentTarget.classList.toggle('active');
                    document.querySelector('.container-select').classList.toggle('ts-collapse');
                    document.querySelector('.sales-cn').classList.toggle('ts-collapse');

                };
                if(document.querySelectorAll('.descblock.class-auto.ts-text-size')){
                    const text = document.querySelectorAll('.descblock.class-auto.ts-text-size');

                    [].slice.call(text).forEach(item=>{
                        if(item.innerText.length>15){
                            let tooltip = document.createElement('div');
                            tooltip.classList.add('class-auto-tooltip');
                            tooltip.innerHTML = item.innerText;
                            item.innerText = `${item.innerText.substring(0, 15)}...`;
                            item.appendChild(tooltip);

                        }
                    })
                }
                config.detail_links = resp.DETAIL_LINK_FOR_DEPARTURE_POINTS;
                
                $(".link-see-all a").attr("href", config.detail_links[dep_id]);
            } else {
                $('#transfers-crossale-container').html('');
            }
        }
    });
});