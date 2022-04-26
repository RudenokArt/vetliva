<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
CJSCore::Init(array('popup', 'date'));
?>


    <div class="filter-area white-area">
        <form id="history-list" method="GET" action="<?= $APPLICATION->GetCurPageParam("", array("HISTORY_VP_STAT[DATE_FROM]", "HISTORY_VP_STAT[DATE_TO]"), false);?>">
            <input type="hidden" name="ID" value="<?= htmlspecialchars($_REQUEST["ID"])?>">
            <fieldset>
                <legend><?= GetMessage("FILTER_TITLE")?></legend>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                        <div class="form-group has-feedback">
                            <label for="HISTORY_VP_STAT[DATE_FROM]"><?= GetMessage("FILTER_DATE_FROM_TITLE")?></label>
                            <input required="" onclick="BX.calendar({node: this, field: this, bTime: true})" value="<?if (strlen($_REQUEST["HISTORY_VP_STAT"]["DATE_FROM"]) > 0) { echo htmlspecialchars($_REQUEST["HISTORY_VP_STAT"]["DATE_FROM"]); } else {echo $arResult["DATE_FROM"];}?>" class="form-control" id="HISTORY_VP_STAT[DATE_FROM]" type="text" name="HISTORY_VP_STAT[DATE_FROM]">
                            <span class="glyphicon glyphicon-calendar form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                        <div class="form-group has-feedback">
                            <label for="HISTORY_VP_STAT[DATE_TO]"><?= GetMessage("FILTER_DATE_TO_TITLE")?></label>
                            <input required="" onclick="BX.calendar({node: this, field: this, bTime: true})" value="<?if (strlen($_REQUEST["HISTORY_VP_STAT"]["DATE_TO"]) > 0) { echo htmlspecialchars($_REQUEST["HISTORY_VP_STAT"]["DATE_TO"]); } else {echo $arResult["DATE_TO"];}?>" class="form-control" id="HISTORY_VP_STAT[DATE_TO]" type="text" name="HISTORY_VP_STAT[DATE_TO]">
                            <span class="glyphicon glyphicon-calendar form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12" style="padding-top: 10px"><a href="<?= $APPLICATION->GetCurDir()?>">Вернуться к списку</a></div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 text-right">
                            <button type="submit" name="HISTORY_VP_STAT[SUBMIT]" value="submit" class="btn btn-primary"><?= GetMessage("FILTER_SUBMIT_TITLE")?></button>
                            <button type="submit" name="HISTORY_VP_STAT[RESET]" value="reset" class="btn btn-primary"><?= GetMessage("FILTER_CLEAR_TITLE")?></button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    
    <div class="white-area">
        <h3>Статистика просмотров</h3>
        <div  id="chart-container" ></div>
    </div>

<script>

!function (dimple) {
    
    var data = [];
    
    <?foreach ($arResult['COUNT']["BY_DATES"] as $date => $arCount):
        $unix = strtotime($date);
        ?>
            data.push({"<?= GetMessage("CHART_X_TITLE")?>": "<?= date('d.m', $unix)?>", "Категория": "Просмотры страницы", "Количество": "<?= $arCount['SHOWS']?>", order: "<?= $unix?>"});
            data.push({"<?= GetMessage("CHART_X_TITLE")?>": "<?= date('d.m', $unix)?>", "Категория": "Бронирование", "Количество": <?= $arCount['QUANTITY_BOOK']?>, order: "<?= $unix?>"});
    <?endforeach?>      

    // Construct chart
    var svg = dimple.newSvg("#chart-container", "100%", 500);

    // Create chart
    // ------------------------------

    // Define chart
    var statChart = new dimple.chart(svg, data);

    // Set bounds
    statChart.setBounds(0, 0, "100%", "100%");

    // Set margins
    statChart.setMargins(60, 25, 0, 50);


    // Create axes
    // ------------------------------

    // Horizontal
    var x = statChart.addCategoryAxis("x", ["<?= GetMessage("CHART_X_TITLE")?>", "Категория"]);
    x.addOrderRule("order", false);
    // Vertical
    var y = statChart.addMeasureAxis("y", "Количество");

    // Construct layout
    // ------------------------------
    
    // Add bars
    statChart.addSeries("Категория", dimple.plot.bar);

    // Add legend
    // ------------------------------

    var legend = statChart.addLegend(0, 5, "100%", 0, "right");


    // Add styles
    // ------------------------------

    // Font size
    x.fontSize = "12";
    y.fontSize = "12";

    // Font family
    x.fontFamily = "Roboto";
    y.fontFamily = "Roboto";

    // Legend font style
    legend.fontSize = "12";
    legend.fontFamily = "Roboto";


    //
    // Draw chart
    //

    // Draw
    statChart.draw();

    // Remove axis titles
    x.titleShape.remove();

    // Position legend text
    legend.shapes.selectAll("text").attr("dy", "1");
   
}(dimple)

</script>