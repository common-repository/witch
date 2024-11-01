<?php
/**
 * @package WiTCh
 * @version 0.1.4
 */
/*
Plugin Name: WiTCh
Plugin URI: http://pattyland.de/project/witch/
Description: Wordpress moTion Controlling enables your visitors to use motion gestures to interact with your page.
Author: pattyland
Version: 0.1.4
Author URI: http://pattyland.de/
License: MIT
*/

function leapjs() {
	wp_enqueue_script( 'leapjs', plugins_url() . '/witch/lib/leapjs/leap.min.js', false, '0.5.0');
}

function insert_script() {
	$prev_post = get_adjacent_post(false, '', true);
	if($prev_post) $prev_post_url = get_permalink($prev_post->ID);
	
	$next_post = get_adjacent_post(false, '', false);
	if($next_post) $next_post_url = get_permalink($next_post->ID);
	
	?><script type="text/javascript">
	var leapopts = {enableGestures: true};
	var swiped = false;
	Leap.loop(leapopts, function(frame) {
		if (frame.gestures.length > 0) {
	    for (var i = 0; i < frame.gestures.length; i++) {
	      var gesture = frame.gestures[i];
	      if (gesture.type === "swipe" && swiped === false) {
	          var h = Math.abs(gesture.direction[0]) > Math.abs(gesture.direction[1]);
	          if(h){
	              if(gesture.direction[0] > 0){
	                  <?php if($prev_post) echo 'window.location.href = "'.get_permalink($prev_post->ID).'";'."\n swiped = true; \n"; ?>
	              } else {
	                  <?php if($next_post) echo 'window.location.href = "'.get_permalink($next_post->ID).'";'."\n swiped = true; \n"; ?>
	              }
	          }
	          
	       }       
	     }
	  }
	});
	</script><?php
}

add_action( 'wp_enqueue_scripts', 'leapjs' );
add_action( 'wp_footer', 'insert_script');

?>