<template id="devices-template">
    <div class="pie-stat-block">
        <h3>Статистика устройств посещающих</h3>
        <img v-if="!this.data && !this.error" src="<?= $templateFolder ?>/preloader.gif" width="100" height="100" id="replace-chart">

        <div v-if="this.data" id="devices-pie-container" >

        </div>
        <div v-if="this.error" class="error">
            <?= ShowError("Нет данных по статистике.") ?>
        </div>
    </div>
</template>
<script>
    BX.Vue.component('devices', {
        template: document.getElementById('devices-template').innerHTML,
        data: function () {
            return {
                data: null,
                error: null
            };
        },
        created: function () {
            vetliva_history_view_page_stat_utils.send_request('<?= $templateFolder ?>/ajax/devices.php', (resp) => {

                if (!resp.error && !(BX.type.isArray(resp) && !resp.length)) {
                    this.data = resp;
                    return;
                }
                this.error = true;
            });
        },
        updated: function () {
            
            let pie_data = [];
            for (let key in this.data) {
                if (this.data.hasOwnProperty(key)) {
                    pie_data.push({"Устройство": key, "Процент": this.data[key].percent});
                }
            }
            vetliva_history_view_page_stat_utils.draw_pie_chart(pie_data, "#devices-pie-container", "Устройство", "Процент");
        }
    });
</script>