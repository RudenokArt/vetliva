<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();


//определение видимости пункта меню по коду свойства
function resultCode ($item, $propertyCode){
    $flag = false;
    if(is_array($propertyCode)){
        foreach ($propertyCode as $prop){
            if(!empty($item[$prop]["VALUE"])){
                $flag = true;
                break;
            }
        }
    }
    else {
        $flag = !empty($item[$propertyCode]["VALUE"]) ? true : false;
    }
    return $flag;
}

//вывод пунктов меню
function showItem ($id, $menu){
    $constructorMenu = '';
    if(isset($menu[$id])){
        $constructorMenu = '<h2>'.$menu[$id]["TITLE"].'</h2><hr class="hr">';
        foreach ($menu as $key=>$itemMenu){
            if($key != $id && $itemMenu["SHOW"]){
                $constructorMenu .= '<a href="#'.$itemMenu["ANCHOR"].'" title="'.$itemMenu["TITLE"].'">'.$itemMenu["TITLE"].'</a>';
            }
        }
    }
    echo $constructorMenu;
}

function showReviews ($id) { 
    $res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>36,"ACTIVE"=>"Y","PROPERTY_ITEM"=>$id), false, false, Array()); 
    $cnt = $res->SelectedRowsCount(); 
    return ($cnt >= 1) ? true : false; 
}