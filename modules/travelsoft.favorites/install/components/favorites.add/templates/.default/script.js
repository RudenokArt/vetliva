
BX.namespace("Travelsoft");

if (!BX.Travelsoft.add2favorites) {
    BX.Travelsoft.add2favorites = function (url, object_type, object_id, store_id, hash, context) {
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
            context.innerText = BX.message("TRAVELSOFT_FAVORITES_REMOVE_FAV");
            context.onclick = function () {
                BX.Travelsoft.deleteFromFavorites(url, object_type, object_id, store_id, hash, this);
            };
            
        });
    };
}

if (!BX.Travelsoft.deleteFromFavorites) {
    BX.Travelsoft.deleteFromFavorites = function (url, object_type, object_id, store_id, hash, context) {
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
            if (resp.error) {
                alert(BX.message('TRAVELSOFT_FAVORITES_DELETE_ERROR'));
                return;
            }
            
            context.classList = "favorites__button bg-star";
            context.innerText = BX.message("TRAVELSOFT_FAVORITES_ADD_TO_FAV");
            context.onclick = function () {
                BX.Travelsoft.add2favorites(url, object_type, object_id, store_id, hash, this);
            };

        });
    };
}