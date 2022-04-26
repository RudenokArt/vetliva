<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
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
if (!$arResult["ITEMS"]) {
    return false;
}

\Bitrix\Main\Page\Asset::getInstance()->addString('<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/4.8.0/d3.min.js"></script>');
\Bitrix\Main\Page\Asset::getInstance()->addString('<script src="https://cdnjs.cloudflare.com/ajax/libs/dimple/2.3.0/dimple.latest.min.js"></script>');

$items_id = \array_values(\array_column($arResult["ITEMS"], "ID"));

$hash = md5(serialize($items_id));
?>

<div class="panel panel-flat">
    <div class="panel-body">
        <h4><?= $arParams["TITLE"] ?></h4>
        <div id="detail-statistic-info"></div>
        <div id="chart-container"></div>
        <img id="replace-ajax-request-<?= $hash ?>" width="100" height="100" src="<?= $templateFolder ?>/preloader.gif">
    </div>
</div>

<script>
    $(document).ready(function () {

        var items_id = <?= \json_encode($items_id); ?>;

        function drawChart(result) {
            var data = [];
            var unix = 0;
            var d_m = "";
            for (var date in result.COUNT.BY_DATES) {
                if (result.COUNT.BY_DATES.hasOwnProperty(date)) {
                    unix = result.DATES_FORMATES[date].UNIX;
                    d_m = result.DATES_FORMATES[date].d_m;
                    data.push({"Дата": d_m, "Категория": "Просмотры страницы", "Количество": result.COUNT.BY_DATES[date].SHOWS, order: unix});
                    data.push({"Дата": d_m, "Категория": "Бронирование", "Количество": result.COUNT.BY_DATES[date].QUANTITY_BOOK, order: unix});
                }
            }

            // Construct chart
            var svg = dimple.newSvg("#chart-container", "100%", 500);

            // Create chart
            // ------------------------------

            // Define chart
            var statChart = new dimple.chart(svg, data);
            
            statChart.addLogAxis("y", "Количество", 10);
            
            // Set bounds
            statChart.setBounds(0, 0, "100%", "100%");

            // Set margins
            statChart.setMargins(60, 25, 0, 50);


            // Create axes
            // ------------------------------

            // Horizontal
            var x = statChart.addCategoryAxis("x", ["Дата", "Категория"]);
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
        }

        $.post("<?= $templateFolder ?>/ajax.php", {items_id: items_id, sessid: "<?= bitrix_sessid() ?>", hash: "<?= $hash ?>"}, function (resp) {
            if (!resp.error) {
                if (typeof resp.content === "string" && resp.content.length) {
                    $("#replace-ajax-request-<?= $hash ?>").replaceWith(resp.content);
                }
                if (resp.data && typeof resp.data === "object") {
                    drawChart(resp.data);
                }
                if (resp.detail_info && typeof resp.detail_info === "string") {
                    $("#detail-statistic-info").append(resp.detail_info);
                }
                return;
            }
            $("#replace-ajax-request-<?= $hash ?>").replaceWith(`<?= ShowError("По данным статистики ничего не найдено") ?>`);

        });

    });
</script>
