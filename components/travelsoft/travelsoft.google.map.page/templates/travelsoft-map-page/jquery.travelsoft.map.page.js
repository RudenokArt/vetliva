
"use strict";

(function ($, window, sessid) {
    
    $(window.document).ready(function () {
        
        var 

            /** 
             * @type String
             */
            mapId = "hotel-maps",

            /**
             * google map
             * @type Window.google.maps
             */
            map = null,

            /**
             * @type MarkerClusterer
             */
            markerCluster = null,

            /**
             * @type object
             */
            mapDefCenter = {lat: 53.55, lng: 27.33},

            /**
             * @type Number
             */
            defZoom = 6,

            /**
             * markers object container
             * @type Array
             */
            markers = [],

            /**
             * @type Window.document
             */
            document = window.document,

            /**
             * @type {object}
             */
            cache = {},

            /**
             * @type {object}
             */
            preloader = (
                /**
                 * @param {jQuery} $
                 * @returns {object}
                 */    
                function ($) {

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
            
            /**
             * @type Array
             */
            arSelectors = ["#locations", "#points"],
            
            /**
             * @type Array
             */
            arPlaceholders = [$(arSelectors[0]).data("placeholder"),  $(arSelectors[1]).data("placeholder")], 
            
            /**
             * @type Boolean
             */
            arFirstTime = [true,  true],
            
            /**
             * @type Object
             */
            lang_phrases = $("#navigation-form").data("lang-phrases"), 
            
            /**
             * @type Number
             */
            k,
                        
            /**
             * @type jQuery
             */
            $currSelect;

             /**
             * click handler on form
             */
            function navClickHandler() {

                var

                    form = $('#navigation-form'),

                    urlStrParameters = form.serialize(),

                    cacheId = hashCode(urlStrParameters) || null, url = null;

                preloader.show();

                clearMap();

                if (cacheId && typeof cache[cacheId] !== "undefined") {

                    renderItems(cache[cacheId]['items']);

                    $("#searching__cnt__elements").text(cache[cacheId].cnt);

                    window.history.pushState(null, null, cache[cacheId]["url"]);

                    preloader.hide();

                } else {

                    url = urlStrParameters ? form.attr("action") + "?" + urlStrParameters : form.attr("action");

                    $.post(url, {__compid: "__travelsoft_df96dd9a12aed7a764ab1d004e9260fd", sessid: sessid}, function (data) {

                        if (data.error) {
                            alert("Some error :(");
                            return false;
                        }

                        renderItems(data.items);

                        data.cntElements = Number(data.cntElements);

                        $("#searching__cnt__elements").text(data.cntElements);

                        if (cacheId) {
                            cache[cacheId] = {
                                items: data.items,
                                cnt: data.cntElements,
                                url: url
                            };

                        }

                        window.history.pushState(null, null, url);

                        preloader.hide();

                    }, "json");

                }


            }

            /**
             * add marker on map and return marker like object
             * @param {object} markerInfo
             * @returns {unresolved}
             */
            function __addMarker(markerInfo) {

                var 
                        infoWindow = new window.google.maps.InfoWindow({maxWidth:600}),

                        position = new window.google.maps.LatLng(markerInfo.lat, markerInfo.lng),

                        infoWindowContent, marker, mleft;

                        infoWindowContent = "<div class='maps-item'>";

                        mleft = "style='margin-left: 0px'";

                        if (markerInfo.img) {
                             infoWindowContent += "<a href='#' class='maps-image'>";
                             infoWindowContent += "<img src='"+markerInfo.img+"' alt='"+markerInfo.title+"'>";
                             infoWindowContent += "</a>";

                             mleft = "";
                        }

                        infoWindowContent += "<div class='maps-text'"+ mleft +">";

                        infoWindowContent += "<h2><a href='"+markerInfo.page+"'>"+markerInfo.title+"</a></h2>";

                        if (markerInfo.address) {
                            infoWindowContent += "<address>"+markerInfo.address+"</address>";
                        }

                        infoWindowContent += "</div></div>";


                        marker = new window.google.maps.Marker({
                             position: position,
                             map: map,
                             icon: markerInfo.icon,
                             title: markerInfo.title
                         });

                //Allow each marker to have an info window    
                window.google.maps.event.addListener(marker, 'click', (function(marker) {
                    return function() {
                        infoWindow.setContent(infoWindowContent); 
                        infoWindow.open(map, marker);
                    };

                })(marker));

                return marker;

            }

            /**
             * clear map
             */
            function clearMap () {

               var key, cnt = markers.length;

               for (key = 0; key < cnt; key++) {
                   markers[key].setMap(null);
               }

                markers = [];

                markerCluster.clearMarkers();


            }

            /**
             * @param {string} str
             * @returns {string}
             */
            function hashCode (str) {

                var 
                    hash = 0,
                    len = str.length, i, char;

                if (!len) return hash;

                for (i = 0; i < len; i++) {

                        char = str.charCodeAt(i);

                        hash = ((hash<<5)-hash)+char;

                        hash = hash & hash; // Convert to 32bit integer
                }

                return hash;

            }

            /**
             * @param {object} jsonItems
             */
            function renderItems (jsonItems) {

                var key, bounds, markerInfo;

                if (typeof jsonItems === "object" && jsonItems) {

                    bounds = new window.google.maps.LatLngBounds();

                    for ( key in  jsonItems ) {

                        markerInfo = jsonItems[key];

                        markers.push( __addMarker( {

                            lat: markerInfo.lat,
                            lng: markerInfo.lng,
                            img: markerInfo.img,
                            icon: markerInfo.icon,
                            title: markerInfo.title,
                            page: markerInfo.page,
                            address: markerInfo.address,
                            text: markerInfo.text

                        } ) );

                        bounds.extend(new window.google.maps.LatLng(markerInfo.lat, markerInfo.lng));

                    }

                    // Automatically center the map fitting all markers on the screen
                    map.fitBounds(bounds);

                    // clustering markers
                    markerCluster.addMarkers( markers );
                }

            }
            
            /**
             * @param {jQuery} $ul
             * @returns {undefined}
             */
            function firstTimeMsInit($ul) {

                $ul.find("li.group").each(function () {
  
                    $(this).find(".optgroup").each(function () {
                        
                        var $this = $(this), dg = $this.data("group"), inputList, __shTpl = "&nbsp;&nbsp;&nbsp;<span class='__sh #CLASS#'>#__SH_TITLE#</span>";
                        
                        if (dg) {
                            
                            inputList = $ul.find("input[data-group='"+dg+"']");
                            
                            if (inputList.length) {
                                
                                if ($ul.find("input[data-group='"+dg+"']:checked").length) {
                                    
                                   $this.append(__shTpl.replace("#__SH_TITLE#", lang_phrases.__hide).replace("#CLASS#", "active"));
                                   
                                } else {
                                    
                                    $this.append(__shTpl.replace("#__SH_TITLE#", lang_phrases.__show).replace("#CLASS#", ""));
                                    __sh(inputList.closest("li"));

                                }
                                
                            }
                            
                        }
                        
                    });
                    
                });
            } 
            
            /**
             * Обработчик действия свернуть/развернуть
             * @param {jQuery} jqLiList
             * @param {boolean} flag
             * @param {jQuery} $this
             * @returns {undefined}
             */
            function __sh(jqLiList, flag, $this) {
                
                if (flag) {
                        jqLiList.css({height: "auto", "max-width": "none"});
                        if ($this) {
                            $this.addClass("active").text(lang_phrases.__hide);
                        }
                } else {
                        jqLiList.css({height: 0, "max-width": 0});
                        if ($this) {
                            $this.removeClass("active").text(lang_phrases.__show);
                        }
                }
                
            }
            
           // init map
            map = new window.google.maps.Map(document.getElementById(mapId), {

                    zoom: defZoom,
                    scrollwheel: false,
                    mapTypeId: window.google.maps.MapTypeId.ROADMAP,
                    center: mapDefCenter

            });
            
            // init markers cluster
            markerCluster = new MarkerClusterer(map, [], {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});

            renderItems( $("#" + mapId).data("json-items") );

            for (k = 0; k < arSelectors.length; k++) {
                $currSelect = $(arSelectors[k]);
                $currSelect.multipleSelect({
                    multiple: true,
                    placeholder: arPlaceholders[k],
                    filter: true,
                    multipleWidth: 150,
                    width: "46%",
                    selectAllText: lang_phrases["selectAllText"],
                    allSelected: lang_phrases["allSelected"],
                    countSelected: lang_phrases["countSelected"],
                    noMatchesFound: lang_phrases["noMatchesFound"],
                    onClick: navClickHandler,
                    onOpen: (function (k, $select) {
                        return function () {
                            if (arFirstTime[k]) {
                                arFirstTime[k] = false;  
                                firstTimeMsInit($select.next().find(".ms-drop").find("ul"));
                            }
                        };
                    })(k, $currSelect),
                    onOptgroupClick: function (view) {
                        
                        var chList = $(view.children), liList = $(view.children).closest("li"), dg = chList.first().data("group");
                
                        __sh(liList, view.checked, liList.first().closest("ul").find("label[data-group='"+dg+"']").find(".__sh"));
         
                        navClickHandler();

                    },
                    onCheckAll: function () {
                        navClickHandler();
                        
                    },
                    onUncheckAll: function () {
                        navClickHandler();
                    }
                });
            }
            
            // инициализация клика по свернуть/развернуть в $.multipleSelect
            $(document).on("click", ".__sh", function (e) {
                
                var $this = $(this), dg = $this.closest(".optgroup").data("group"), inputList;

                if (dg) {
                    inputList = $this.closest("ul").find("input[data-group='"+dg+"']");
                    if (inputList.length) {
                        __sh(inputList.closest("li"), !$this.hasClass("active"), $this);
                    }
                }

                e.preventDefault();
                e.stopPropagation();
                
            });

            // init state event handler
            window.addEventListener('popstate', function() {
                window.location.reload();
            });
            
       });
        
        
    
})(jQuery, window, BX.bitrix_sessid);