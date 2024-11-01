<?php

/* Twitter Widget */

class sn_simnor_widgets_twitter_widget extends WP_Widget {

	public function __construct() {
		global $sn_simnor_widgets_name;
		parent::__construct(
	 		'sn_simnor_widgets_twitter_widget',
			$sn_simnor_widgets_name . __( ': Twitter Feed', 'snplugin' ), 
			array( 'description' => __( 'Show your latest tweets.', 'snplugin' ) )
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		global $sn_simnor_widgets_path;
		$title = apply_filters( 'sn_simnor_widgets_twitter_widget', $instance['title'] );
		$twitter_username = apply_filters( 'sn_simnor_widgets_twitter_widget', $instance['twitter_username'] );
		$twitter_count = apply_filters( 'sn_simnor_widgets_twitter_widget', $instance['twitter_count'] );
		if($twitter_count == "") { $twitter_count = 3; }		
		$twitter_consumer_key = apply_filters( 'sn_simnor_widgets_twitter_widget', $instance['twitter_consumer_key'] );
		$twitter_consumer_secret = apply_filters( 'sn_simnor_widgets_twitter_widget', $instance['twitter_consumer_secret'] );
		$twitter_user_token = apply_filters( 'sn_simnor_widgets_twitter_widget', $instance['twitter_user_token'] );
		$twitter_user_secret = apply_filters( 'sn_simnor_widgets_twitter_widget', $instance['twitter_user_secret'] );
		
		echo $before_widget;
		if(!empty($title)) { echo $before_title . $title . $after_title; }
		
		echo '<ul class="sn-simnor-widgets-ul sn-simnor-widgets-tweets">';

			require $sn_simnor_widgets_path . '/includes/api/tmhOAuth/tmhOAuth.php';
			require $sn_simnor_widgets_path . '/includes/api/tmhOAuth/tmhUtilities.php';
			$tmhOAuth = new tmhOAuth(array(
			  'consumer_key'    => $twitter_consumer_key,
			  'consumer_secret' => $twitter_consumer_secret,
			  'user_token'      => $twitter_user_token,
			  'user_secret'     => $twitter_user_secret,
			));
			
			$code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/statuses/user_timeline'), array(
			 'screen_name' => $twitter_username,
			 'count' => $twitter_count));
			
			if ($code == 200) {
			
				$response = $tmhOAuth->response['response'];
				$tweets = json_decode($response, true);
				
				foreach($tweets as $tweet) {
				
					$tweet_text = $tweet['text'];
					$tweet_text = preg_replace('@(https?://([-\w\.]+)+(/([\w/_\.]*(\?\S+)?(#\S+)?)?)?)@','<a href="$1">$1</a>', $tweet_text);
					$tweet_text = preg_replace('/\s+#(\w+)/', ' <a href="http://search.twitter.com/search?q=%23$1">#$1</a>', $tweet_text);
					$tweet_text = preg_replace('/@(\w+)/', '<a href="http://twitter.com/$1">@$1</a>', $tweet_text);
					
			        echo '<li>
			        	<span class="tweet-text">'.$tweet_text.'</span>
			        	<span class="tweet-date">'.date("l M j \- g:ia",strtotime($tweet['created_at'])).'</span>
			        </li>';
			    
			    }
			
			} else {
					
				echo '<li><p>There was a problem retrieving this twitter feed.</p></li>';
				
			}
		
		echo '</ul>
		<p align="right"><a href="http://twitter.com/'.$twitter_username.'" target="_blank">'.__('Follow ', 'snplugin').$twitter_username.'</a></p>';
			
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['twitter_username'] = strip_tags( $new_instance['twitter_username'] );
		$instance['twitter_count'] = strip_tags( $new_instance['twitter_count'] );
		$instance['twitter_consumer_key'] = strip_tags( $new_instance['twitter_consumer_key'] );
		$instance['twitter_consumer_secret'] = strip_tags( $new_instance['twitter_consumer_secret'] );
		$instance['twitter_user_token'] = strip_tags( $new_instance['twitter_user_token'] );
		$instance['twitter_user_secret'] = strip_tags( $new_instance['twitter_user_secret'] );
		return $instance;
	}

	public function form( $instance ) {
		/* Form Data */
		if(isset($instance['title'])) { $title = $instance[ 'title' ]; } else { $title = __( 'Twitter Feed', 'snplugin' ); }
		if(isset($instance['twitter_count'])) { $twitter_count = $instance[ 'twitter_count' ]; } else { $twitter_count = "3"; }
		if(isset($instance['twitter_username'])) { $twitter_username = $instance[ 'twitter_username' ]; } else { $twitter_username = ''; }
		if(isset($instance['twitter_consumer_key'])) { $twitter_consumer_key = $instance[ 'twitter_consumer_key' ]; } else { $twitter_consumer_key = ''; }
		if(isset($instance['twitter_consumer_secret'])) { $twitter_consumer_secret = $instance[ 'twitter_consumer_secret' ]; } else { $twitter_consumer_secret = ''; }
		if(isset($instance['twitter_user_token'])) { $twitter_user_token = $instance[ 'twitter_user_token' ]; } else { $twitter_user_token = ''; }
		if(isset($instance['twitter_user_secret'])) { $twitter_user_secret = $instance[ 'twitter_user_secret' ]; } else { $twitter_user_secret = ''; }
		
		/* Form */
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_username' ); ?>"><?php _e( 'Twitter Username:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'twitter_username' ); ?>" name="<?php echo $this->get_field_name( 'twitter_username' ); ?>" type="text" value="<?php echo esc_attr( $twitter_username ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_count' ); ?>"><?php _e( 'Number of Tweets:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'twitter_count' ); ?>" name="<?php echo $this->get_field_name( 'twitter_count' ); ?>" type="text" value="<?php echo esc_attr( $twitter_count ); ?>" />
		</p>
		
		<p><br/><strong><?php _e('To show tweets on your site you must authenticate yourself through the Twitter API. Follow this <a href="http://simnorlabs.com/support/tutorials/get-your-twitter-api-codes/" target="_blank">tutorial</a> and then add the details below:', 'snplugin'); ?></strong></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_consumer_key' ); ?>"><?php _e( 'Consumer Key:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'twitter_consumer_key' ); ?>" name="<?php echo $this->get_field_name( 'twitter_consumer_key' ); ?>" type="text" value="<?php echo esc_attr( $twitter_consumer_key ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_consumer_secret' ); ?>"><?php _e( 'Consumer Secret:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'twitter_consumer_secret' ); ?>" name="<?php echo $this->get_field_name( 'twitter_consumer_secret' ); ?>" type="text" value="<?php echo esc_attr( $twitter_consumer_secret ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_user_token' ); ?>"><?php _e( 'User Access Token:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'twitter_user_token' ); ?>" name="<?php echo $this->get_field_name( 'twitter_user_token' ); ?>" type="text" value="<?php echo esc_attr( $twitter_user_token ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_user_secret' ); ?>"><?php _e( 'User Access Token Secret:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'twitter_user_secret' ); ?>" name="<?php echo $this->get_field_name( 'twitter_user_secret' ); ?>" type="text" value="<?php echo esc_attr( $twitter_user_secret ); ?>" />
		</p>
		<?php
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget( "sn_simnor_widgets_twitter_widget" );' ) );