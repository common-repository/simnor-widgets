<?php

/* Authors List Widget */

class sn_simnor_widgets_authors_widget extends WP_Widget {

	public function __construct() {
		global $sn_simnor_widgets_name;
		parent::__construct(
	 		'sn_simnor_widgets_authors_widget',
			$sn_simnor_widgets_name . __( ': Authors', 'snplugin' ), 
			array( 'description' => __( 'List the authors of your blog', 'snplugin' ) )
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'sn_simnor_widgets_authors_widget', $instance['title'] );
		
		echo $before_widget;
		if(!empty($title)) { echo $before_title . $title . $after_title; }
				
		echo '<ul class="sn-simnor-widgets-ul sn-simnor-widgets-authors">';
				
		global $wpdb;
		$authors = $wpdb->get_results("SELECT ID, user_nicename from $wpdb->users ORDER BY display_name");
		
		foreach($authors as $author) { ?>
		
		<li>
			<a class="author-avatar" href="<?php echo esc_url(get_author_posts_url($author->ID)); ?>"><?php echo get_avatar($author->ID, 60); ?></a>
			<span class="author-name"><a href="<?php echo esc_url(get_author_posts_url($author->ID)); ?>"><?php the_author_meta('display_name', $author->ID); ?></a></span>
			<span class="author-count"><?php echo count_user_posts($author->ID); 
			if(count_user_posts($author->ID) == 1) {
				_e(' post', 'snplugin');
			} else {
				_e(' posts', 'snplugin');
			} ?></span>
		</li>
		
		<?php
		
		}
		
		echo '</ul>';
		
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	public function form( $instance ) {
		/* Form Data */
		if(isset($instance['title'])) { $title = $instance[ 'title' ]; } else { $title = __( 'Authors', 'snplugin' ); }
		
		/* Form */
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget( "sn_simnor_widgets_authors_widget" );' ) );