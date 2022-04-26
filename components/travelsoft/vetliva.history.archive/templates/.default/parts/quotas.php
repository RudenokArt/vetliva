<table class="table" id="archive-table">
    <thead>
        <tr>
            <th colspan="<?= $colspan ?>">
                <label><b>По дате на которую заведена квота</b>:</label> &nbsp; <input placeholder="введите дату в формате dd.mm.yyyy" type="text" class="archive-sub-filter-field" id="date-filter-field">
            </th>
        </tr>
        <tr>
            <th>Дата записи в архив</th>
            <? if ($show_user_field): ?>
                <th>Пользователь</th>
            <? endif ?>
            <? if ($is_multiple_tour): ?>
                <th>Тариф</th>
            <? endif ?>
            <th>Дата на которую заведена квота</th>
            <? if ($need_value_before_field): ?>
                <th>Значение квоты до изменения</th>
            <? endif ?>
            <th><? if ($need_value_before_field): ?>Значение квоты после изменения<? else: ?>Значение квоты<? endif ?></th>
        </tr>
    </thead>
    <tbody>
        <?
        foreach ($list as $index => $row):
            ?>
            <tr id="row-<?= $index ?>" data-sort="<?= $row["UF_DATE"]->getTimestamp() ?>">
                <td><?= $row["date"] ?></td>
                <? if ($show_user_field): ?>
                    <td><?= $row["user"] ?></td>
                <? endif ?>
                <? if ($is_multiple_tour): ?>
                    <td><?= $row["rate"] ?></td>
                <? endif ?>
                <td><?= $row["on_date"] ?></td>
                <? if ($need_value_before_field): ?>
                    <td><?= $row["value_before"] ?></td>
                <? endif ?>
                <td><?= $row["value"] ?></td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>

<script>

    (function () {

        var table = [];

        var date_filter_field = $("#date-filter-field");

        function filter() {

            var date = date_filter_field.val();

            for (var i = 0; i < table.length; i++) {

                if (
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
                row: $("#row-<?= $index ?>")
            });
<? endforeach; ?>

        $(".archive-sub-filter-field").on("input", function () {
            filter();
        });

    })();

</script>

