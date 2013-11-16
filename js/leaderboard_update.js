$(document).ready(function($) {

	/*$.ajax({ 
		url: "leaderboard_score.php",
		cache: false,
		success: function(html) {
			$("#leaderboard-update").append(html);
		}

	});*/

	//show leaderboard first.
	setInterval(function() {

	$('#leaderboard-update').load('leaderboard_score.php', function() {
		console.log('update');

		var userInfo = $('td.user-info');
		userInfo.hide();

		$('tr.user-details').hover(function() {
			console.log($(this).find('tr'));
			$(this).find(userInfo).show();
		}, function() {
				$(this).find(userInfo).hide();
		});

		var tooltip = $('.user-info');

		$('#leaderboard').mousemove(function(e) {

		tooltip.css('top', e.pageY + 20).css('left', e.pageX + 20)

		});
	
	}); 
	
	console.log('leaderboard update');

	//console.log('test');
	$('table#leaderboard').hover(function() {
		console.log($(this));
	});

	}, 15000);

});
