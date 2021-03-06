/* some common functions */
function jsRedirect(re_url, time){
	if(typeof re_url == 'undefined')
		re_url = BASE_URL;
	
	if(typeof time == 'undefined')
		time = 300;

	setTimeout(function(){ window.location.href = re_url; }, time);
}

function jsEmpty(devName, time){
	
	if(typeof time == 'undefined')
		time = 800;
	
	$( "div.error" ).each(function( index ) {
	  $( this ).html('');
	});
	
	if(typeof devName != 'undefined')
		setTimeout(function(){ $('#'+devName).fadeOut(); $('#'+devName).html(''); $('#'+devName).fadeIn();}, time);
	
}

/* toastr option init */
toastr.options.onHidden = function() {toastr.clear();}
toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-top-center",
  "preventDuplicates": false,
  //"onclick": null,
  //"showDuration": 300,
  //"hideDuration": 1000,
  "fadeOut": 1000,
  "timeOut": 3000,
  //"extendedTimeOut": 1000,
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut",
  "maxOpened": 1,
};

function bind_error(getFormId,getErrors){
	getForm = $('#'+getFormId);
	var firstField;
	$(getForm).find(".error").remove();
	if(typeof getForm != 'undefined' && !$.isEmptyObject(getErrors)) { 
		$.each(getErrors, function(key,errorMsgs) { 
			if(typeof firstField === 'undefined'){
				firstField = key; 
			}			
			$(errorMsgs).each(function(k,errorMsg) { 
				$(getForm).find('[name='+key+']').after('<div id="'+key+'-error'+k+'" class="error">'+errorMsg+'</div>');
			});
		});
		checkData = $(getForm).find('[name='+firstField+']');
		if(typeof checkData != 'undefined' && checkData.length > 0){
			setTimeout(function(){ 
				$('html, body').animate({
					scrollTop: $(getForm).find('[name='+firstField+']').offset().top
				}, 1000);
			}
			, 500);
		}
	}
}

var ajaxLoader = $(".ajax_loader_icon_outer");
var pageLoader = $('.full_page_loader');