<table class="table" id="archive-table">
    <thead>
        <tr>
            <th colspan="<?= $colspan?>">
                <label><b>По тарифу</b>:</label> &nbsp; <input placeholder="введите название тарифа" type="text"  class="archive-sub-filter-field" id="rate-filter-field" > &nbsp;
                
                <label><b>По дате на которую заведена цена</b>:</label> &nbsp; <input placeholder="введите дату в формате dd.mm.yyyy" type="text" class="archive-sub-filter-field" id="date-filter-field">
            </th>
        </tr>
        <tr>
            <th>Дата изменения</th>
            <th>Тариф</th>
            <?if ($show_user_field):?>
            <th>Пользователь</th>
            <?endif?>
            <th>Дата на которую заведена цена</th>
            <?if($need_value_before_field):?>
            <th>Значение цены до изменения</th>
            <?endif?>
            <th><?if($need_value_before_field):?>Значение цены после изменения<?else:?>Значение цены<?endif?></th>
        </tr>
    </thead>
    <tbody>
        <?
        foreach ($list as $index => $row):
            ?>
            <tr id="row-<?= $index ?>" data-sort="<?= $row["UF_DATE"]->getTimestamp() ?>">
                <td><?= $row["date"] ?></td>
                <td><?= $row["rate"] ?></td>
                <?if ($show_user_field):?>
                <td><?= $row["user"] ?></td>
                <?endif?>
                <td><?= $row["on_date"] ?></td>
                <?if($need_value_before_field):?>
                <td><?= $row["value_before"] ?></td>
                <?endif?>
                <td><?= $row["value"] ?></td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>

<script>

    (function () {

        var table = [];
        
        var rate_filter_field = $("#rate-filter-field");
        var ptype_filter_field = $("#ptype-filter-field");
        var date_filter_field = $("#date-filter-field");
        
        function filter() {
            
            var rate_name = rate_filter_field.val();
            
            var ptype_name = ptype_filter_field.val();
            
            var date = date_filter_field.val();
            
            for (var i = 0; i < table.length; i++) {
                
                if (
                    
                    (table[i].rate.toLowerCase().indexOf(rate_name.toLowerCase()) !== -1 || !rate_name) &&
                    (table[i].ptype.toLowerCase().indexOf(ptype_name.toLowerCase()) !== -1 || !ptype_name) &&
                    (table[i].date.toLowerCase().indexOf(date.toLowerCase()) !== -1 || !date)
                    
                ) {
                    
                    table[i].row.show();
                } else {
                    
                    table[i].row.hide();
                }
                

            }
            
        }
        

<?
foreach ($list as $index => $row):
    ?>
            table.push({
                sort: <?= $row["UF_DATE"]->getTimestamp() ?>,
                date: "<?= $row["on_date"] ?>",
                rate: "<?= $row["rate"] ?>",
                ptype: "<?= $row["ptype"] ?>",
                row: $("#row-<?= $index ?>")
            });
<? endforeach; ?>

        $(".archive-sub-filter-field").on("input", function () {
            filter();
        });

    })();

</script>

