<template id="age-template">
    <div class="pie-stat-block">
        <h3>Статистика возраста посещающих</h3>
        <img v-if="!this.data && !this.error" src="<?= $templateFolder ?>/preloader.gif" width="100" height="100" id="replace-chart">

        <div v-if="this.data" id="age-pie-container" >

        </div>
        <div v-if="this.error" class="error">
            <?= ShowError("Нет данных по статистике.") ?>
        </div>
    </div>
</template>
<script>
    BX.Vue.component('age', {
        template: document.getElementById('age-template').innerHTML,
        data: function () {
            return {
                data: null,
                error: null
            };
        },
        created: function () {
            vetliva_history_view_page_stat_utils.send_request('<?= $templateFolder ?>/ajax/age.php', (resp) => {

                if (!resp.error && !(BX.type.isArray(resp) && !resp.length)) {
                    this.data = resp;
                    return;
                }
                this.error = true;
            });
        },
        updated: function () {
            
            let pie_data = [];
            let title = '';
            for (let key in this.data) {
                if (this.data.hasOwnProperty(key)) {
                    if (key === "<18") {
                        title = "Менее 18 лет";
                    } else if (key === "18-24") {
                        title = "18‑24 лет";
                    } else if (key === "25-34") {
                        title = "25‑34 лет";
                    } else if (key === "35-45") {
                        title = "35‑45 лет";
                    } else if (key === "45<") {
                        title = "Более 45 лет"; 
                    }
                    pie_data.push({"Возраст": title, "Процент": this.data[key]});
                }
            }
            vetliva_history_view_page_stat_utils.draw_pie_chart(pie_data, "#age-pie-container", "Возраст", "Процент");
        }
    });
</script>