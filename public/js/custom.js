//Open safari webapp urls in app instead of browser
(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone");

//Custom functions
$(function() {
	//Sockets
	$('#sockets button.btn').click(function() {
		var buttonInfo = $(this).attr('id').split('_');
		$.ajax({
			url: ajaxUrl,
			data: { mode: 'socket', socket: buttonInfo[1], status: buttonInfo[2] },
			cache: false,
			dataType: 'json'
		}).done(function(data) {
			if(data.success == true) {
				$('#socket_' + buttonInfo[1] + '_off').removeClass('btn-primary').addClass('btn-default');
				$('#socket_' + buttonInfo[1] + '_on').removeClass('btn-primary').addClass('btn-default');
				$('#socket_' + buttonInfo[1] + '_' + buttonInfo[2]).removeClass('btn-default').addClass('btn-primary');
			} else {
				BootstrapDialog.show({
					type: BootstrapDialog.TYPE_WARNING,
					title: lang.error,
					message: lang.errormessage,
				}); 
			}			
		}).fail(function() {
            BootstrapDialog.show({
                type: BootstrapDialog.TYPE_WARNING,
                title: lang.error,
                message: lang.httperrormessage,
            });     
		});
	});
});
