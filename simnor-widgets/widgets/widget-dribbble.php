<?php

/* Dribbble List Widget */

class sn_simnor_widgets_dribbble_widget extends WP_Widget {

	public function __construct() {
		global $sn_simnor_widgets_name;
		parent::__construct(
	 		'sn_simnor_widgets_dribbble_widget',
			$sn_simnor_widgets_name . __( ': Dribbble', 'snplugin' ), 
			array( 'description' => __( 'Show your latest dribbble shots', 'snplugin' ) )
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		global $sn_simnor_widgets_path;
		$title = apply_filters( 'sn_simnor_widgets_dribbble_widget', $instance['title'] );
		$dribbble_username = apply_filters( 'sn_simnor_widgets_dribbble_widget', $instance['dribbble_username'] );
		$dribbble_count = apply_filters( 'sn_simnor_widgets_dribbble_widget', $instance['dribbble_count'] );
		
		echo $before_widget;
		if(!empty($title)) { echo $before_title . $title . $after_title; }
		
		if($dribbble_username) {
				
			echo '<ul class="sn-simnor-widgets-ul sn-simnor-widgets-dribbble">';
			
			require $sn_simnor_widgets_path . '/includes/api/dribbble/src/Dribbble/Dribbble.php';
			require $sn_simnor_widgets_path . '/includes/api/dribbble/src/Dribbble/Exception.php';
			$dribbble = new Dribbble\Dribbble();  
			
			try {
				$my_shots = $dribbble->getPlayerShots($dribbble_username, 1, $dribbble_count);
				foreach ($my_shots->shots as $shot) {
					$shot_image = $shot->image_url;
					$shot_url = $shot->url;
					$shot_title = $shot->title;
					
					echo '<li><a href="' . $shot_url . '" target="_blank"><img src="'.$shot_image.'" alt="'.$shot_title.'" /><span class="dribbble-title">' . $shot_title . '</span></a></li>';
					
				}
			}
			catch (Dribbble\Exception $e) {
				echo $e->getCode() . ': ' . $e->getMessage();
			}
			
			echo '</ul>';
		
		}
		
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['dribbble_username'] = strip_tags( $new_instance['dribbble_username'] );
		$instance['dribbble_count'] = strip_tags( $new_instance['dribbble_count'] );
		return $instance;
	}

	public function form( $instance ) {
		/* Form Data */
		if(isset($instance['title'])) { $title = $instance[ 'title' ]; } else { $title = __( 'Dribbble', 'snplugin' ); }
		if(isset($instance['dribbble_username'])) { $dribbble_username = $instance[ 'dribbble_username' ]; } else { $dribbble_username = ''; }
		if(isset($instance['dribbble_count'])) { $dribbble_count = $instance[ 'dribbble_count' ]; } else { $dribbble_count = '3'; }
		
		/* Form */
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'dribbble_username' ); ?>"><?php _e( 'Dribbble Username:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'dribbble_username' ); ?>" name="<?php echo $this->get_field_name( 'dribbble_username' ); ?>" type="text" value="<?php echo esc_attr( $dribbble_username ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'dribbble_count' ); ?>"><?php _e( 'Number to show:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'dribbble_count' ); ?>" name="<?php echo $this->get_field_name( 'dribbble_count' ); ?>" type="text" value="<?php echo esc_attr( $dribbble_count ); ?>" />
		</p>
		<?php
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget( "sn_simnor_widgets_dribbble_widget" );' ) );