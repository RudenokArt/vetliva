<?php
/**
 * Created by PhpStorm.
 * User: kosmos
 * Date: 13.12.2017
 * Time: 19:24
 */

namespace Kosmos\Main\Helpers;

use Bitrix\Main\Diag\Helper,
    Bitrix\Main\Config\Option;

class Debug
{

    const module_id = "kosmos.main";

    public static function printr($arr, $getFile = false)
    {
        global $USER;

        if ($USER->IsAdmin() || Option::get(self::module_id,
                "debug_view_all") == "Y") {
            if (class_exists('krumo') && (Option::get(self::module_id,
                        "debug_krumo") == "Y")) {
                krumo($arr);
            } else {
                $arStyle = [
                    "pre" => "
                    font-size: 12px; 
                    font-family: 'Consolas', Arial, sans-serif;
                    background: #293134;
                    padding: 10px 20px;
                    color: #d0c900;
                    overflow: scroll;
                    text-align: left;
                ",
                    "file" => "
                    background: #d0c900;
                    color: #293134;
                    margin: -10px -20px 10px -20px;
                    padding: 5px 20px;
                ",
                ];

                echo '<pre style="' . $arStyle["pre"] . '">';

                if ($getFile) {
                    $backTrace = Helper::getBackTrace(1);
                    $backTrace = $backTrace[0];
                    $backTrace["file"] = str_replace($_SERVER["DOCUMENT_ROOT"],
                        "", $backTrace["file"]);

                    echo '<div style="' . $arStyle["file"] . '">File: ' . $backTrace["file"] . ' (' . $backTrace["line"] . ')</div>';
                }

                print_r($arr);
                echo '</pre>';
            }
        }
    }
}