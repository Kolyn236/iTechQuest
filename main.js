$(function() {
	$('#feedback-form').submit(function (e) {
		e.preventDefault();
		$.ajax({
			url: 'index.php',
			method: 'post',
			data: 	$(this).serialize(),
			success: function (data) {
				console.log('data',data);
				var htmlstr = '';
				data = JSON.parse(data);
				for (var i=0; i<data.length; i++) {
					htmlstr += '<tr>';
					htmlstr += '<td>' + data[i].ID +'</td>';
					htmlstr += '<td>' + data[i].TITLE +'</td>';
					htmlstr += '<td align="center">';
					htmlstr += '<a href="index.php?delete='+ data[i].ID +'"><i class="fa fa-times" aria-hidden="true"></i></a>';
					htmlstr += '</td></tr>';
				}
				$('#requests').html(htmlstr);
			}
		});
	})
});