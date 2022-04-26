<?php

namespace Kosmos\Main\Helpers;

class Common
{

    public static function prepareMailFields($arFields)
    {

        $result = '<table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #f8f8f8;" width="100%" class="mcnTextContentContainer"><tbody>';

        foreach ($arFields as $field) {
            if (!$field['VALUE']) {
                $field['VALUE'] = "-";
            }

            $result .= '<tr><td valign="top" class="mcnTextContent" style="padding: 0px 0px 10px 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #202020;font-family: Helvetica;font-size: 13px;line-height: 150%;text-align: left;"><table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #f8f8f8;" width="100%" class="mcnTextContentContainer"><tbody><tr><td valign="top" class="mcnTextContent" style="padding: 0px 0px 0px 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #595959;font-family: Helvetica;font-size: 12px;line-height: 150%;text-align: left;">' . $field['LABEL'] . '</td></tr><tr><td valign="top" class="mcnTextContent" style="padding: 0px 0px 0px 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #202020;font-family: Helvetica;font-size: 14px;line-height: 150%;text-align: left;">' . $field['VALUE'] . '</td></tr></tbody></table></td></tr>';
        }

        $result .= '</tbody></table>';

        return $result;

    }

    public static function getYoutubeField($link)
    {

        $id = preg_replace('~https?://(?:[0-9A-Z-]+\.)?(?:youtu\.be/| youtube(?:-nocookie)?\.com\S*?[^\w\s-])([\w-]{11})(?=[^\w-]|$)(?![?=&+%\w.-]*(?:[\'"][^<>]*>| </a>))[?=&+%\w.-]*~ix',
            '$1', $link);

        $link = "//img.youtube.com/vi/" . $id;

        $result = [
            "ID" => $id,
            "IMAGES" => [
                "DEFAULT" => $link . "/default.jpg",
                "MEDIUM" => $link . "/mqdefault.jpg",
                "HIGH" => $link . "/hqdefault.jpg",
                "MAX" => $link . "/maxresdefault.jpg",
            ],
        ];

        return $result;
    }

    public static function numDecline($number, $titles)
    {
        if (is_string($titles)) {
            $titles = preg_split('~,\s*~', $titles);
        }

        if (count($titles) < 3) {
            $titles = [func_get_arg(1), func_get_arg(2), func_get_arg(3)];
        }

        $cases = [2, 0, 1, 1, 1, 2];

        return $number . ' ' . $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10,
                5)]];
    }

    public static function getCatalogSectionName($id)
    {
        \Bitrix\Main\Loader::includeModule("iblock");
        $res = \CIBlockElement::GetElementGroups($id, true, ["ID", "NAME"]);
        $name = false;
        while ($row = $res->GetNext()) {
            if (!in_array($row["ID"],
                [CATALOG_SECTION_NEW, CATALOG_SECTION_SPECIAL])) {
                $name = $row["NAME"];
                break;
            }
        }
        return $name;
    }

    public static function getPhone($value)
    {
        return preg_replace('/[^\d+]/', '', $value);
    }

    public static function getPopup(
        $title,
        $message,
        $button = false,
        $link = false,
        $type = false
    ) {
        $html = '
		<div class="popup-ajax-block">
			<div class="heading flex">
				<div class="name">' . $title . '</div>
				<button data-fancybox-close><span class="icon icon-close"></span></button>
			</div>
			<div class="content flex ' . $type . '">
	';

        switch ($type) {
            case 'success':
                $html .= '
				<div class="swal2-icon swal2-success icon-hide">
					<div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
					<span class="swal2-success-line-tip"></span> 
					<span class="swal2-success-line-long"></span>
					<div class="swal2-success-ring"></div> 
					<div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
					<div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
				</div>
				<div class="message">' . $message . '</div>
			';
                break;

            case 'error':
                $html .= '
	    	    <div class="swal2-icon swal2-error icon-hide">
	    	        <span class="swal2-x-mark">
	    	            <span class="swal2-x-mark-line-left"></span>
	    	            <span class="swal2-x-mark-line-right"></span>
	    	        </span>
	    	    </div>
	    	    <div class="message">' . $message . '</div>
	    	';
                break;

            default:
                $html .= '<div class="message">' . $message . '</div>';
                break;
        }

        $html .= '
			</div>
		</div>	
	';
        return $html;
    }

    public static function getEntityDataClass($id)
    {
        $hlBlock = self::getHLArray($id);
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlBlock);
        $entityDataClass = $entity->getDataClass();
        return $entityDataClass;
    }

    public static function getHLArray($id)
    {
        $hlBlock = \Bitrix\Highloadblock\HighloadBlockTable::getById($id)
            ->fetch();
        return $hlBlock;
    }

    public static function str_replace_once($search, $replace, $text)
    {
        $pos = strpos($text, $search);
        return ($pos !== false) ? substr_replace($text, $replace, $pos,
            strlen($search)) : $text;
    }

    public static function mb_ucfirst($str, $encoding = 'UTF-8')
    {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) .
            mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }

    public function fileDownload($file)
    {
        if (file_exists($file)) {
            if (ob_get_level()) {
                ob_end_clean();
            }

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }

    public static function getIcon($icon, $addIconClasses = [])
    {
        return '<svg class="icon icon-' . $icon . ' ' . implode(' ',
                $addIconClasses) . '"><use xlink:href="' . \Kosmos\Main\Constant::get('SRC_PATH') . 'images/sprite.svg#icon-' . $icon . '"></use></svg>';
    }

    public static function getCacheId($code)
    {
        $arResult = [];
        $arResult[] = $code;
        $arResult[] = SITE_ID;
        $arResult = array_merge($arResult,
            \Bitrix\Main\UserTable::getUserGroupIds($GLOBALS['USER']->GetID()));
        return implode('_', $arResult);
    }

    public static function getLastKey($arr)
    {
        $keys = array_keys($arr);
        $keys = array_reverse($keys);
        return $keys[0];
    }
}