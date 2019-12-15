$(function() {
	
	$('input[type=name]').unbind().blur( function() {
		
		if($(this).val() === '') {
		
			$('.error-box').css('color','red')
							.css('display', 'block')
                            .animate({'paddingLeft':'10px'},400)
                            .animate({'paddingLeft':'5px'},400);
		} else {
			
			$('.error-box').css('display','none')
			
		}
		
	});
	
	$('#feedback-form').submit(function (e) {
		e.preventDefault();
		$.ajax({
			url: 'index.php',
			method: 'post',
			data: 	$(this).serialize(),
			success: function (data) {
				data = JSON.parse(data);
				updateList(data);
				$('#message').html('Спасибо за обращение, Ваш номер в очереди : ' + data.length )
				.fadeIn('slow')
				.delay(2000)
				.fadeOut('slow');
			}
		});
	});
});

function deleteLead(id) {
	$.ajax({
		url: 'index.php',
		method: 'post',
		data: {'id': id},
		success: function (data) {
			data = JSON.parse(data);
			updateList(data);
		}
	})
}

function updateList(data) {
	var htmlstr = '';
	for (var i=0; i<data.length; i++) {
		htmlstr += '<tr>';
		htmlstr += '<td>' + data[i].ID +'</td>';
		htmlstr += '<td>' + data[i].TITLE +'</td>';
		htmlstr += '<td align="center">';
		htmlstr += '<span class="delete_lead" onclick="javascript:deleteLead(' + data[i].ID + ')"><i class="fa fa-times" aria-hidden="true"></i></span>';
		htmlstr += '</td></tr>';
	}
	$('#requests').html(htmlstr);
	$('#countLeads').html(data.length);
}