<?php
class SB_RandomPosts_ShortCode {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_asset' ) );
	}

	public function load_asset() {
		wp_enqueue_style('random-post', plugins_url( 'assets/style.css' , __FILE__) );
	}

	public static function shortcode_callback( $atts ) {
		$atts = shortcode_atts( array(
			'size' => 5,
			'type' => 'image_only',
			'post_type' => 'post',
			'thumb_size' => 'full'
		), $atts );

		$args = array(
		    'post_type' => $atts['post_type'],
			'ignore_sticky_posts' => true,
			'orderby' => 'rand', 'order' => 'ASC',
			'posts_per_page' => $atts['size']
		);

		$q = new WP_Query( $args );

		if ( $q->have_posts() ) :
			echo '<div class="random-posts '.$atts['type'].'"><ul>';
			while ( $q->have_posts() ) :
				$q->the_post();
				include dirname(__FILE__) . '/parts/shortcode-content-'.$atts['type'].'.php';
			endwhile;
			wp_reset_query();
			echo '</ul></div>';
		endif;
	}
}

class SB_RandomPosts_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array(
        	'classname' => 'simple_random_posts',
        	'description' => 'Display random posts' );
        parent::__construct( 'simple_random_posts', 'Simple Random Posts', $widget_ops );
    }

    function widget( $args, $instance ) {
        extract( $args, EXTR_SKIP );
        echo $before_widget;

        if ( $instance['title'] != '' ) {
        	echo $before_title;
        	echo $instance['title'];
        	echo $after_title;
        }

        echo do_shortcode('[sb_random_posts size="'.$instance['numposts'].'" type="'.$instance['layout'].'" post_type="'.$instance['post_type'].'" thumb_size="'.$instance['thumb_size'].'"]');

    	echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {

        $instance = array();
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['numposts'] = $new_instance['numposts'];
		$instance['layout'] = strip_tags($new_instance['layout']);
		$instance['post_type'] = strip_tags($new_instance['post_type']);
		$instance['thumb_size'] = strip_tags($new_instance['thumb_size']);
        return $instance;
    }

    function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$numposts = ! empty( $instance['numposts'] ) ? $instance['numposts'] : 5;
		$layout = ! empty( $instance['layout'] ) ? $instance['layout'] : 'image_text';
		$post_type = ! empty( $instance['post_type'] ) ? $instance['post_type'] : 'post';
		$thumb_size = ! empty( $instance['thumb_size'] ) ? $instance['thumb_size'] : 'full';

		$post_types = sbrpw_post_types_to_array();

		$layouts = array(
			'text_only' => 'Text Only',
			'image_only' => 'Image Only',
			'image_text' => 'Image & Text',
			'image_fullwidth_text' => 'Image & Text (Full Width)',
		);

		$thumbnail_sizes = get_intermediate_image_sizes();
		$thumbnail_sizes[] = 'full';

		include 'parts/widget-admin.php';

    }
}