<template id="male-template">
    <div class="pie-stat-block">
        <h3>Статистика пола посещающих</h3>
        <img v-if="!this.data && !this.error" src="<?= $templateFolder ?>/preloader.gif" width="100" height="100" id="replace-chart">

        <div v-if="this.data" id="male-pie-container" >

        </div>
        <div v-if="this.error" class="error">
            <?= ShowError("Нет данных по статистике.") ?>
        </div>
    </div>
</template>
<script>
    BX.Vue.component('male', {
        template: document.getElementById('male-template').innerHTML,
        data: function () {
            return {
                data: null,
                error: null
            };
        },
        created: function () {
            vetliva_history_view_page_stat_utils.send_request('<?= $templateFolder ?>/ajax/male.php', (resp) => {

                if (!resp.error && !(BX.type.isArray(resp) && !resp.length)) {
                    this.data = resp;
                    return;
                }
                this.error = true;
            });
        },
        updated: function () {

            let pie_data = [];
            pie_data.push({"Пол": "Мужчины", "Процент": this.data["man"]});
            pie_data.push({"Пол": "Женщины", "Процент": this.data["women"]});
            vetliva_history_view_page_stat_utils.draw_pie_chart(pie_data, "#male-pie-container", "Пол", "Процент");
        }
    });
</script>