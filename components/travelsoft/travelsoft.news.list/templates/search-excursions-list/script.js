 function initSelectDates(element) {
        var  $this = element, alloweddatestrue  =[], countday = $this.data('countday'), link = $this.data('link'), id = $this.data('id'),  alloweddates = $this.data('dates'), input = $this.siblings('input');
        alloweddates.forEach(element => alloweddatestrue.push( moment(element, 'X').format('DD.MM.YYYY')));
        input.datepicker({
            beforeShowDay: function (date) {
                if (countday >1) {
                    showdate = false;
                    datetmp = moment(date).format('X');
                    var mindates = [], maxdates = [];
                    alloweddates.forEach(function(item, i, arr) {
                        minDate = item;
                        maxDate = moment(minDate, 'X').add(countday,'days').format('X');
                        mindates.push(minDate); maxdates.push((maxDate-86400));
                        if (datetmp >= minDate && datetmp < maxDate)  showdate = true;
                        
                    });
                    
                    
                    if (showdate) {
                        datetmp = parseInt(datetmp);
                        if (mindates.includes(datetmp)) return [true, ' ui-datepicker-current-day green-day', ''];
                        else if (maxdates.includes(datetmp)) return [true, ' light-date-end green-day', ''];
                        else return [true, 'light-date', ''];
                    } 
                    else return [false, '', ''];
                }
                else {
                    datetmp = moment(date).format('DD.MM.YYYY');
                    if(jQuery.inArray(datetmp, alloweddatestrue) != -1) {
                        return [true, 'light-date green-day', ''];
                    }
                    else return [false, '', ''];
                }
            },
            onSelect: function (string, object) {
                if (countday >1) {
                    datetmp = moment(string, 'DD.MM.YYYY').format('X');
                    alloweddates.forEach(function(item, i, arr) {
                        minDate = item;
                        maxDate = moment(minDate, 'X').add(countday,'days').format('X');
                        if (datetmp >= minDate && datetmp < maxDate) {
                            $('#form-excursionstours [name="booking[date_to]"]').val(maxDate);
                            $('#form-excursionstours [name="booking[date_from]"]').val(minDate);
                            $('#form-excursionstours [name="booking[id][0]"]').val(id);
                            $('#form-excursionstours form').attr('action', link);
                            $('#form-excursionstours form').submit(); 
                        }
                    });
                }
                else {
                    $('#form-tours [name="booking[date_to]"]').val(moment(string, 'DD.MM.YYYY').format('X'));
                    $('#form-tours [name="booking[date_from]"]').val(moment(string, 'DD.MM.YYYY').format('X'));
                    $('#form-tours [name="booking[id][0]"]').val(id);
                    $('#form-tours form').attr('action', link);
                    $('#form-tours form').submit();  
                }
            },
            minDate:alloweddatestrue[0],
            maxDate:moment(alloweddatestrue[0], 'DD.MM.YYYY').add(1,'year').format('DD.MM.YYYY'),
            dateFormat: 'dd.mm.yy',
            numberOfMonths: window.innerWidth < 640 ? 1 : 2,
            allowedDates :alloweddatestrue
           });
     
           input.datepicker('show');
    }