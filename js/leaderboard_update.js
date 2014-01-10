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

	jQuery('#loading').show();

	$('#leaderboard a').click(function() {
		var query_string = $(this).next().val();
		//console.log(query_string);
	});

	// Read a page's GET URL variables and return them as an associative array.
	function getUrlVars()
	{
	    var vars = [], hash;
	    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	    for(var i = 0; i < hashes.length; i++)
	    {
	        hash = hashes[i].split('=');
	        vars.push(hash[0]);
	        vars[hash[0]] = hash[1];
	    }
	    return vars;
	}

	console.log(getUrlVars()['sort']);
	



	$('#leaderboard-update').load('../leaderboard_score.php?sort=experience&desc=false', function() {
		console.log('update');

		var userInfo = $('td.user-info');
		userInfo.hide();

		$('tr.user-details').hover(function() {
			//console.log($(this).find('tr'));
			$(this).find(userInfo).show();
		}, function() {
			$(this).find(userInfo).hide();
		});

		var tooltip = $('.user-info');

		$('#leaderboard').mousemove(function(e) {

		tooltip.css('top', e.pageY + 20).css('left', e.pageX + 20)

		});
	
	});
	//});
	
	jQuery('#loading').hide();

	console.log('leaderboard update');

	//console.log('test');
	$('table#leaderboard').hover(function() {
		console.log($(this));
	});

	}, 5000);

	//jQuery('#loading').hide();

});
