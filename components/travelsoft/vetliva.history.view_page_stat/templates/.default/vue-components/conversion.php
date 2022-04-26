<template id="conversion-template">
    <div class="conversion-stat-block">
        <h3>Статистика просмотров</h3>
        <img v-if="!this.data && !this.error" src="<?= $templateFolder ?>/preloader.gif" width="100" height="100" id="replace-chart">
        <div v-if="this.data" id="detail-statistic-info">
            <div calss="total-shows-block">
                Просмотры Вашей страницы<br>
                {{parseInt(data.COUNT.TOTAL.SHOWS)}}
            </div>
            <div calss="total-booking-block">
                Бронирования<br>
                {{parseInt(data.COUNT.TOTAL.QUANTITY_BOOK)}}
            </div>
            <div class="arrow-block">&rarr;</div>
            <div calss="conversion-block">
                Конверсия<br>
                {{data.CONVERSION}}%
            </div>
        </div>
        <div v-if="this.data" id="chart-container" >

        </div>
        <div v-if="this.error" class="error">
            <?= ShowError("Нет данных по статистике.") ?>
        </div>
    </div>
</template>
<style>
    #detail-statistic-info div {
        box-shadow: 0 0 10px rgba(0,0,0,0.5);
        display: inline-block;
        margin: 15px;
        padding: 20px;
        font-size: 18px;
        text-align: center;
        font-weight: bold;
    }

    .arrow-block {
        font-size: 50px !important;
        padding: 5px !important;
        box-shadow: none !important;
    }
</style>
<script>


    BX.Vue.component("conversion", {
        created: function () {
            vetliva_history_view_page_stat_utils.send_request('<?= $templateFolder ?>/ajax/conversion.php', (resp) => {

                if (!resp.error && !(BX.type.isArray(resp) && !resp.length)) {
                    this.data = resp;
                    return;
                }
                this.error = true;
            });
        },
        updated: function () {
            vetliva_history_view_page_stat_utils.draw_chart({
                COUNT: this.data.COUNT,
                DATES_FORMATES: this.data.DATES_FORMATES,
                DATE_FROM: this.data.DATE_FROM,
                DATE_TO: this.data.DATE_TO,
                ITEMS: this.data.ITEMS
            }, "<?= GetMessage("CHART_X_TITLE") ?>", "#chart-container");
        },
        template: document.getElementById('conversion-template').innerHTML,
        data: function () {
            return {
                error: null,
                data: null
            };
        }
    });

</script>