/* 
 * Скрипт для получения и отсылки ajax сообщений по бронированию
 * @author dimabresky
 * @copyright (c) 2017, travelsoft
 */

!function (window, BX, JSON) {
    
    "use strict";
    
    var dateFrom = null;
    
    if (typeof window.Travelsoft !== "object") {
        window.Travelsoft = {}
    }
    if (typeof window.Travelsoft.BookingMessanger !== "undefined") {
        return
    }
    
    // объект отправки сообщений
    window.Travelsoft.BookingMessanger = function (options) {
        
        var defOptions = {};
        defOptions.freqRequest = options.freqRequest || 30
        defOptions.ajaxUrl = options.ajaxUrl
        defOptions.onAjaxSuccess = options.onAjaxSuccess
        defOptions.onAjaxFailure = options.onAjaxFailure
        defOptions.onAjaxBefore = options.onAjaxBefore
        defOptions.dateFrom = options.dateFrom
        
        function sendRequest (parameters) {
            
            var send = true
            
            if (typeof defOptions.onAjaxBefore === "function") {
                send = defOptions.onAjaxBefore()
            }
            
            if (send) {
                parameters.sessid = BX.bitrix_sessid()
                BX.ajax({
                    method: "POST",
                    url: defOptions.ajaxUrl,
                    data: parameters,
                    dataType: 'json',
                    async: true,
                    processData: true,
                    scriptsRunFirst: false,
                    emulateOnload: false,
                    start: true,
                    cache: false,
                    onsuccess: function (data) {
                        if (typeof data.DATE_FROM === "string") {
                            defOptions.dateFrom = data.DATE_FROM;
                        }
                        if (typeof defOptions.onAjaxSuccess === "function") {
                            defOptions.onAjaxSuccess(data);
                        }
                    },
                    onfailure: function () {
                        if (typeof defOptions.onAjaxFailure === "function") {
                            defOptions.onAjaxFailure()
                        }
                    }
                })
            }
        }
        
        this.watch = function () { 
            setInterval (function () {
                sendRequest({action: "watch", date_from: defOptions.dateFrom})
            }, defOptions.freqRequest*1000)
        }
        
        this.sendMessage = function (message) {
            sendRequest({action: "sendmessage", message: message})
        }
    }
    
}(window, BX, JSON)


