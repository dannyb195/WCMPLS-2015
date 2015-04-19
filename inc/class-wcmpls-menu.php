<?php
/**
 * undocumented class
 *
 * @package default
 * @author
 **/
class WCMPLS_Menu {

	public static function get_nav_menu( $location ) {

		$menus = get_nav_menu_locations();

		// getting menu items by theme location
		if ( ! empty( $menus[ $location ] ) ) {
			return wp_get_nav_menu_items( $menus[ $location ] );

		}

	}

	public static function nav_menu( $location ) {
		$menu_top_level = self::get_nav_menu( $location );
		if ( ! empty( $menu_top_level ) ) {
			foreach ( $menu_top_level as $menu_item ) {
				// setting each menu item's posts meta as a property of the menu object
				$menu_item->sub_items = get_post_meta( $menu_item->object_id, 'section_options', true );
			}
		}
		return $menu_top_level;
	}

	public static function getting_menu_items( $sub_menu_items ) {
		echo '<pre>';
		print_r($sub_menu_items);
		echo '</pre>';

		foreach ( $sub_menu_items as $sub_item) {
			if ( array_key_exists( 'post_type_post', $sub_item) ) {
				echo 'we have posts';
			}

			if ( array_key_exists( 'categories', $sub_item) ) {
				echo 'we have categories';
			}

			if ( array_key_exists( 'tags', $sub_item) ) {
				echo 'we have post tags';
			}

		}


	}

	public static function nav_menu_output( $location ) {
		$menu_items = self::nav_menu( $location );

		if ( ! empty( $menu_items ) ) {
			echo '<ul>';
				foreach ( $menu_items as $item ) {
					// echo '<pre>';
					// print_r($item);
					// echo '</pre>';
					if ( empty( $item->sub_items ) ) {
						// no children
						echo '<li><a href="#">' . esc_html( $item->title ) . '</a></li>';
					} else {
						// item with children
						echo '<li>';
							echo '<a href="#">';
								echo esc_html( $item->title );
							echo '</a>';
							echo '<ul>';
								$sub_menu = self::getting_menu_items( $item->sub_items );
							echo '</ul>';
						echo '</li>';
					}
				}
			echo '</ul>'; // ending menu container
		}
	}

} // END class

new WCMPLS_Menu();