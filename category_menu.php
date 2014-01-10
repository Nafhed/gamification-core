<?php

	$category_menu = $g->get_categories();

	//echo 'category menu ' . print_r($category_menu, true);
	echo "<aside id='sidebar_menu'>";
		echo "<div id='category_menu'>";
			echo "<ul id='category-navigation' class='menu'>"; 
				foreach($category_menu as $cat_menu) {
					//print_r($cat_menu);
					echo "<li id='cat-" . $cat_menu['category_id'] . "' class='category_item'><a href='" . BASE_URL . "posts/category/" . $cat_menu['category_slug'] . "'>" . $cat_menu['category_name'] . "</a></li>";
				}
			echo "</ul>";
		echo "</div>";
	echo "</aside>";

?>