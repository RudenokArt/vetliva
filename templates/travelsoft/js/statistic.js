(function($) {
    "use strict";

    var Statistic = function () {

    };

    //$(document).ready(function () {

        console.log("1234");

        function goalCheck(counter_id, goal_id) {

            yaCounter + counter_id.reachGoal(goal_id);

        }

        function cookieSet(name, value, options) {

            options = options || {};

            var expires = options.expires;

            if (typeof expires == "number" && expires) {
                var d = new Date();
                d.setTime(d.getTime() + expires * 1000);
                expires = options.expires = d;
            }
            if (expires && expires.toUTCString) {
                options.expires = expires.toUTCString();
            }

            value = encodeURIComponent(value);

            var updatedCookie = name + "=" + value;

            for (var propName in options) {
                updatedCookie += "; " + propName;
                var propValue = options[propName];
                if (propValue !== true) {
                    updatedCookie += "=" + propValue;
                }
            }

            document.cookie = updatedCookie;

        }

        function cookieGet(name) {

            var matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") + "=([^;]*)"
            ));

            return matches ? decodeURIComponent(matches[1]) : undefined;
        }

        function addSecond(name) {

            var second = cookieGet(name);
            cookieSet(name, second + 1);

        }

        function pagesCheck(url) {

            var url_ = cookieGet("page_" + url);
            if (typeof url_ === "undefined") {
                cookieSet("page_" + url, 1);
            }

        }

        Statistic.prototype.cookieCheckForRegister = function (counter_id, goal_id, path) {

            if (typeof counter_id !== "undefined" || typeof goal_id !== "undefined") {

                var path_ = path || "/";

                var cookie_path = cookieGet("path");
                if(typeof cookie_path === "undefined") {
                    cookieSet("path",path_);
                }
                //goalCheck (counter_id, goal_id);

                return true;
            }
            return false;
        };


        Statistic.prototype.cookieCheck = function (counter_id, goal_id, path, url) {

            console.log(counter_id, goal_id, path, url);
            /*if (typeof counter_id !== "undefined" || typeof goal_id !== "undefined") {

                var url_ = url || null;
                 var path_ = path || "/";

                 var cookie_path = cookieGet("path");
                 if(typeof cookie_path === "undefined") {
                    cookieSet("path",path_);
                 }

                 var set_goal_str = cookieGet("set_goal_str");
                 if(typeof set_goal_str !== "undefined" && set_goal_str == 0) {

                     if(typeof url_ !== "undefined" && url_ !== null) {
                         var cnt_pages = cookieGet("cnt_pages");
                         if (cnt_pages < 3) {
                             pagesCheck(url_);
                             cookieSet("cnt_pages", cnt_pages + 1);
                         }
                         if (cookieGet("cnt_pages") >= 3) {
                         //goalCheck (counter_id, goal_id);
                            cookieSet("set_goal_str", 1);
                        }
                    }
                 } else {
                    cookieSet("set_goal_str",0);
                 }

                 var set_goal_second = cookieGet("set_goal_second");
                 if(typeof set_goal_second !== "undefined" && set_goal_second == 0) {

                     var intervalID = setInterval(function () {
                         var second_ = cookieGet("cookie_second");
                         if(second_ == 60){
                             //goalCheck (counter_id, goal_id);
                             cookieSet("set_goal_second",1);
                             clearInterval(intervalID);
                         } else {
                            addSecond("cookie_second");
                         }
                     }, 1000);

                 } else {
                     cookieSet("set_goal_second",0);
                 }

                return true;
            }
            return false;*/

        }

    //});

})(jQuery);