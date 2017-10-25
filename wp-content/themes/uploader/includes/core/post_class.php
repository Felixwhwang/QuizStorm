<?php defined('ABSPATH') OR die('restricted access');
//@TODO: allow available uploads only
//@TODO: automatically create playlist of multiple audio and video files

if( ! class_exists( 'eXc_Post_Class' ) )
{
	class eXc_Post_Class
	{
		private $eXc;
		private $wpdb;

		private $_update_views_in = 600000; // Update views after 10 minutes

		var $interval = 120; // Time before user can revote
		
		function __construct( &$eXc )
		{
			$this->eXc = $eXc;
			
			$this->wpdb =& $GLOBALS['wpdb'];
			
			//$this->eXc->html->load_js('exc-post-like', $this->eXc->system_url('views/js/post-like.js') );

			if ( exc_is_client_side() )
			{
				//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ) );
			
				//Rating
				//add_action('wp_ajax_nopriv_exc_post_like', array($this, 'post_like'));
				//add_action('wp_ajax_exc_post_like', array($this, 'post_like'));
			}
		
			//Automatic views counter
			if ( is_single() )
			{
				//add_filter( 'the_content', array( $this, 'count_post_views' ) );
			}
								
			//User Likes
			//add_action('exc_post_like', array($this, 'update_user_likes'));
		}
		
		function enqueue_script()
		{
			wp_enqueue_script( 'exc-post-like', $this->eXc->system_url('views/js/post-like.js'), array(), $this->eXc->get_version(), true );
			
			wp_localize_script('exc-post-like', 'exc_post_like', array(
				'url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('exc-post-like')
			));
		}
		
		function count_post_views( $content )
		{
			// Post views count
			$current_time = current_time( 'timestamp', 1 );
			$session_key = 'post_views_' . get_the_ID();
			
			if ( $session = $this->eXc->session->get_data( $session_key ) )
			{
				// Check if session is expired
				if ( ( $current_time - $this->_update_views_in ) >= $session)
				{
					$session = '';
				} else
				{
					return $content;
				}
			}
			
			// Update session
			$this->eXc->session->set_data( $session_key, current_time( 'timestamp', 1 ) );

			// Automatically update post views
			exc_get_views( true );
			
			$user_id = get_the_author_meta('ID');
			
			if( ! $views = get_the_author_meta('media_views', $user_id))
			{
				$views = 0;
			}
			
			update_user_meta( $user_id, 'media_views', ++$views );
			
			return $content;
		}
		
		/*
		function update_user_likes($user_id = '')
		{
			if( ! $user_id) return false;
			
			//Current value
			$views = ($v = get_the_author_meta('media_likes', $user_id)) ? $v : 0;
			update_user_meta($user_id, 'media_likes', ++$views);

			if(($the_user = get_current_user_id()) && $user_id != $the_user)
			{
				$who_liked = get_the_author_meta('who_liked', $user_id);

				if( ! in_array($the_user, $who_liked, true))
				{
					$who_liked[] = $the_user;
					update_user_meta($user_id, 'who_liked', $who_liked);
				}
			}
		}*/
		
		/*
		function i_liked_it($post_id)
		{
			if( ! $user_id = get_current_user_id()) return;
			
			$likes = ($value = get_the_author_meta('media_iliked', $user_id)) ? $value : array();
			
			if(array_search($post_id, $likes) === false)
			{
				$likes[] = $post_id;
				update_user_meta($user_id, 'media_iliked', $likes);
			}
		}*/
		
		
		function get_followers($user_id = '')
		{
			if( ! $user_id) $user_id = get_current_user_id();

			return ($c = $this->wpdb->get_var("SELECT count(*) FROM ".$this->wpdb->prefix."exc_followers WHERE following_user_id = $user_id")) ? 
					$c : 0;
			
		}
		
		function get_following($user_id = '')
		{
			if( ! $user_id) $user_id = get_current_user_id();
			
			return ($c = $this->wpdb->get_var("SELECT count(*) FROM ".$this->wpdb->prefix."exc_followers WHERE follower_user_id = $user_id")) ? 
					$c : 0;
		}
		
		function follow_user()
		{
			check_ajax_referer("exc-user-follow", "nonce");
			
			if( ! $follower_user_id = get_current_user_id())
			{
				wp_send_json_error( __("You must login to follow users", 'exc-uploader-theme') );
			}
			
			$following_user = ($following_id = exc_kv($_POST, 'user_id')) ? get_user_by('id', $following_id) : '';

			if(is_a($following_user, 'WP_User') && $follower_user_id && ($following_user->ID != $follower_user_id))
			{
				//Check if user is not already following
				if($this->wpdb->get_var("SELECT COUNT(*) FROM ".$this->wpdb->prefix."exc_followers WHERE following_user_id = '".$following_user->ID."'
										AND follower_user_id = '".$follower_user_id."'"))
				{
					wp_send_json_error( sprintf( __("You're already following %s.", 'exc-uploader-theme'), $following_user->display_name) );
				}else
				{
					$this->wpdb->insert(
						$this->wpdb->prefix.'exc_followers', 
						
						array(
							'following_user_id' => $following_user->ID,
							'follower_user_id' => $follower_user_id,
						),
						
						array('%d', '%d')
					);
					
					if($this->wpdb->insert_id)
					{
						wp_send_json_success( sprintf( __("You're now following %s.", 'exc-uploader-theme'), $following_user->display_name) );
						
					}else
					{
						wp_send_json_error( __("An unknown error has occured, please try again later.", 'exc-uploader-theme') );
					}
				}
			}
			
			wp_send_json_error( __("You cannot follow this user, possible hacking attempt.", 'exc-uploader-theme') );
		}
		
		function get_likes( $user_id = '')
		{
			if ( ! $user_id ) {
				$user_id = get_current_user_id();
			}

			$votes = $this->wpdb->get_var(
						$this->wpdb->prepare(
								"SELECT count(*) FROM ".$this->wpdb->posts." INNER JOIN ".$this->wpdb->prefix."exc_votes ON ID = post_id
										 WHERE post_author = %d",

								array( $user_id )
							)
						);
			
			return $votes || 0;
		}
		
		function post_like()
		{
			check_ajax_referer("exc-post-like", "nonce");

			//@TODO: if user is logged in then save the post id and show unlike link
			if(isset($_POST['post_id']) && get_post_status($_POST['post_id']))
			{
				$ip = $_SERVER['REMOTE_ADDR'];
				$post_id = $_POST['post_id'];

				$voted_ips = get_post_meta($post_id, "exc_voted_ips", true);
				if( ! is_array($voted_ips)) $voted_ips = array();
				
				$meta_count = get_post_meta($post_id, "exc_votes_count", true);

				if( ! $this->hasLiked($post_id))
				{
					if($user_id = get_current_user_id())
					{
						$this->wpdb->insert(
							$this->wpdb->prefix.'exc_votes',
							array(
								'post_id'	=> $post_id,
								'user_id'	=> $user_id
							),
							
							array('%d', '%d')
						);
							
						update_post_meta($post_id, "exc_votes_count", ++$meta_count);
					}else
					{
						//Automatically eliminate older values
						$now = time();
						foreach($voted_ips as $ip=>$time)
						{
							if(round(($now - $time) / 60) > $this->interval)
							{
								unset($voted_ips[$ip]);
							}
						}
						
						$voted_ips[$ip] = $now;
						
						update_post_meta($post_id, "exc_voted_ips", $voted_ips);
						update_post_meta($post_id, "exc_votes_count", ++$meta_count);
						
						//do_action('exc_post_like', get_post_field('post_author', $post_id));
					}
					
					wp_send_json_success( array( 'msg' => __("Thank you for your vote.", 'exc-uploader-theme'), 'votes' => $meta_count) );
				}else
				{
					wp_send_json_error( array( 'msg' => __("Your vote has been cast.", 'exc-uploader-theme')) );
				}
			}
		}
		
		
		function get_votes($post_id = '')
		{
			$post_id = ( $post_id ) ? $post_id : get_the_ID();

			$votes = get_post_meta( $post_id, "exc_votes_count", true );
			
			return ( $votes ) ? $votes : 0;
		}
		
		function hasLiked($post_id = '')
		{
			$post_id = ($post_id) ? $post_id : get_the_ID();

			if($user_id = get_current_user_id())
			{
				return $this->wpdb->get_var("SELECT COUNT(*) FROM ".$this->wpdb->prefix."exc_votes WHERE post_id = '".$post_id."' AND user_id = '".$user_id."'")
						? true : false;
			}else
			{
				$ip = $_SERVER['REMOTE_ADDR'];
				$voted_ips = get_post_meta($post_id, "exc_voted_ips", true);
				
				if( ! is_array($voted_ips)) $voted_ips = array();				
				
				if(in_array($ip, array_keys($voted_ips)))
				{
					$time = $voted_ips[$ip];
					$now = time();
					
					if(round(($now - $time) / 60) < $this->interval)
					{
						return true;
					}
				}
			}
			
			return false;
		}
		
		function isFollowing($user_id, $current_user_id)
		{
			//if( ! $current_user_id) $current_user_id = get_current_user_id();
			if( ! $user_id || ! $current_user_id) return false;
			
			return $this->wpdb->get_var("SELECT count(*) FROM ".$this->wpdb->prefix."exc_followers
									WHERE follower_user_id = $current_user_id AND following_user_id = $user_id")
					? true : false;
		}
		
		function get_follow_link($user_id = '', $html = '<a class="btn btn-xs follow-me {class}" href="#" data-user="{user}" data-security="{nonce}">
															<i class="icon-follow-me"></i>{str}</a>')
		{
			if( ! $user_id) $user_id = get_the_author_meta('ID');
			
			$str = ($this->isFollowing($user_id, get_current_user_id())) ? __('unfollow', 'exc-uploader-theme')
													: __('follow', 'exc-uploader-theme');
			
			$variables = array('{class}', '{str}', '{user}', '{nonce}');
			$values = array('exc-follow-user', $str, get_the_author_meta('nicename'), wp_create_nonce('exc-follow-'.$user_id));
			
			echo str_replace($variables, $values, $html);
		}
		
		function get_like_link($before_vote = '<a href="#" id="exc-post-like-{post_id}" data-id="{post_id}" class="exc-post-like"><i class="icon-thumbs-up"></i> {vote_count}</a>',
							$after_vote = '<a href="javascript:void(0)"><i class="icon-thumbs-up"> {vote_count}</i></a>', $post_id = '')
		{
			$post_id = ( $post_id ) ? $post_id : get_the_ID();
			
			$vote_count = $this->get_votes( $post_id );
			
			$varaibles = array('{post_id}', '{vote_count}', '{class}');
			$values = array($post_id, $vote_count, 'exc-post-like');
			
			if ( $this->hasLiked( $post_id ) )
			{
				echo str_replace($varaibles, $values, $after_vote);
				
			}else
			{
				echo str_replace($varaibles, $values, $before_vote);
			}
		}
	}
}