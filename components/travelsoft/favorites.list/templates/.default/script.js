
BX.namespace("Travelsoft");

if (!BX.Travelsoft.add2favorites) {
    BX.Travelsoft.add2favorites = function (url, object_type, object_id, store_id, hash, context, show_short) {
        context.disabled = true;
        BX.ajax.get(url, {
            sessid: BX.bitrix_sessid(),
            OBJECT_TYPE: object_type || '',
            OBJECT_ID: object_id || '',
            HASH: hash || '',
            ACTION: "ADD",
            STORE_ID: store_id || ''
        }, function (resp) {
            
            resp = JSON.parse(resp);
            context.disabled = false;
            if (resp.error) {
                alert(BX.message('TRAVELSOFT_FAVORITES_ADD_ERROR'));
                return;
            }
            
            context.classList = "favorites__button bg-star_filled";
            if (show_short!='Y') context.innerText = BX.message("TRAVELSOFT_FAVORITES_REMOVE_FAV");
            context.onclick = function () {
                BX.Travelsoft.deleteFromFavorites(url, object_type, object_id, store_id, hash, this, show_short);
            };
            
        });
    };
}

if (!BX.Travelsoft.deleteFromFavorites) {
    BX.Travelsoft.deleteFromFavorites = function (url, object_type, object_id, store_id, hash, context, show_short) {
        context.disabled = true;
        BX.ajax.get(url, {
            sessid: BX.bitrix_sessid(),
            OBJECT_TYPE: object_type || '',
            OBJECT_ID: object_id || '',
            HASH: hash || '',
            ACTION: "DELETE",
            STORE_ID: store_id || ''
        }, function (resp) {
            
            resp = JSON.parse(resp);
           
            context.disabled = false;
            if (resp.error) return;
            $.ajax({
        		url: '/private-office/wish-list/',
        		type: 'GET',
                dataType: 'html',
                success: function(html){
                    if($('.favorites').length){
        				$('.favorites').html($(html).find('.favorites').html());
                        $('.owl-carousel.wish-list').owlCarousel({
                            items: 3,
                            loop:true,
                            margin:10,
                            navigation:true,
                            navigationText: ['<span class="prev-next-room prev-room"></span>','<span class="prev-next-room next-room"></span>'],
                            dots: false
                        })
        			}
        		}
        	})
            
            

        });
    };
}