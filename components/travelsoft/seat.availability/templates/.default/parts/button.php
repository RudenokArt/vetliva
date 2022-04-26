<?$is_mobile = check_smartphone();?>
<?if ($arParams['EMTY_RESULT']=="Y"):?>
<div class="seat-availability ts-mt-4">
    <div class="">
        <div class=" text-right mb-10 ">
            <div class="btn btn-primary hidden ts-width-100  ts-d-flex ts-justify-content__space-between js-collapse__seat-availability" id="seat-availability-btn"><?= GetMessage("SEAT_AV_BTN_EMPTY") ?>
            </div>
        </div>
    </div>
    <div class="seat-availability__table 1">
    </div>
</div>
<?else:?>
<div class="seat-availability ts-mt-4">
    <div class="">
        <div class=" text-right mb-10 ">
            <div onclick="Travelsoft.vetliva.utils.__show_hide_seat_availability();Travelsoft.vetliva.utils.__set_title_of_seat_availability_btn();" data-next-title="<?= GetMessage("SEAT_HIDE_AV_BTN") ?>"
                 class="btn btn-primary hidden ts-width-100  ts-d-flex ts-justify-content__space-between js-collapse__seat-availability" id="seat-availability-btn"><?= GetMessage("SEAT_AV_BTN") ?>
            </div>
        </div>
    </div>
    <div class="hidden seat-availability__table 2">
    </div>
</div>
<?endif;?>
<script>


    window.Travelsoft = {
        vetliva: {
            ajax_processing: false,
            seat_availability__table: document.querySelector('.seat-availability__table'),
            seat_availability_btn: document.getElementById('seat-availability-btn'),
            utils: {
                __show_hide_seat_availability: () => {
                    Travelsoft.vetliva.seat_availability__table.classList.toggle('hidden');
                    if(document.querySelector('.table_element')!= null) {
                        $('.table_element').animate({scrollLeft: ($('.active_date').position().left-$('.block_rooms_name').width()-5)}, 500);
                         let firstCol = Array.from(document.querySelectorAll('.table_element .block_rooms_name'));
                         let x = $('.table_element').scrollLeft();
                         firstCol.forEach( item =>{
                             item.style.transform = `translate(${x}px)`;
                         });
					} 
                },
                __set_title_of_seat_availability_btn: function () {
                    var current_title = Travelsoft.vetliva.seat_availability_btn.innerText;
                    var next_title = Travelsoft.vetliva.seat_availability_btn.dataset.nextTitle;
                    Travelsoft.vetliva.seat_availability_btn.dataset.nextTitle = current_title;
                    Travelsoft.vetliva.seat_availability_btn.innerText = next_title;
                    Travelsoft.vetliva.seat_availability_btn.classList.toggle('active');
                },
                get_seat_availability: () => {

                    let data = {sessid: BX.bitrix_sessid()};

                    let seat_availability__date_input = document.querySelector('.seat-availability__date-input');

                    if (Travelsoft.vetliva.ajax_processing) {
                        return;
                    }

                    if (seat_availability__date_input && seat_availability__date_input.value) {
                        data.date_from = seat_availability__date_input.value;
                    }
                    data.EMTY_RESULT = '<?=$arParams['EMTY_RESULT']?>';
                    $.ajax({
                        type: 'post',
                        url: "<?= $templateFolder ?>/ajax.php",
                        data: data,
                        success: function (resp) {
                            Travelsoft.ajax_processing = false;
                            if (resp) {
                                Travelsoft.vetliva.seat_availability__table.innerHTML = resp;
                                Travelsoft.vetliva.seat_availability_btn.classList.remove('hidden');
                                if(document.querySelector('.table_element')!= null) {
                                     let firstCol = Array.from(document.querySelectorAll('.table_element .block_rooms_name'));
                                     let x = $('.table_element').scrollLeft();
                                     firstCol.forEach( item =>{
                                         item.style.transform = `translate(${x}px)`;
                                     });
								} 
                                let interval = setInterval(()=>{
                                    if(document.querySelector('.table_element')!= null){
                                       $('.table_element').scroll(()=>{
                                            let firstCol = Array.from(document.querySelectorAll('.table_element .block_rooms_name'));
                                             //let rowLength = document.querySelector('.rowItem');
                                             let x = $('.table_element').scrollLeft();
                                             firstCol.forEach( item =>{
                                                 item.style.transform = `translate(${x}px)`;
                                             })
                            
                            
                                       })
                                        clearInterval(interval);
                                        $('.table_element').animate({scrollLeft: ($('.active_date').position().left-$('.block_rooms_name').width()-5)}, 500);
                                    }
                                },500);                                
                            }
                            <?if ($is_mobile){?>
                                $(".rooms_name_popup").magnificPopup({
                                    type: "inline",
                                    midClick: true
                                });
                            <?}?>
                            if ($.fn.datepicker) {
                                $('.seat-availability__date-input').datepicker({
                                    dateFormat: "dd.mm.yy"
                                });
                            }

                        }
                    });
                }
            }
        }
    };

    document.addEventListener("DOMContentLoaded", () => {

        Travelsoft.vetliva.utils.get_seat_availability();
    });

    
    let interval = setInterval(()=>{
        console.log(document.querySelector('.table_element'));
        if(document.querySelector('.table_element')!= null){
           $('.table_element').scroll(()=>{
                let firstCol = Array.from(document.querySelectorAll('.table_element .block_rooms_name'));
                 let x = $('.table_element').scrollLeft();
                 firstCol.forEach( item =>{
                     item.style.transform = `translate(${x}px)`;
                 })


           })
            clearInterval(interval);
        }
    },1000);

</script>