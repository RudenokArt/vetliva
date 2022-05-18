<?

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('highloadblock');

/**
 * "Принт" переменной
 *
 * @global CUser $USER
 *
 * @param mixed $var переменная для debug
 *
 * @param boolean $vd использовать var_dump для debug
 *
 * @param boolean $tofile вывод в файл $_SERVER['DOCUMENT_ROOT'] . "/debug.txt"
 *
 * @param boolean $onlyAdmin доступна ли функция только для админа
 */
function dm($var, $vd = false, $tofile = false, $onlyAdmin = true) {

    global $USER;

    if ($onlyAdmin && !$USER->IsAdmin()) {
        return;
    }

    if ($tofile) {
        ob_start();
    }

    echo "<pre>";

    if ($vd) {
        var_dump($var);
    } else {
        print_r($var);
    }

    echo "</pre>";

    if ($tofile) {
        file_put_contents("debug.txt", ob_get_clean());
    }
}

/**
 * Обёртка для implode
 * @var array $arr
 * @return string
 */
function implode2($arr, $delimiter = ', ', $no_strip = false) {

    return implode(
            $delimiter, array_map(
                    function ($it) {

                return $no_strip ? $it : strip_tags($it);
            }, array_filter(
                            $arr, function ($el) {
                        return ($el && !empty($el));
                    }
                    )
            )
    );
}

/**
 * Путь к изрбражению по его id
 * @var int $img - id изображения
 * @var array $resize
 * @var array $arr_watermark
 * @return string
 */
function getSrcImage($img, $resize = array(), $noPhoto = "", array $arr_watermark = array()) {

    $img = (int) $img;

    if ($noPhoto == "")
        $src = "";
    else
        $src = $noPhoto;

    if ($img > 0) {

        if ($resize['width'] > 0 && $resize['height'] > 0) {
            $file = CFile::ResizeImageGet($img, $resize, BX_RESIZE_IMAGE_EXACT, true, $arr_watermark);
            $src = $file['src'];
        } else {
            $file = CFile::GetFileArray($img);
            $src = $file['SRC'];
        }
    }

    return $src;
}

/**
 * Обёртка для substr
 * @var string $str
 * @var int $nos
 * @return string
 */
function substr2($str, $nos = null) {

	$str = strip_tags($str);

	if ($nos === null || strlen($str) <= $nos){
        return $str;
	}

	return mb_substr($str, 0, $nos, 'UTF-8') . "...";
}

/**
 * Получаем поля элемента по его ID
 * @var integer $id
 * @return mixed
 */
function getIBElement($id) {

    if (!($res = getIBElementFields($id)))
        return false;

    $arr = $res;

    if (!($res = getIBElementProperties($id)))
        return false;

    $arr['PROPERTIES'] = $res;

    return $arr;
}

// только поля
function getIBElementFields($id) {

    if (!($res = CIBlockElement::GetByID($id)->GetNextElement()))
        return false;

    return $res->GetFields();
}

// только свойства
function getIBElementProperties($id) {

    if (!($res = CIBlockElement::GetByID($id)->GetNextElement()))
        return false;

    return $res->GetProperties();
}

/**
 * @param string $paramName
 * @param array $array
 */
function __getArrayUriQueryString($paramName, array $array) {
    for ($i = 0, $cnt = count($array); $i < $cnt; $i++) {
        $arr[] = "booking[" . $paramName . "][]=" . $array[$i];
    }
    return implode("&", $arr);
}

/**
 * возвращает ссылку
 * @param string $link
 * @param array $bookingRequest
 * @param array $additionalParams
 * @param string $anchor
 */
function getCalculateDetailLink($link, array $bookingRequest, array $additionalParams = null, string $anchor = null) {

    $arUri = null;

    if (!empty($bookingRequest)) {

        if (!empty($bookingRequest["id"]) && is_array($bookingRequest["id"])) {
            $arUri[] = __getArrayUriQueryString("id", $bookingRequest["id"]);
        }

        if ($bookingRequest["date_from"]) {
            $arUri[] = "booking[date_from]=" . $bookingRequest["date_from"];
        }

        if ($bookingRequest["date_to"]) {
            $arUri[] = "booking[date_to]=" . $bookingRequest["date_to"];
        }

        if (isset($bookingRequest["adults"])) {
            $arUri[] = "booking[adults]=" . $bookingRequest["adults"];
        }

        if ($bookingRequest["point_A"]) {
            $arUri[] = "booking[point_A]=" . $bookingRequest["point_A"];
        }

        if ($bookingRequest["point_B"]) {
            $arUri[] = "booking[point_B]=" . $bookingRequest["point_B"];
        }

        if ($bookingRequest["roundtrip"] == "Y") {
            $arUri[] = "booking[roundtrip]=" . $bookingRequest["roundtrip"];
        }

        if ($bookingRequest["children"]) {
            $arUri[] = "booking[children]=" . $bookingRequest["children"];
        }

        if (!empty($bookingRequest["children_age"]) && is_array($bookingRequest["children_age"])) {
            $arUri[] = __getArrayUriQueryString("children_age", $bookingRequest["children_age"]);
        }
    }

    if (!empty($additionalParams)) {
        foreach ($additionalParams as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $kkey => $vval) {
                    $arUri[] = $key . "[" . $kkey . "]=" . $vval;
                }
            } else {
                $arUri[] = $key . "=" . $val;
            }
        }
    }

    $result = $arUri ? $link . "?" . implode("&", $arUri) : $link;

    return $anchor ? $result . "#" . $anchor : $result;
}

//проверка наличия отзывов
function showReviews($id) {
    $res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => 36, "ACTIVE" => "Y", "PROPERTY_ITEM" => $id), false, false, Array());
    $cnt = $res->SelectedRowsCount();
    return ($cnt >= 1) ? true : false;
}
//проверка наличия комментов
function showComments($id) {
    $res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => 63, "ACTIVE" => "Y", "PROPERTY_ITEM" => $id), false, false, Array());
    $cnt = $res->SelectedRowsCount();
    return ($cnt >= 1) ? true : false;
}

function getNameCSV($id) {
    $res = CIBlockElement::GetByID($id);
    if ($ar_res = $res->GetNext())
        return $ar_res['NAME'];
}

function checkPopAfterActivity($ib_id, $el_id) {
	$res = CIBlockElement::GetList(Array(), Array("ID" => $el_id), false, false, 
			array("ID", "IBLOCK_ID"));
	$ob = $res->GetNextElement();

    // TODO фикс ошибок в логе
    if(empty($ob)) return;

	$arProp = $ob->GetProperties();
	$prop_val = ($ib_id == 33) ? 156 : 163;

	$prop_new_array = array_diff($arProp["NEWYEAR"]["VALUE_ENUM_ID"], array($prop_val));
	if(empty($prop_new_array))
		$prop_new_array = 0;

	$prop_change = array(
		"NEWYEAR" => $prop_new_array
	);

	CIBlockElement::SetPropertyValuesEx($el_id, $ib_id, $prop_change);
}

function num2word($num, $words, $show_num = true)
{
    $num_text = '';
    $num = $num % 100;
    if ($num > 19) {
        $num = $num % 10;
    }

    if($show_num){
        $num_text = $num." ";
    }
    else{
        $num_text = "";
    }
    switch ($num) {
        case 1: {
            return $num_text.$words[0];
        }
        case 2: case 3: case 4: {
        return $num_text.$words[1];
    }
        default: {
            return $num_text.$words[2];
        }
    }
}
function check_smartphone() {
 
    $phone_array = array('iphone', 'android', 'pocket', 'palm', 'windows ce', 'windowsce', 'cellphone', 'opera mobi', 'ipod', 'ipad', 'small', 'sharp', 'sonyericsson', 'symbian', 'opera mini', 'nokia', 'htc_', 'samsung', 'motorola', 'smartphone', 'blackberry', 'playstation portable', 'tablet browser');
    $agent = strtolower( $_SERVER['HTTP_USER_AGENT'] );
 
    foreach ($phone_array as $value) {
 
        if ( strpos($agent, $value) !== false ) return true;
 
    }
 
    return false;
 
}
function Get_Name_Element ($element_id) {
    if (CModule::IncludeModule("iblock")) {
        $res = CIBlockElement::GetByID($element_id);
        $ar_res = $res->GetNext();
        if (POSTFIX_PROPERTY!='') {
            $name_en = CIBlockElement::GetProperty($ar_res['IBLOCK_ID'], $element_id, Array("sort"=>"asc"), Array("CODE"=>"NAME".POSTFIX_PROPERTY))->Fetch()['VALUE'];
            if ($name_en!='') return $name_en;
            else return $ar_res['NAME'];
        }
        else {
           return $ar_res['NAME'];
        }        
    }
}

function getPriceOne($id, $type,$parameters=[]) {
    $parameters["id"][] = $id;
    global $APPLICATION;
        $result = $APPLICATION->IncludeComponent(
    	"travelsoft:travelsoft.service.price.result", 
    	"on.detail.page.render", 
    	array(
    		"RETURN_RESULT" => "Y",
    		"FILTER_BY_PRICES_FOR_CITIZEN" => "N",
    		"TYPE" => $type,//"excursionstours",
    		"POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
    		"__BOOKING_REQUEST" => $parameters,
    		"MP" => "Y",
    		"COMPONENT_TEMPLATE" => "on.detail.page.render",
    		"CODE" => "",
    		"MAKE_ORDER_PAGE" => "/booking/",
    		"INC_JQUERY" => "N",
    		"INC_MAGNIFIC_POPUP" => "N",
    		"INC_OWL_CAROUSEL" => "N"
    	),
    	false
    );
    return $result;
}

function makeWebp ($src) {
    //return false; 
	$newImgPath = false;
    //return false; 
	if (function_exists('imagewebp')) {
		$src = ToLower($src);
		if (strpos($src, '.png')) {
		    return false; 
		    $newImgPath = str_replace('.png', '.webp', $src);  
            $newname = end(explode('/',$newImgPath));
            $newImgPath =  '/upload/webp/'.$newname;
		    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $newImgPath)) {
		      
                $im = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . $src);
                imagepalettetotruecolor($im);
                imageAlphaBlending($im, true); 
                imageSaveAlpha($im, true);
                
                
                imagewebp($im, $_SERVER['DOCUMENT_ROOT'] . $newImgPath, 100);
                imagedestroy($im);  
                $fpr=fopen($_SERVER['DOCUMENT_ROOT'] . $newImgPath, "a+");
                fwrite($fpr, chr(0x00));
                fclose($fpr);
              
			}  
		} elseif (strpos($src, '.jpg') !== false || strpos($src, '.jpeg') !== false) {
			$newImgPath = str_replace(array('.jpg', '.jpeg'), '.webp', $src);
            $newname = end(explode('/',$newImgPath));
            $newImgPath =  '/upload/webp/'.$newname;
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $newImgPath) ) {
		        $newImg = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'] . $src);
                ob_start();
                imagejpeg($newImg,NULL,100);
                $cont=  ob_get_contents();
                ob_end_clean();
                if ($newImg)  $content =  imagecreatefromstring($cont);
                imagewebp($content, $_SERVER['DOCUMENT_ROOT'] . $newImgPath, 80);
                imagedestroy($content);
                imagedestroy($newImg);
                $fpr=fopen($_SERVER['DOCUMENT_ROOT'] . $newImgPath, "a+");
                fwrite($fpr, chr(0x00));
                fclose($fpr);
            }
        }
	}
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $newImgPath) || filesize($_SERVER['DOCUMENT_ROOT'] . $newImgPath)<100) return false;
    else return $newImgPath;
}
function makeWebpBig ($src) {
   // return false; 
	$newImgPath = false;
    //return false; 
	if (function_exists('imagewebp')) {
		$src = ToLower($src);
		if (strpos($src, '.png')) {
		    return false; 
		    $newImgPath = str_replace('.png', '.webp', $src);  
            $newname = end(explode('/',$newImgPath));
            $newImgPath =  '/upload/webp/detail/'.$newname;
		    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $newImgPath)) {
		      
                $im = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . $src);
                imagepalettetotruecolor($im);
                imageAlphaBlending($im, true); 
                imageSaveAlpha($im, true);
                
                
                imagewebp($im, $_SERVER['DOCUMENT_ROOT'] . $newImgPath, 100);
                imagedestroy($im);  
                $fpr=fopen($_SERVER['DOCUMENT_ROOT'] . $newImgPath, "a+");
                fwrite($fpr, chr(0x00));
                fclose($fpr);
              
			}  
		} elseif (strpos($src, '.jpg') !== false || strpos($src, '.jpeg') !== false) {
			$newImgPath = str_replace(array('.jpg', '.jpeg'), '.webp', $src);
            $newname = end(explode('/',$newImgPath));
            $newImgPath =  '/upload/webp/detail/'.$newname;
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $newImgPath) ) {
		        $newImg = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'] . $src);
                ob_start();
                imagejpeg($newImg,NULL,100);
                $cont=  ob_get_contents();
                ob_end_clean();
                if ($newImg)  $content =  imagecreatefromstring($cont);
                imagewebp($content, $_SERVER['DOCUMENT_ROOT'] . $newImgPath, 80);
                imagedestroy($content);
                imagedestroy($newImg);
                $fpr=fopen($_SERVER['DOCUMENT_ROOT'] . $newImgPath, "a+");
                fwrite($fpr, chr(0x00));
                fclose($fpr);
            }
        }
	}
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $newImgPath) || filesize($_SERVER['DOCUMENT_ROOT'] . $newImgPath)<100) return false;
    else return $newImgPath;
}

/**
 * Получить параметры фильтра разделов для компонента поиска
 * @return array
 */
function getSearchFilter() {

    $arParamsFilter = [
        'SANATORIUM' => [SANATORIUM_IBLOCK_ID],
        'EXCURSION' => [EXCURSION_IBLOCK_ID],
        'PLACEMENTS' => [PLACEMENTS_IBLOCK_ID],
        'ABOUT_BELARUS' => AR_ABOUT_BELARUS_IBLOCK_ID,
    ];

    $arFilterIblockId = [];
    if (empty($_REQUEST['filter'])) {
        foreach ($arParamsFilter as $arVal) {
            $arFilterIblockId = array_merge($arFilterIblockId, $arVal);
        }
    } else {
        foreach ($_REQUEST['filter'] as $key=>$value) {
            if (!empty($arParamsFilter[$key])) {
                $arFilterIblockId = array_merge($arFilterIblockId, $arParamsFilter[$key]);
            }
        }
    }

    if (empty($_REQUEST['filter']) || !empty($_REQUEST['filter']['ABOUT_BELARUS'])) {

        $arFilterSearch =   array(
            array(
                "LOGIC" => "OR",
                array(
                    "=MODULE_ID" => "iblock",
                    "=PARAM2" => $arFilterIblockId,
                ),
                array(
                    "=MODULE_ID" => "main",
                    "%ITEM_ID" => ABOUT_BELARUS_ROOT_URL
                )
            )
        );

        /**
         * Отдельная фильтрация афиши, т.к. для дат используется не стандартное поле
         */
        $arPosterActiveElementId = [];
        $arSelect = Array("IBLOCK_ID ", "ID");
        $arFilter = Array(
            "IBLOCK_ID"=> POSTER_IBLOCK_ID,
            "ACTIVE"=>"Y",
            ">=PROPERTY_DATE_FROM" => date("Y-m-d")
        );
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        while($ob = $res->Fetch()) {
            $arPosterActiveElementId[] = $ob['ID'];
        }
        if (!empty($arPosterActiveElementId)) {
            $arFilterSearch[0][] = array(
                "=MODULE_ID" => "iblock",
                "=PARAM2" => [POSTER_IBLOCK_ID],
                "=ITEM_ID" => $arPosterActiveElementId
            );
        }
    } else {
        $arFilterSearch = array(
            array(
                "=MODULE_ID" => "iblock",
                "=PARAM2" => $arFilterIblockId,
            ),
        );
    }

    return $arFilterSearch;
}

function sidebarMenuLinkFilter ($arResult) { // фильтр пунктов меню для разделов школьный и эксклюзивный туризм
  $arr = [];
  $url = explode('/', $_SERVER['REQUEST_URI'])[2];
  if ($url == 'shkolnyy-i-inklyuzivnyy-turizm') {
    foreach ($arResult as $key => $value) {
      $arResult[$key]['link_filter'] = explode('/', $value['LINK'])[2];
      $link = explode('/', $value['LINK'])[2];
      if ($link == 'shkolnyy-i-inklyuzivnyy-turizm') {
        array_push($arr, $value);
      }
    }
  } else {
    $arr = $arResult;
  }
  return $arr;
}