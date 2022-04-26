/* 
 * Crossale component
 * 
 * @author dimabresky
 */

$(document).ready(function () {

    var config = window.__tsconfig_placements_crossale;

    var utils = {

        /**
         * @param {$} context
         */
        initOwlCarousel: function (context) {

            context.owlCarousel({
                items: 4,
                loop: true,
                margin:'1rem',
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
        
        getPlacementsOffersContent: function (placements_offers) {
            var template = $("#crossale-placements-offer-template").html();
            var content = '';
            for (var k in placements_offers) {
                if (placements_offers.hasOwnProperty(k)) {
                    content += template.replace("{{src}}", (function () {
                        var src = config.no_photo_src;
                        if (placements_offers[k].service.img_src !== '') {
                            src = placements_offers[k].service.img_src;
                        }
                        return src;
                    })())
                            
                            .replace("{{placement_name}}", placements_offers[k].placement.name)
                            .replace("{{service_name}}", placements_offers[k].service.name)
                            .replace("{{rate_name}}", placements_offers[k].rate.name)
                            .replace("{{price_formatted}}", placements_offers[k].price_formatted)
                            .replace("{{add2cart}}", placements_offers[k].add2cart);
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

            var placements_content = '';         
            if (typeof resp.PLACEMENTS_OFFERS !== "undefined" && $.isArray(resp.PLACEMENTS_OFFERS)) {

                placements_content = $('#crossale-placements-template')
                        .html()
                        
                        .replace('{{offers_container}}',
                                $("#crossale-placements-offers-template").html()
                                .replace("{{offers}}", utils.getPlacementsOffersContent(resp.PLACEMENTS_OFFERS))
                                );

                $('#placements-crossale-container').html(placements_content);

                utils.initOwlCarousel($('#interesting-slide-placements'));
                utils.initAdd2cartBtn($('#interesting-slide-placements').find("a.add-to-cart"));
                
                document.querySelector('#placements-crossale-container').classList.toggle('active');

                document.querySelector('.arrow-drop-down-placements').onclick = (event) =>{
                    event.currentTarget.classList.toggle('active');
                    
                    document.querySelector('.sales-cn-placements').classList.toggle('ts-collapse');

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
                
                $(".link-see-all-placements a").attr("href", resp.DETAIL_LINK);
            } else {
                $('#placements-crossale-container').html('');
            }
        }
    });
});