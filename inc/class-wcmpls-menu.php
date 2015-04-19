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

		// trans start
		// never leave the $_GET['dev'] check in on production
		if ( ! empty( $_GET['dev'] ) || false === ( $post_ids = get_transient( 'menu_post_ids' ) ) ) {

			$post_ids = array();

			foreach ( $sub_menu_items as $sub_item) {

				if ( array_key_exists( 'post_type_post', $sub_item) ) {
					foreach ( $sub_item['post_type_post'] as $id ) {
						array_push( $post_ids, $id );
					}
				}

				if ( array_key_exists( 'categories', $sub_item) ) {
					$posts = get_posts( array(
						'suppress_filters' => false,
						'post_type' => 'post',
						'post_status' => 'publish',
						'category__in' => $sub_item['categories'],
						'fields' => 'ids',
					) );
					foreach ( $posts as $id ) {
						array_push( $post_ids, $id );
					}
				}

				if ( array_key_exists( 'tags', $sub_item) ) {
					$posts = get_posts( array(
						'suppress_filters' => false,
						'post_type' => 'post',
						'post_status' => 'publish',
						'tag__in' => $sub_item['tags'],
						'fields' => 'ids',
					) );
					foreach ( $posts as $id ) {
						array_push( $post_ids, $id );
					}
				}

			} // end foreach submenu items
		}
		//trans end
		return array_unique( $post_ids );

	} // end function

	public static function nav_menu_output( $location ) {

		$menu_items = self::nav_menu( $location );

		if ( ! empty( $menu_items ) ) : ?>
			<ul>
				<?php foreach ( $menu_items as $item ) :
					if ( empty( $item->sub_items ) ) : ?>
						// no children - really just a fallback
						<li>
							<a href="<?php echo esc_url( get_permalink( $item->ID ) ); ?>">
								<?php echo esc_html( $item->title ); ?>
							</a>
						</li>
					<?php else : // item with children - primary output ?>
						<li>
							<a href="<?php echo esc_url( get_permalink( $item->ID ) ); ?>">
								<?php echo esc_html( $item->title ); ?>
							</a>
							<ul>
								<span class="menu-items-wrapper">
									<?php
									$sub_menu_posts = self::getting_menu_items( $item->sub_items );
									$total_count = count( $sub_menu_posts );
									$count = 1;
									foreach ( $sub_menu_posts as $post_id ) : ?>
										<li>
											<?php
											if ( has_post_thumbnail( $post_id ) ) :
												$thumb_id = get_post_thumbnail_id( $post_id );
												$thumb = wp_get_attachment_image_src( $thumb_id ); ?>
												<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
													<img src="<?php echo esc_url( $thumb[0] ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>" />
												</a>
											<?php endif; ?>
											<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a>
										</li>
										<?php if ( $total_count-- >= 3 && $count++ %3 === 0 ) : ?>
											</span><span class="menu-items-wrapper">
										<?php endif; ?>
									<?php endforeach; ?>
								</span>
							</ul>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php endif; // ending menu container

	} // end function

} // END class

new WCMPLS_Menu();
