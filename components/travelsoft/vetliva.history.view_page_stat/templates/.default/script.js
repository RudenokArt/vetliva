
window.vetliva_history_view_page_stat_utils = {

    send_request: (url, success) => {

        BX.ajax({
            url: url,
            method: 'post',
            dataType: 'json',
            data: BX.ajax.prepareData(BX.ajax.prepareForm(document.getElementById('view-page-history-filter')).data),
            onsuccess: success
        });
    },
    draw_chart: (result, chart_x_title, chart_selector) => {
        var data = [];
        var data_item = {};
        var unix = 0;
        var d_m = "";
        for (var date in result.COUNT.BY_DATES) {
            if (result.COUNT.BY_DATES.hasOwnProperty(date)) {
                unix = result.DATES_FORMATES[date].UNIX;
                d_m = result.DATES_FORMATES[date].d_m;
                data_item = {};
                data_item[chart_x_title] = d_m;
                data_item["Категория"] = "Просмотры страницы";
                data_item["Количество"] = result.COUNT.BY_DATES[date].SHOWS;
                data_item.order = unix;
                data.push(data_item);
                data_item = {};
                data_item[chart_x_title] = d_m;
                data_item["Категория"] = "Бронирование";
                data_item["Количество"] = result.COUNT.BY_DATES[date].QUANTITY_BOOK;
                data_item.order = unix;
                data.push(data_item);
            }
        }

        // Construct chart
        var svg = dimple.newSvg(chart_selector, "100%", 500);
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
        var x = statChart.addCategoryAxis("x", [chart_x_title, "Категория"]);
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
    },
    draw_pie_chart: (data, pie_selector, x_title, y_title) => {
        
        let svg = dimple.newSvg(pie_selector, "100%", 400);

        const pie = new dimple.chart(svg, data);
        
        pie.setBounds(20, 20, 460, 360);
        pie.addMeasureAxis("p", y_title);
        pie.addSeries(x_title, dimple.plot.pie);
        pie.addLegend(500, 20, 90, 300, "left");

        pie.draw();
    }
};