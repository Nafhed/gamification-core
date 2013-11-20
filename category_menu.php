<?php

	$category_menu = $g->get_categories();

	//echo 'category menu ' . print_r($category_menu, true);

	echo "<div id='category_menu'";
		echo "<ul id='category-navigation' class='menu'>"; 
			foreach($category_menu as $cat_menu) {
				echo "<li id='cat-" . $cat_menu['category_id'] . "' class='category_item'>" . $cat_menu['category_name'] . "</li>";
			}
		echo "</ul>";
	echo "</div>";

?>