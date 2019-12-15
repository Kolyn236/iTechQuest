$(function() {
	$('#feedback-form').submit(function (e) {
		e.preventDefault();
		$.ajax({
			url: 'index.php',
			method: 'post',
			data: 	$(this).serialize(),
			success: function (data) {
				// console.log(data);
				data = JSON.parse(data);
				updateList(data);
			}
		});
	});
});

function delete_lead(id) {
	$.ajax({
		url: 'index.php',
		method: 'post',
		data: {'id': id},
		success: function (data) {
			// console.log('data',data);
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
		htmlstr += '<span class="delete_lead" onclick="javascript:delete_lead(' + data[i].ID + ')"><i class="fa fa-times" aria-hidden="true"></i></span>';
		htmlstr += '</td></tr>';
	}
	$('#requests').html(htmlstr);
	$('#countLeads').html(data.length);
}