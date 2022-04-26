function Statistic (counter_id, goal_id, path, url) {

    var _this = this;

    function goalCheck(counter_id, goal_id, callback) {

        window.addEventListener("load", function () {
            window["yaCounter" + String(counter_id)].reachGoal(String(goal_id));
            if(typeof callback === "function"){
                callback();
            }
        });

    }

    this.booking = function (counter_id, goal_id) {

        if (typeof counter_id !== "undefined" || typeof goal_id !== "undefined") {
            goalCheck(counter_id, goal_id);
        }

    };

    this.cookieSet = function(name, value) {

        var d = new Date();
        d.setTime(d.getTime() + 3600 * 1000);
        var expires = d;
        expires = expires.toUTCString();

        var path = "/";

        var val = _this.cookieGet("name");
        if(typeof val !== "undefined"){
            var date = new Date(0);
            date = date.toUTCString();
            document.cookie = name + "=; path=/; expires=" + date;
        }

        document.cookie = name + "=" + value + "; expires=" + expires + "; path=" + path;

    };

    //TODO: переписать на простую!
    /*this.cookieSet = function(name, value, options) {

        options = options || {};

        //var expires = options.expires;
        var expires = "3600";

        if (typeof expires == "number" && expires) {
            var d = new Date();
            d.setTime(d.getTime() + expires * 1000);
            expires = options.expires = d;
        }
        if (expires && expires.toUTCString) {
            options.expires = expires.toUTCString();
        }

        console.log();

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

    };*/

    this.cookieGet = function(name) {

        var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") + "=([^;]*)"
        ));

        return matches ? decodeURIComponent(matches[1]) : undefined;
    };

    function addSecond(name) {

        var second = Number(_this.cookieGet(name));
        _this.cookieSet(name, second + 1);

    }

    function pagesCheck(url, name_page) {

        var str = "page_" + url;
        var url_ = _this.cookieGet(str);

        if (typeof url_ === "undefined") {
            _this.cookieSet(str, 1);

            var name_page_ =  Number(_this.cookieGet(name_page));

            _this.cookieSet(name_page, name_page_ + 1);

        }

    }

    function initGoalSecond(name, name_second) {

        var set_goal_second = _this.cookieGet(name);

        if(typeof set_goal_second === "undefined" || set_goal_second === "undefined") {
            _this.cookieSet(name, 0);
            _this.cookieSet(name_second, 0);
        }

    }

    function initGoalPage(name, name_page) {

        var set_goal_str = _this.cookieGet(name);

        if(typeof set_goal_str === "undefined" || set_goal_str === "undefined") {
            _this.cookieSet(name, 0);
            _this.cookieSet(name_page, 0);
        }

    }

    function initGoalServices(name) {

        var set_goal_services = _this.cookieGet(name);

        if(typeof set_goal_services === "undefined" || set_goal_services === "undefined") {
            _this.cookieSet(name, 0);
        }

    }

    this.cookieCheckForPage = function (name_goal_str, name_cnt_pages, url) {

        initGoalPage(name_goal_str, name_cnt_pages);

        set_goal_str = _this.cookieGet(name_goal_str);

        if(set_goal_str != 0) {
            return;
        }

        if (typeof url !== "undefined" && url != "") {

            pagesCheck(url, name_cnt_pages);

        }

        cnt_pages = Number(_this.cookieGet(name_cnt_pages));

        if (cnt_pages == 3) {

            goalCheck (counter_id, goal_id);
            _this.cookieSet(name_goal_str, 1);

        }

    };

    this.cookieCheckForSecond = function (name_goal_second, name_seconds) {

        initGoalSecond(name_goal_second,name_seconds);

        set_goal_second = _this.cookieGet(name_goal_second);

        if(set_goal_second != 0) {
            return;
        }

        var intervalID = setInterval(function () {

            var second_ = Number(_this.cookieGet(name_seconds));
            addSecond(name_seconds);

            if(second_ < 60){
                return;
            }

            //goalCheck (counter_id, goal_id);
            goalCheck (counter_id, 'was_online_cnc');
            _this.cookieSet(name_goal_second,1);
            clearInterval(intervalID);

        }, 1000);


    };

    this.cookieCheckForRegister = function (callback) {

        if (typeof counter_id !== "undefined" || typeof goal_id !== "undefined") {

            /*var path_ = path || "/";

            var cookie_path = _this.cookieGet("path");
            if(typeof cookie_path === "undefined") {
                _this.cookieSet("path",path_);
            }*/
            goalCheck (counter_id, goal_id, callback);

        }

    };

    this.cookieCheckForServices = function (name_goal_services) {

        initGoalServices(name_goal_services);

        set_goal_services = _this.cookieGet(name_goal_services);

        if(set_goal_services != 0) {
            return;
        }

        if (typeof counter_id !== "undefined" || typeof goal_id !== "undefined") {

            /*goalCheck (counter_id, goal_id);
            _this.cookieSet(name_goal_services, 1);*/

        }

    };

    this.cookieCheck = function () {

        if (typeof counter_id !== "undefined" || typeof goal_id !== "undefined") {

             var url_ = url || "";
             /*var path_ = path || "/";

             var cookie_path = _this.cookieGet("path");
             if(typeof cookie_path === "undefined") {
                 _this.cookieSet("path",path_);
             }*/

             //_this.cookieCheckForPage("set_goal_str", "cnt_pages", url_);

             _this.cookieCheckForSecond("set_goal_second","cookie_second");

                 /*if (cnt_pages < 3) {

                     if(typeof url_ !== "undefined" && url_ !== "") {

                         pagesCheck(url_,"cnt_pages");

                     }

                 }

                 if (cnt_pages == 3) {
                     //goalCheck (counter_id, goal_id);
                     _this.cookieSet("set_goal_str", 1);
                 }*/



             /*var set_goal_str = _this.cookieGet("set_goal_str");
             if(typeof set_goal_str !== "undefined" && set_goal_str == 0) {

             if(typeof url_ !== "undefined" && url_ !== "") {
             var cnt_pages = _this.cookieGet("cnt_pages");
             if (cnt_pages < 3) {
                pagesCheck(url_);
                 _this.cookieSet("cnt_pages", cnt_pages + 1);
             }
             if (_this.cookieGet("cnt_pages") >= 3) {
             //goalCheck (counter_id, goal_id);
                 _this.cookieSet("set_goal_str", 1);
             }
             }
             } else {
                 _this.cookieSet("set_goal_str",0);
             }*/

            /*initGoalSecond("set_goal_second","cookie_second");

            set_goal_second = _this.cookieGet("set_goal_second");

            if(set_goal_second == 0){

                var intervalID = setInterval(function () {

                    var second_ = Number(_this.cookieGet("cookie_second"));
                    addSecond("cookie_second");
                    console.log(second_);

                    if(second_ < 60){
                        return;
                    }

                    //goalCheck (counter_id, goal_id);
                    _this.cookieSet("set_goal_second",1);
                    clearInterval(intervalID);

                }, 1000);

            }*/

        }
        return false;

    }

}