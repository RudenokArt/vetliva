<?php

namespace Kosmos\Main\Helpers;


class Image
{

    // размеры, которые нужно вернуть
    private $_sizes;

    // ID всех переданных изображений
    private $_images = [];

    // Информация об изображениях
    private $_imagesInfo = [];

    // Пережатые изображения
    private $_resizeImages = [];

    private $_watermark = false;

    const module_id = "kosmos.main";

    public function __construct($arSizes)
    {
        if (!is_array(current($arSizes))) {
            $arSizes["DEFAULT"] = $arSizes;
        }

        $arReturnSizes = [];
        foreach ($arSizes as $key => $arSize) {
            if ((int)$arSize[0] > 0 && (int)$arSize[1] > 0) {
                $arReturnSizes[$key] = [
                    "WIDTH" => (int)$arSize[0],
                    "HEIGHT" => (int)$arSize[1],
                    "TYPE" => self::_getResizeType($arSize[2]),
                ];
            }
        }

        if (!empty($arReturnSizes)) {
            $this->_sizes = $arReturnSizes;
        } else {
            return false;
        }
    }

    public function add($image)
    {
        if (is_array($image)) {
            if ($image["ID"]) {
                $this->_add($image);
            } else {
                foreach ($image as $id) {
                    $this->_add((int)$id);
                }
            }
        } else {
            $this->_add((int)$image);
        }
    }

    private function _add($image)
    {
        $arFile = (is_array($image)) ? $image : \CFile::GetFileArray($image);
        if ($arFile["ID"] > 0) {
            if (!in_array($arFile["ID"], $this->_images)) {
                $this->_images[] = $arFile["ID"];
                $this->_imagesInfo[$arFile["ID"]] = $arFile;
            }
        }
    }

    public function getResizeArray()
    {
        $this->_getResize();
        $arResult["RESIZE"] = $this->_makeResultArray();
        return $arResult;
    }

    private static function _getResizeType($type = false)
    {
        switch ($type) {
            case "P":
                $type = BX_RESIZE_IMAGE_PROPORTIONAL_ALT;
                break;
            case "PS":
                $type = BX_RESIZE_IMAGE_PROPORTIONAL;
                break;
            default:
                $type = BX_RESIZE_IMAGE_EXACT;
                break;
        }

        return $type;
    }

    private function _getResize()
    {
        foreach ($this->_images as $id) {
            foreach ($this->_sizes as $key => $arSize) {
                $arWatermark = ($this->_watermark) ? self::getWatermark($id,
                    $arSize) : [];

                $resizeImage = \CFile::ResizeImageGet($id, [
                    "width" => $arSize["WIDTH"],
                    "height" => $arSize["HEIGHT"],
                ], $arSize["TYPE"], true, $arWatermark);

                $this->_resizeImages[$id][$key] = $resizeImage["src"];
            }
        }
    }

    private function _makeResultArray()
    {
        $arResult = [];

        foreach ($this->_images as $id) {
            $arImage = [
                'SIZES' => [],
                'META' => [
                    "ALT" => ($this->_imagesInfo[$id]["ALT"]) ? $this->_imagesInfo[$id]["ALT"] : $this->_imagesInfo[$id]["DESCRIPTION"],
                    "TITLE" => ($this->_imagesInfo[$id]["TITLE"]) ? $this->_imagesInfo[$id]["TITLE"] : $this->_imagesInfo[$id]["DESCRIPTION"],
                ],
            ];

            foreach ($this->_sizes as $key => $arSize) {
                $arImage['SIZES'][$key] = $this->_resizeImages[$id][$key];
            }

            $arImage['SIZES']["ORIGINAL"] = $this->_imagesInfo[$id]["SRC"];

            $arResult[] = $arImage;

        }

        return $arResult;
    }

    public function setResizeArray(&$arResult)
    {
        $arResize = $this->getResizeArray();
        if (!is_array($arResize)) {
            $arResize = [];
        }

        if (!is_array($arResult)) {
            $arResult = [];
        }

        return $arResult + $arResize;
    }

    public static function setSelfResizeArray(
        &$arResult,
        $arSizes,
        $arWatermark = false
    ) {
        $image = new Image($arSizes);
        $image->add($arResult);
        $image->_watermark = $arWatermark;
        $arResult = $image->setResizeArray($arResult);
        return $arResult;
    }

    public static function getWatermark($id, $arSize)
    {
        $dirPath = $_SERVER['DOCUMENT_ROOT'] . '/upload/watermark/';
        $dir = new \Bitrix\Main\IO\Directory($dirPath);
        if (!$dir->isExists()) {
            $dir->create();
        }

        $watermark = $_SERVER['DOCUMENT_ROOT'] . \Bitrix\Main\Config\Option::get(self::module_id,
                'image_watermark');
        $arWatermark = [];

        if (is_readable($watermark)):

            $img = \CFile::ResizeImageGet($id,
                ["width" => $arSize["WIDTH"], "height" => $arSize["HEIGHT"]],
                $arSize["TYPE"], true);

            $watermarkParams = [
                "WIDTH" => (int)$img["width"],
                "HEIGHT" => (int)$img["height"],
            ];

            \CFile::Delete($img["id"]);

            $watermarkResized = $dirPath . 'watermark_' . $watermarkParams["WIDTH"] . '_' . $watermarkParams["HEIGHT"] . '.png';

            if (!is_readable($watermarkResized)) {
                \CFile::ResizeImageFile($watermark, $watermarkResized, [
                    "width" => $watermarkParams["WIDTH"],
                    "height" => $watermarkParams["HEIGHT"],
                ], BX_RESIZE_IMAGE_PROPORTIONAL);
            }

            if (is_readable($watermarkResized)):
                $arWatermark = [
                    [
                        "name" => "watermark",
                        "type" => "image",
                        "position" => "center",
                        "size" => "real",
                        "file" => $watermarkResized,
                        "alpha_level" => 100,
                    ],
                ];
            endif;
        endif;

        return $arWatermark;
    }

    public function addWatermark()
    {
        $this->_watermark = true;
    }
}