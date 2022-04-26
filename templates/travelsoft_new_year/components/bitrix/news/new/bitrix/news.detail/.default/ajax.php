<?php

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && $_REQUEST["id"] > 0 && $_REQUEST['hl_id'] && check_bitrix_sessid() && $_REQUEST["type"]) {

    Bitrix\Main\Loader::includeModule("highloadblock");
    
    $data_class = Bitrix\Highloadblock\HighloadBlockTable::compileEntity(
            Bitrix\Highloadblock\HighloadBlockTable::getById($_REQUEST['hl_id'])->fetch())->getDataClass();
    
    if ($_REQUEST["type"] == "srv") {
         $ar_res = $data_class::getList(array(
                "filter" => array("ID" => $_REQUEST["id"]),
                "select" => array("ID", "UF_NAME", "UF_PICTURES", "UF_SOFA_BAD","UF_BAD1", "UF_BAD2", "UF_SERVICES_IN_ROOM",
                    "UF_SQUARE", "UF_PEOPLE", "UF_SERVICE_DESC") 
            ))->fetch();
    
        $img_marks = null;

        foreach ($ar_res["UF_PICTURES"] as $img_id) {

            $img_marks['small'][] = "<img src='" . getSrcImage($img_id, array("width" => 120, "height" => 90)) . "'>";
            $img_marks["big"][] = "<img class='lazyOwl' data-src='" . getSrcImage($img_id, array("width" => 500, "height" => 400)) . "'>";

        }

        ?>

        <?if ($img_marks) :?>
        <div class="w-66 gallery-wrapper detail-slider mr-15">
                <div class="slide-room-lg">
                   <div id="owl-big-slides">
                       <?= implode("", $img_marks['big']);?>
                   </div>
                </div>
                <div class="slide-room-sm">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div id="owl-small-slides">
                                <?= implode("", $img_marks['small']);?>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <?endif?>
        <div  class="service-description">
            <h3><?= $ar_res['UF_NAME']?></h3>
            <ul>
                <li>Максимальное количество человек: <?= $ar_res["UF_PEOPLE"]?></li>
                <li>Количество диван-кроватей: <?= $ar_res["UF_SOFA_BAD"]?></li>
                <li>Площадь номера: <?= $ar_res["UF_SQUARE"]?></li>
            </ul>
            <div class="desc">
                <?= $ar_res["UF_SERVICE_DESC"]?>
            </div>
        </div>
        <div class="clearfix"></div>
    
    <?} elseif ($_REQUEST["type"] == "rate") {
                $ar_res = $data_class::getList(array(
                    "filter" => array("ID" => $_REQUEST["id"]),
                    "select" => array("ID", "UF_NAME", "UF_NOTE") 
                ))->fetch();
        ?>
        
       <div  class="rate-description">
            <h3><?= $ar_res['UF_NAME']?></h3>
            <div class="desc">
                <?= $ar_res["UF_NOTE"]?>
            </div>
        </div>
        
    <?}
    
    
}
