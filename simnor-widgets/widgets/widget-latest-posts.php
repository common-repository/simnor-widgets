<?php

/* Latest Posts List Widget */

class sn_simnor_widgets_latest_posts_widget extends WP_Widget {

	public function __construct() {
		global $sn_simnor_widgets_name;
		parent::__construct(
	 		'sn_simnor_widgets_latest_posts_widget',
			$sn_simnor_widgets_name . __( ': Latest Posts', 'snplugin' ), 
			array( 'description' => __( 'List your latest posts', 'snplugin' ) )
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'sn_simnor_widgets_latest_posts_widget', $instance['title'] );
		$latest_posts_category = apply_filters( 'sn_simnor_widgets_latest_posts_widget', $instance['latest_posts_category'] );
		$latest_posts_count = apply_filters( 'sn_simnor_widgets_latest_posts_widget', $instance['latest_posts_count'] );
		if($latest_posts_count == "") { $latest_posts_count = 3; }
		
		echo $before_widget;
		if(!empty($title)) { echo $before_title . $title . $after_title; }
				
		echo '<ul class="sn-simnor-widgets-ul sn-simnor-widgets-latest-posts">';

		if($latest_posts_category) {
			$posts = get_posts('posts_per_page='.$latest_posts_count.'&category='.$latest_posts_category);
		} else {
			$posts = get_posts('posts_per_page='.$latest_posts_count);
		}
		
		foreach($posts as $post) {
			
			if(has_post_thumbnail($post->ID)) {
				echo '<li class="has-thumbnail">';
				echo get_the_post_thumbnail($post->ID, 'thumbnail');
			} else {
				echo '<li>';
			}
			
			echo '<span class="latest-post-date">'.date(get_option('date_format'), strtotime($post->post_date)).'</span>
				<a class="latest-post-title" href="'.get_permalink($post->ID).'">'.$post->post_title.'</a>
			</li>';
			
		}
		
		echo '</ul>';
		
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['latest_posts_category'] = strip_tags( $new_instance['latest_posts_category'] );
		$instance['latest_posts_count'] = strip_tags( $new_instance['latest_posts_count'] );
		return $instance;
	}

	public function form( $instance ) {
		/* Form Data */
		if(isset($instance['title'])) { $title = $instance[ 'title' ]; } else { $title = __( 'Latest Posts', 'snplugin' ); }
		if(isset($instance['latest_posts_category'])) { $latest_posts_category = $instance[ 'latest_posts_category' ]; } else { $latest_posts_category = ''; }
		if(isset($instance['latest_posts_count'])) { $latest_posts_count = $instance[ 'latest_posts_count' ]; } else { $latest_posts_count = '3'; }
		
		/* Form */
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'latest_posts_category' ); ?>"><?php _e( 'Category:' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'latest_posts_category' ); ?>" name="<?php echo $this->get_field_name( 'latest_posts_category' ); ?>">
				<option value="">Show all</option>
				<?php $categories = get_categories( 'hide_empty=0' );
				foreach($categories as $category) {
					if($category->term_id == $latest_posts_category) {
						$selected = ' selected="selected" ';
					} else {
						$selected = '';
					}
					echo '<option value="'.$category->term_id.'"'.$selected.'>'.$category->name.'</option>';
				} ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'latest_posts_count' ); ?>"><?php _e( 'Number to show:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'latest_posts_count' ); ?>" name="<?php echo $this->get_field_name( 'latest_posts_count' ); ?>" type="text" value="<?php echo esc_attr( $latest_posts_count ); ?>" />
		</p>
		<?php
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget( "sn_simnor_widgets_latest_posts_widget" );' ) );