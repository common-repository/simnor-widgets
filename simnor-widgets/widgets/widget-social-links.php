<?php

/* Social Links Widget */

class sn_simnor_widgets_social_links_widget extends WP_Widget {

	public function __construct() {
		global $sn_simnor_widgets_name;
		parent::__construct(
	 		'sn_simnor_widgets_social_links_widget',
			$sn_simnor_widgets_name . __( ': Social Links', 'snplugin' ), 
			array( 'description' => __( 'Icon links to social profiles', 'snplugin' ), )
		);
	}

	public function widget( $args, $instance ) {
		global $sn_simnor_widgets_directory;
		
		extract( $args );
		$title = apply_filters( 'sn_simnor_widgets_social_links_widget', $instance['title'] );
		$i = 0;
		$social_links_data_services = array();
		$social_links_data_links = array();
		while($i < 10) { $i++;
			if(isset($instance['social_links_service_'.$i])) { 
				$social_links_data_services[] = apply_filters( 'sn_simnor_widgets_social_links_widget', $instance['social_links_service_'.$i]);
			} else {
				$social_links_data_services[] = "";
			}
			if(isset($instance['social_links_link_'.$i])) { 
				$social_links_data_links[] = apply_filters( 'sn_simnor_widgets_social_links_widget', $instance['social_links_link_'.$i]);
			} else {
				$social_links_data_links[] = "";
			}
		}

		echo $before_widget;
		if(!empty($title)) { echo $before_title . $title . $after_title; } ?>
		
		<ul class="sn-simnor-widgets-ul sn-simnor-widgets-social-links">
		<?php $i = 0; while($i < 10) { $i++;
			if($social_links_data_services[$i-1]) {
				echo '<li><a href="'.$social_links_data_links[$i-1].'" target="_blank"><img src="'.$sn_simnor_widgets_directory.'includes/images/social-icons/'.$social_links_data_services[$i-1].'.png" alt="" /></a></li>';
			}
		}
		?>
		</ul>
		
		<?php echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$i = 0;
		while($i < 10) { $i++;
			$instance['social_links_service_'.$i] = strip_tags( $new_instance['social_links_service_'.$i] );
			$instance['social_links_link_'.$i] = strip_tags( $new_instance['social_links_link_'.$i] );
		}
		return $instance;
	}

	public function form( $instance ) {
		global $sn_simnor_widgets_path, $sn_simnor_widgets_directory;
		
		/* Form Data */
		if(isset($instance['title'])) { $title = $instance[ 'title' ]; } else { $title = __( 'Social Links', 'snplugin' ); }
		$i = 0;
		$social_links_data_services = array();
		$social_links_data_links = array();
		while($i < 10) { $i++;
			if(isset($instance['social_links_service_'.$i])) { 
				$social_links_data_services[] = $instance['social_links_service_'.$i];
			} else {
				$social_links_data_services[] = "";
			}
			if(isset($instance['social_links_link_'.$i])) { 
				$social_links_data_links[] = $instance['social_links_link_'.$i];
			} else {
				$social_links_data_links[] = "";
			}
		}
		
		/* Form */
		?>
		<style type="text/css">
		.sn-simnor-widgets-social-link { }
			.sn-simnor-widgets-choose-social-icon { float: left; display: block; }
			.sn-simnor-widgets-social-icon { float: left; display: block; margin: 0 0 0 10px; }
				.sn-simnor-widgets-social-icon img { width: 22px; height: 22px; display: block; }
			.sn-simnor-widgets-social-icons-holder { clear: both; padding: 10px 0 0 0; }
				.sn-simnor-widgets-social-icons-holder { clear: both; overflow: hidden; }
				.sn-simnor-widgets-social-icons-holder ul { padding: 10px 0 0 0; display: block; list-style: none; margin: 0px; }
				.sn-simnor-widgets-social-icons-holder ul li { display: block; float: left; width: 36px; height: 36px; margin: 0 8px 8px 0; }
				.sn-simnor-widgets-social-icons-holder ul li a { display: block; float: left; width: 36px; height: 36px; text-align: center; line-height: 40px; border: 1px solid #EEE; background: #FFF; }
				.sn-simnor-widgets-social-icons-holder ul li a:hover { border: 1px solid #CCC; box-shadow: 1px 1px 2px #EEE; }
				.sn-simnor-widgets-social-icons-holder ul li a i { font-size: 14px; }
				.sn-simnor-widgets-social-icons-holder ul li a img { width: 18px; height: 18px; margin-top: 10px; }
				.sn-simnor-widgets-social-icons-holder ul li a span { display: none; }
			.sn-simnor-widgets-social-link .widefat { margin-top: 10px; clear: both; }
		</style>
		<script type="text/javascript">
		jQuery(function() {
			jQuery('.sn-simnor-widgets-choose-social-icon').on("click", function() {
				jQuery(this).parent('.sn-simnor-widgets-social-link').find('.sn-simnor-widgets-social-icons-holder').fadeIn();
				return false;
			});
			jQuery('.sn-simnor-widgets-social-icons-holder ul li a').on("click", function() {
				jQuery(this).parentsUntil('.sn-simnor-widgets-social-link').parent('.sn-simnor-widgets-social-link').find('input[type="text"]:first').val(jQuery(this).find('span').text());
				jQuery('.sn-simnor-widgets-social-icons-holder').hide();
				jQuery(this).parentsUntil('.sn-simnor-widgets-social-link').parent('.sn-simnor-widgets-social-link').find('.sn-simnor-widgets-social-icon').html('<img src="<?php echo $sn_simnor_widgets_directory; ?>includes/images/social-icons/'+jQuery(this).find('span').text()+'.png" alt="" />');
				return false;
			});
		});
		</script>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
		include($sn_simnor_widgets_path . '/includes/arrays.php');
		global $social_services;
		$i = 0;
		while($i < 10) { $i++; 
		?>
		<p>
			<label for="<?php echo $this->get_field_name( 'social_links_service_'.$i ); ?>"><?php _e( 'Social Link '); echo $i; ?></label>
			
			<div class="sn-simnor-widgets-social-link">
				<a href="#" class="button sn-simnor-widgets-choose-social-icon"><?php _e('Choose icon', 'snplugin'); ?></a>
				<div class="sn-simnor-widgets-social-icon"><?php if($social_links_data_services[$i-1]) { ?>
					<img src="<?php echo $sn_simnor_widgets_directory; ?>includes/images/social-icons/<?php echo $social_links_data_services[$i-1]; ?>.png" alt="" />
				<?php } ?></div>
				<div class="sn-simnor-widgets-social-icons-holder" style="display:none;">
					<p>Icons by <a href="http://icondock.com/free/vector-social-media-icons" target="_blank">Icon Dock</a></p>
					<ul class="sn-simnor-widgets-social-icons-list">
					<?php foreach($social_services as $social_service) { ?>
						<li><a href="#"><img src="<?php echo $sn_simnor_widgets_directory; ?>includes/images/social-icons/<?php echo $social_service[0]; ?>.png" alt="" /><span><?php echo $social_service[0]; ?></span></a></li>
					<?php } ?>
					</ul>
				</div>	
				
				<input style="display:none" id="<?php echo $this->get_field_id( 'social_links_service_'.$i ); ?>" name="<?php echo $this->get_field_name( 'social_links_service_'.$i ); ?>" type="text" value="<?php echo esc_attr( $social_links_data_services[$i-1] ); ?>" />

				<input class="widefat" id="<?php echo $this->get_field_id( 'social_links_link_'.$i ); ?>" name="<?php echo $this->get_field_name( 'social_links_link_'.$i ); ?>" type="text" value="<?php echo esc_attr( $social_links_data_links[$i-1] ); ?>" />

			</div>
			
		</p>
		<?php 
		}
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget( "sn_simnor_widgets_social_links_widget" );' ) );