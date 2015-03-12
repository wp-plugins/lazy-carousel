<?php
/**
 * Plugin Name: Lazy Carousel Wordpress
 * Plugin URI: http://raihanb.com/premium/lazy-carousel
 * Description: This plugin will enable carousel in your WordPress site.
 * Author: Abu Sayed
 * Author URI: http://raihanb.com/premium/
 * Version: 1.0
 * License: GPL2
 */

/*Some Set-up*/
define('LAZY_CAROUSEL', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

function lazy_carousel_js() {
wp_enqueue_script('lazy-carousel-jquery', LAZY_CAROUSEL.'js/jquery.zaccordion.js', array('jquery'));

}
add_action('wp_footer','lazy_carousel_js');



// Lazy carousel css
function carousel_css (){ ?>
		<style type="text/css">
			#container {width:960px;margin:0 auto;padding-bottom:40px;height:200px!important}
			pre.html {margin:10px 0;}
			.clear {clear:both;}
			.entry-content ul li{height:500px;margin-bottom:50px}
			


			
		</style>
<?php
}
add_action('wp_footer', 'carousel_css');

// Active for lazy carousel
function lazy_carousel_active (){ ?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery("#example14").zAccordion({
					tabWidth: "15%",
					width: "100%",
					height: "100%"
				});
				jQuery(window).resize(function() {
					jQuery("#example14").height($(window).height());
					jQuery("#example14 li").height($(window).height());
					jQuery("#example14 img").height($(window).height());
				});
			});
		</script>
<?php
}
add_action('wp_footer', 'lazy_carousel_active');



 //  Lazy carousel custom post
add_action( 'init', 'carousel_custom_post' );
function carousel_custom_post() {
	register_post_type( 'carousel-items',
		array(
			'labels' => array(
				'name' => __( 'Lazy Carousel' ),
				'singular_name' => __( 'Lazy Carousel' ),
				'add_new_item' => __( 'Add New Carousel' )
			),
			'public' => true,
			'supports' => array('author', 'thumbnail', 'title', 'editor', 'custom-fields'),
			'has_archive' => true,
			'rewrite' => array('slug' => 'carousel-item'),
		)
	);
}	 


	  //  Lazy carousel custom taxonomy
	 function lazy_carousel_taxonomy() {
                    register_taxonomy('carousel_cat', //the name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
	         'carousel-items',                   //post type name
                           array(
                           'hierarchical'                =>true,
                           'label'                          =>'Carousel Category',   //Display name
                           'query_var'                   =>true,
                           'show_admin_column'   =>true,
                           'rewrite'                        =>array(
                           'slug'                           =>'carousel-category',  // This controls the base slug that will display before each term
						    
                          'with_front'                    =>false   // Don't display the category base before
                         )
                  )
           );

     }
   add_action(  'init', 'lazy_carousel_taxonomy' );	

   // Lazy carousel Shortcode
   function lazy_carousel_shortcode ($atts) {
         extract ( shortcode_atts( array(
		 'category' =>'',		 
		 'height' =>'',		 
		 'posts_per_page' =>'',		 

    ), $atts, 'category_post' ) );

      $q = new WP_Query (
            array( 'posts_per_page' => $posts_per_page, 'post_type' => 'carousel-items', 'carousel_cat' => $category)
           );
  $list = '
  
  <style>
      .entry-content img, .comment-content img, .widget img, img.header-image, .author-avatar img, img.wp-post-image {
			  border-radius: 3px;
			  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
			  height:'.$height.';
			}
  </style>
  
  
  <ul id="example14">';

  while ($q->have_posts() ) : $q->the_post ();

 $id = get_the_ID();
 $post_thumbnail = get_the_post_thumbnail ( get_the_ID(), 'post_thumbnail' );
	$list .= '
	
	        <li>'.$post_thumbnail.'</li>
		   
    ';
    endwhile;
    $list.= '</ul>';
   wp_reset_query();
   return $list;
   }
   add_shortcode('carousel', 'lazy_carousel_shortcode');		


?>