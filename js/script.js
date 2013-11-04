jQuery(document).ready(function($) {
	//login box id
	var loginBox = $('#userForm form');
	loginBox.hide();

	function openLogin() {

		loginBox.fadeIn(600);

	}

	$('#openLogin').click(openLogin);

});