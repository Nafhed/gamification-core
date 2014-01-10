jQuery(document).ready(function($) {
	//login box id
	var loginBox = $('#userForm form');
	loginBox.hide();

	function openLogin() {
		loginBox.fadeToggle(600);
	}

	$('#openLogin').click(openLogin);

	$(document).mouseup(function (e)
		{
		    var container = $(loginBox);

		    if (!container.is(e.target) // if the target of the click isn't the container...
		        && container.has(e.target).length === 0) // ... nor a descendant of the container
		    {
		        container.hide();
		    }
	});

	$('#playerActive a#player').click(function() {
		$(this).next('ul#user-links').animate({left: '10'});
	});

});