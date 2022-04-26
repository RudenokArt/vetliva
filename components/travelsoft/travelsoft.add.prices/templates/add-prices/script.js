$( document ).ready(function() {
    $(".rate-title").on("click" , function (e) {
        var tr = $(this).closest('.rateblock-header');
        if (tr.hasClass('open')) {

			  $("table").find("[data-ratesign='" + tr.data('ratesign') + "'][data-currency='" + tr.data('currency') + "']").css('display', '');
              $("table").find("[data-ratesign='" + tr.data('ratesign') + "'][data-currency='" + tr.data('currency' ) + "']").removeClass('open');

			tr.removeClass('open');
			tr.next('.scroll-tr').removeClass('ccode');
        }

        else {

  			 $("table").find("[data-ratesign='" + tr.data('ratesign') + "'][data-currency='" + tr.data('currency') + "']").css('display', '');
             $("table").find("[data-ratesign='" + tr.data('ratesign') + "'][data-currency='" + tr.data('currency') + "']").addClass('open');

			tr.addClass('open');
			tr.next('.scroll-tr').addClass('ccode');
			
		
        }
    });
    $('.daterange-filtr').on('apply.daterangepicker', function(ev, picker) {
      document.monthSelectForm.submit();
    });
	

   

	//$('.rateblock-header').not('.open').parents().addClass('code');

			 

	

});
function applyfiltr() {
    var ratesign = $('#selectRate').val(), currency = $('#selectCurrency').val();
    $('.rateblock-header').hide();
    $('.rateblock-row').hide();
    if (ratesign!='' && currency!='') {
        $("table").find(".rateblock-header[data-ratesign='" + ratesign + "'][data-currency='" + currency + "']").show();
        $("table").find(".rateblock-header[data-ratesign='" + ratesign + "'][data-currency='" + currency + "']").first().find('.rate-title').click();
    } 
    else if (ratesign!='') {
        $("table").find(".rateblock-header[data-ratesign='" + ratesign + "']").show();
        $("table").find(".rateblock-header[data-ratesign='" + ratesign + "']").first().find('.rate-title').click();
    }
    else if (currency!='') {
        $("table").find(".rateblock-header[data-currency='" + currency + "']").show();
        $("table").find(".rateblock-header[data-currency='" + currency + "']").first().find('.rate-title').click();
    }
    else {
        $('.rateblock-header').show();
        $('.rateblock-row').hide();
    }
}
function resetfiltr() {
    $('form#serviceSelectFormFiltr select').prop('selectedIndex', 0);
    $('form#serviceSelectFormFiltr select').change();
}