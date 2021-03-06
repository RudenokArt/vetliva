!function(e,s){"use strict";function i(e){var s,i=e.length,n=[];for(s=0;i>s;s++)n.push(t(e[s].toString()));return n}function t(e){for(var s=/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,i=e;s.test(i);)i=i.replace(s,"");return i}function n(e,s){var i,t,n="",a="",r="",o="";for(i=0;i<e.small_imgs.length;i++)a+="<img src='"+e.small_imgs[i]+"'>";if(""!==a)for(i=0;i<e.big_imgs.length;i++)r+="<img class='lazyOwl' data-src='"+e.big_imgs[i]+"'>";if(""!==r&&(n+='<div class="w-66 gallery-wrapper detail-slider mr-15">',n+='<div class="slide-room-lg">',n+='<div id="'+P+'">',n+=r,n+="</div>",n+="</div>",n+='<div class="slide-room-sm">',n+='<div class="row">',n+='<div class="col-md-8 col-md-offset-2">',n+='<div id="'+b+'">',n+=a,n+="</div>",n+="</div>",n+="</div>",n+="</div>",n+="</div>"),n+='<div class="service-description">',e.name&&(n+="<h3>"+e.name+"</h3>"),e.desc&&(n+='<div class="desc">'+e.desc+"</div>"),e.square&&(o+="<li><b>"+s.square+"</b>: "+e.square+"</li>"),null!==e.bad1&&(o+="<li><b>"+s.cntBad1+"</b>: "+e.bad1+"</li>"),null!==e.bad2&&(o+="<li><b>"+s.cntBad2+"</b>: "+e.bad2+"</li>"),null!==e.sofa_bad&&(o+="<li><b>"+s.cntSofaBad+"</b>: "+e.sofa_bad+"</li>"),e.places_add&&(o+="<li><b>"+s.cntAddPlaces+"</b>: "+e.places_add+"</li>"),e.places_main&&(o+="<li><b>"+s.cntMainPlaces+"</b>: "+e.places_add+"</li>"),e.people&&(o+="<li><b>"+s.maxPeople+"</b>: "+e.people+"</li>"),e.servicesIn){for(o+="<li><b>"+s.servicesIn+"</b>: <ul>",t=e.servicesIn.length,i=0;t>i;i++)o+="<li>"+e.servicesIn[i]+"</li>";o+="</ul></li>"}return""!==o&&(n+="<ul>"+o+"</ul>"),n+="</div>",n+='<div class="clearfix"></div>'}function a(e){var s="";return s+='<div class="rate-description">',e.title&&(s+="<h3>"+e.title+"</h3>"),e.desc&&(s+='<div class="desc">',s+=e.desc,s+="</div>"),s+="</div>",s+='<div class="clearfix"></div>'}function r(e,s){var i="";return e.text=e.text||s.cancellationPolicyDefaultText,i+='<div class="cancellation-policy">',e.text&&(i+='<div class="cancellation-policy-text">',i+=e.text||s.cancellationPolicyDefaultText,i+="</div>"),i+="</div>",i+='<div class="clearfix"></div>'}function o(e){var s={text:null};return"object"==typeof e&&"string"==typeof e.CANCELLATION_POLICY_TEXT&&(s.text=t(e.CANCELLATION_POLICY_TEXT)),s}function c(e){var s={title:null,desc:null};return"object"==typeof e&&("string"==typeof e.NAME&&(s.title=t(e.NAME)),"string"==typeof e.NOTE&&(s.desc=t(e.NOTE))),s}function l(s){var n={name:null,desc:null,small_imgs:[],big_imgs:[],people:null,sofa_bad:null,square:null,places_add:null};return"object"==typeof s&&("string"==typeof s.NAME&&(n.name=t(s.NAME)),"string"==typeof s.DESC&&(n.desc=t(s.DESC)),s.PICTURES&&"object"==typeof s.PICTURES&&(e.isArray(s.PICTURES.small)&&(n.small_imgs=i(s.PICTURES.small)),e.isArray(s.PICTURES.big)&&(n.big_imgs=i(s.PICTURES.big))),e.isArray(s.SERVICES)&&(n.servicesIn=s.SERVICES),"undefined"!=typeof s.PEOPLE&&(n.people=Number(s.PEOPLE)),"undefined"!=typeof s.BAD1&&(n.bad1=Number(s.BAD1)),"undefined"!=typeof s.BAD2&&(n.bad2=Number(s.BAD2)),"undefined"!=typeof s.SOFA_BAD&&(n.sofa_bad=Number(s.SOFA_BAD)),"undefined"!=typeof s.PLACES_ADD&&(n.places_add=Number(s.PLACES_ADD)),"undefined"!=typeof s.SQUARE&&(n.square=Number(s.SQUARE))),n}function d(e){var s={};if("undefined"==typeof e.anchor||!e.anchor)throw new Error("?????????????? ???????????????????? ??????????");if(s.anchor=e.anchor,"undefined"==typeof e.sessid||!e.sessid)throw new Error("???????????????????????? ?????????????????????????? ????????????????????????");s.sessid=e.sessid}function u(s,i){var t={messages:{}};if("object"!=typeof s.messages||!s.messages)throw new Error("?????????????? ?????????????? ????????????");e(i).each(function(e,i){"string"==typeof s.messages[i]&&""!==s.messages[i].toString()&&(t.messages[i]=s.messages[i])})}function f(s){var i=e.extend({},u(s,["maxPeople","cntSofaBad","square","servicesIn"]));return i}function p(e){var s={};if("string"!=typeof e.redirect||!e.redirect.length)throw new Error("?????????????? ?????????????? ???????????????? ???????????????? ?????? ????????????????????????");return s.redirect=e.redirect,s}function m(s){var i=e.extend({},u(s,["no_result"]));return i}function v(e){if("string"==typeof e.error_message&&!e.error_message.length)throw alert(e.error_message),new Error(e.error_message)}function g(s){s.that.one("click",function(i){function t(e){e.magnificPopup({type:"inline",midClick:!0}).magnificPopup("open")}var n=e(this);delete C[s.index],e("#"+s.popupAreaId).length?t(n):(s.that.after("<div id='"+s.popupAreaId+'\'><div class="defmess">'+s.messages.loadingMessage+"</div></div>"),t(n),e.ajax({url:y,data:{type:s.type,id:s.id,sessid:s.sessid},dataType:"json",statusCode:{404:function(){s.that.on("click",function(e){e.preventDefault()})}},success:function(i){var t,n,a,r,o;i&&(v(i),r=s.renderFn(s.dataFilterFn(i),s.messages),t=e("#"+s.popupAreaId),o=t.find(".defmess"),o.length>0?o.replaceWith(r):t.html(r),n=t.find("#"+b),a=t.find("#"+P),a.owlCarousel({items:1,lazyLoad:!0,navigation:!0,navigationText:["<span class='prev-next-room prev-room'></span>","<span class='prev-next-room next-room'></span>"],pagination:!1,itemsCustom:[[320,1],[480,1],[768,1],[992,1],[1200,1]]}),n.owlCarousel({mouseDrag:!1,navigation:!1,itemsCustom:[[320,3],[480,5],[768,6],[992,7],[1200,8]],pagination:!1}),n.on("click",".owl-item",function(s){if(s.preventDefault(),e(this).hasClass("synced"))return!1;e(".synced").removeClass("synced"),e(this).addClass("synced");var i=e(this).data("owlItem");a.data("owlCarousel").goTo(i)}))}})),i.preventDefault()})}function h(s,i){s=e.extend({},s,d(s),f(s)),e(s.anchor).each(function(){var t=e(this),n={that:t,type:i.type,id:t.data("id"),sessid:s.sessid,dataFilterFn:i.dataFilterFn,popupAreaId:t.attr("href").replace("#",""),renderFn:i.renderFn,messages:s.messages};"undefined"!=typeof i.setToOff&&i.setToOff===!0&&(n.index=C.push(t)-1),g(n)})}var y="/local/components/travelsoft/travelsoft.service.price.result/ajax.php",b="owl-small-slides",P="owl-big-slides",C=[];e.initServicePopup=function(e){h(e,{renderFn:n,dataFilterFn:l,type:"service",setToOff:!0})},e.initRatePopup=function(e){h(e,{renderFn:a,dataFilterFn:c,type:"rate",setToOff:!0})},e.initCancellationPolicyPopup=function(e){h(e,{renderFn:r,dataFilterFn:o,type:"cancellation_policy",setToOff:!0})},e.addToCartInit=function(i){i=e.extend({},i,d(i),p(i)),e(i.anchor).each(function(){e(this).on("click",function(t){var n=e(this);t.preventDefault(),e.ajax({url:y,dataType:"Json",data:{add2cart:n.data("add2cart"),sessid:i.sessid},success:function(e){v(e),"string"==typeof e.message_ok&&"ok"===e.message_ok&&(s.location.href=i.redirect)}})})})},e.citizenPricesInit=function(s){function i(i){e(C).each(function(e,s){"undefined"!=typeof s&&s&&s.off("click")}),C=[],i?(e(s.insertContainer).html(i),s.initServicePopup&&e.initServicePopup({sessid:s.sessid,anchor:s.servicesAnchor,messages:{maxPeople:s.messages.maxPeople,cntSofaBad:s.messages.cntSofaBad,square:s.messages.square,servicesIn:s.messages.servicesIn,cntAddPlaces:s.messages.cntAddPlaces,cntBad1:s.messages.cntBad1,cntBad2:s.messages.cntBad2,cntMainPlaces:s.messages.cntMainPlaces}}),s.initRatePopup&&e.initRatePopup({sessid:s.sessid,anchor:s.rateAnchor,messages:{}}),s.initCancellationPolicyPopup&&e.initCancellationPolicyPopup({sessid:s.sessid,anchor:s.cancellationPolicyAnchor,messages:{cancellationPolicyDefaultText:s.messages.cancellationPolicyDefaultText}}),s.initAddToCart&&e.addToCartInit({sessid:s.sessid,anchor:s.addToCartAnchor,redirect:s.redirect})):e(s.insertContainer).html(s.messages.no_result)}var n={};s=e.extend({},s,d(s),m(s),p(s),f(s)),n[e(s.anchor).val()]=e(s.insertContainer).html(),e(s.anchor).on("change",function(){var a=e(this).val();"undefined"==typeof n[a]?(s.sparams.citizen_price=a,e.ajax({url:y,dataType:"Json",data:{type:"cprice",sessid:s.sessid,sparams:s.sparams},success:function(e){v(e),"undefined"!=typeof e.content?n[a]=t(e.content):n[a]=null,i(n[a])}})):i(n[a])})}}(jQuery,document);