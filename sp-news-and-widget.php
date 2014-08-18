<?php
/*
Plugin Name: SP News and three widgets(static, scrolling and scrolling with thumbs)
Plugin URL: http://sptechnolab.com
Description: A simple News and three widgets(static, scrolling and scrolling with thumbs) plugin
Version: 2.2.1
Author: SP Technolab
Author URI: http://www.sptechnolab.com
Contributors: SP Technolab
*/
/*
 * Register CPT sp_News
 *
 */
// Initialization function
add_action('init', 'sp_cpt_news_init');
function sp_cpt_news_init() {
  // Create new News custom post type
    $news_labels = array(
    'name'                 => _x('News', 'post type general name'),
    'singular_name'        => _x('News', 'post type singular name'),
    'add_new'              => _x('Add News Item', 'news'),
    'add_new_item'         => __('Add New News Item'),
    'edit_item'            => __('Edit News Item'),
    'new_item'             => __('New News Item'),
    'view_item'            => __('View News Item'),
    'search_items'         => __('Search  News Items'),
    'not_found'            =>  __('No News Items found'),
    'not_found_in_trash'   => __('No  News Items found in Trash'), 
    '_builtin'             =>  false, 
    'parent_item_colon'    => '',
    'menu_name'            => 'News'
  );
  $news_args = array(
    'labels'              => $news_labels,
    'public'              => true,
    'publicly_queryable'  => true,
    'exclude_from_search' => false,
    'show_ui'             => true,
    'show_in_menu'        => true, 
    'query_var'           => true,
    'rewrite'             => array( 
							'slug' => 'news',
							'with_front' => false
							),
    'capability_type'     => 'post',
    'has_archive'         => true,
    'hierarchical'        => false,
    'menu_position'       => 8,
    'menu_icon'           => plugins_url( 'images/newspaper-add-icon.png', __FILE__ ),
    'supports'            => array('title','editor','thumbnail','excerpt','comments'),
    'taxonomies'          => array('category', 'post_tag')
  );
  register_post_type('news',$news_args);
}

function my_rewrite_flush() {  
		sp_cpt_news_init();  
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'my_rewrite_flush' );

class SP_News_Widget extends WP_Widget {

    function SP_News_Widget() {

        $widget_ops = array('classname' => 'SP_News_Widget', 'description' => __('Displayed Letest News Items from the News  in a sidebar', 'news_cpt') );
        $control_ops = array( 'width' => 350, 'height' => 450, 'id_base' => 'sp_news_widget' );
        $this->WP_Widget( 'sp_news_widget', __('Letest News Widget', 'news_cpt'), $widget_ops, $control_ops );
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array( 'title' => '' ));
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $num_items = isset($instance['num_items']) ? absint($instance['num_items']) : 5;
    ?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('num_items'); ?>">Number of Items: <input class="widefat" id="<?php echo $this->get_field_id('num_items'); ?>" name="<?php echo $this->get_field_name('num_items'); ?>" type="text" value="<?php echo attribute_escape($num_items); ?>" /></label></p>
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['num_items'] = $new_instance['num_items'];
        return $instance;
    }
    function widget($news_args, $instance) {
        extract($news_args, EXTR_SKIP);

        $current_post_name = get_query_var('name');

        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $num_items = empty($instance['num_items']) ? '5' : apply_filters('widget_title', $instance['num_items']);

        $postcount = 0;

        echo $before_widget;

?>
            <h4 class="sp_new_title"><?php echo $title ?></h4>
            <!--visual-columns-->
            <div class="recent-news-items">
                <ul>
            <?php // setup the query
            $news_args = array( 'suppress_filters' => true,
                           'posts_per_page' => $num_items,
                           'post_type' => 'news',
                           'order' => 'DESC'
                         );

            $cust_loop = new WP_Query($news_args);
            if ($cust_loop->have_posts()) : while ($cust_loop->have_posts()) : $cust_loop->the_post(); $postcount++;
                    ?>
                    <li>
                        <a class="post-title" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
						
                    </li>
            <?php endwhile;
            endif;
             wp_reset_query(); ?>

                </ul>
            </div>
<?php
        echo $after_widget;
    }
}

/* Register the widget */
function sp_news_widget_load_widgets() {
    register_widget( 'SP_News_Widget' );
}

/* Load the widget */
add_action( 'widgets_init', 'sp_news_widget_load_widgets' );

/* scrolling news */
class SP_News_scrolling_Widget extends WP_Widget {

    function SP_News_scrolling_Widget() {

        $widget_ops = array('classname' => 'SP_News_scrolling_Widget', 'description' => __('Displayed Letest News Items from the News  in a sidebar', 'news_cpt') );
        $control_ops = array( 'width' => 350, 'height' => 450, 'id_base' => 'sp_news_s_widget' );
        $this->WP_Widget( 'sp_news_s_widget', __('Letest News Scrolling Widget', 'news_cpt'), $widget_ops, $control_ops );
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array( 'title' => '' ));
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $num_items = isset($instance['num_items']) ? absint($instance['num_items']) : 5;              
    ?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('num_items'); ?>">Number of Items: <input class="widefat" id="<?php echo $this->get_field_id('num_items'); ?>" name="<?php echo $this->get_field_name('num_items'); ?>" type="text" value="<?php echo attribute_escape($num_items); ?>" /></label></p>
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['num_items'] = $new_instance['num_items'];        
        return $instance;
    }
    function widget($news_args, $instance) {
        extract($news_args, EXTR_SKIP);
        $current_post_name = get_query_var('name');
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);  
		  $num_items = empty($instance['num_items']) ? '5' : apply_filters('widget_title', $instance['num_items']);  
        $postcount = 0;

        echo $before_widget;

?>
            <h4 class="sp_new_title"><?php echo $title ?></h4>
            <!--visual-columns-->
            <div class="recent-news-items">
               <div class="newsticker-jcarousellite">
			   <ul>
            <?php // setup the query
            $news_args = array( 'suppress_filters' => true,
       							'posts_per_page' => $num_items,                   
                           'post_type' => 'news',
                           'order' => 'DESC'
                         );

            $cust_loop = new WP_Query($news_args);
            if ($cust_loop->have_posts()) : while ($cust_loop->have_posts()) : $cust_loop->the_post(); $postcount++;
                    ?>
                    <li >
                        <a class="post-title" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                    </li>
            <?php endwhile;
            endif;
             wp_reset_query(); ?>
                </ul>
	            </div>
            </div>
<?php
        echo $after_widget;
    }
}

/* Register the widget */
function sp_news_scroll_widget_load_widgets() {
    register_widget( 'SP_News_scrolling_Widget' );
}

/* Load the widget */
add_action( 'widgets_init', 'sp_news_scroll_widget_load_widgets' );

/* news with thumb */
class SP_News_thmb_Widget extends WP_Widget {

    function SP_News_thmb_Widget() {

        $widget_ops = array('classname' => 'SP_News_thmb_Widget', 'description' => __('Displayed Letest News Items from the News  in a sidebar with thumbnails', 'news_cpt') );
        $control_ops = array( 'width' => 350, 'height' => 450, 'id_base' => 'sp_news_sthumb_widget' );
        $this->WP_Widget( 'sp_news_sthumb_widget', __('Letest News with thumb  Widget', 'news_cpt'), $widget_ops, $control_ops );
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array( 'title' => '' ));
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $num_items = isset($instance['num_items']) ? absint($instance['num_items']) : 5;
    ?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('num_items'); ?>">Number of Items: <input class="widefat" id="<?php echo $this->get_field_id('num_items'); ?>" name="<?php echo $this->get_field_name('num_items'); ?>" type="text" value="<?php echo attribute_escape($num_items); ?>" /></label></p>
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['num_items'] = $new_instance['num_items'];
        return $instance;
    }
    function widget($news_args, $instance) {
        extract($news_args, EXTR_SKIP);

        $current_post_name = get_query_var('name');

        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $num_items = empty($instance['num_items']) ? '5' : apply_filters('widget_title', $instance['num_items']);

        $postcount = 0;

        echo $before_widget;

?>
            <h4 class="sp_new_title"><?php echo $title ?></h4>
            <!--visual-columns-->
            <div class="recent-news-items">
			  <div class="newstickerthumb-jcarousellite">
                <ul>
            <?php // setup the query
            $news_args = array( 'suppress_filters' => true,
                           'posts_per_page' => $num_items,
                           'post_type' => 'news',
                           'order' => 'DESC'
                         );

            $cust_loop = new WP_Query($news_args);
            if ($cust_loop->have_posts()) : while ($cust_loop->have_posts()) : $cust_loop->the_post(); $postcount++;
                    ?>
                    <li>
						<div class="news_thumb_left">
					   <a  href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"> <?php
                  if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) {
                   the_post_thumbnail( array(80,80) );
                  }
                  ?> </a></div>
				  <div class="news_thumb_right">
                        <a class="post-title" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
						</div>
                    </li>
            <?php endwhile;
            endif;
             wp_reset_query(); ?>

                </ul>
            </div> </div>
<?php
        echo $after_widget;
    }
}
/* Register the widget */
function sp_news_thumb_widget_load_widgets() {
    register_widget( 'SP_News_thmb_Widget' );
}

/* Load the widget */
add_action( 'widgets_init', 'sp_news_thumb_widget_load_widgets' );


function get_news( $atts, $content = null ){
            // setup the query
            extract(shortcode_atts(array(
		"limit" => '',	
		"category" => ''
	), $atts));
	// Define limit
	if( $limit ) { 
		$posts_per_page = $limit; 
	} else {
		$posts_per_page = '-1';
	}
	if( $category ) { 
		$cat = $category; 
	} else {
		$cat = '';
	}
	ob_start();
            $news_args = array( 'suppress_filters' => true,
                           'posts_per_page' => $posts_per_page,
                           'post_type' => 'news',
                           'order' => 'DESC',
                           'cat'	=> $cat
                         );
                         
			
				$cust_loop = new WP_Query($news_args);
            
          
     
            if ($cust_loop->have_posts()) : while ($cust_loop->have_posts()) : $cust_loop->the_post();
            				get_template_part( 'content');  
           endwhile;
            endif;
             wp_reset_query(); 
				
		return ob_get_clean();			             
	}
add_shortcode('sp_news','get_news');	


wp_register_style( 'cssnews', plugin_dir_url( __FILE__ ) . 'css/stylenews.css' );
wp_register_script( 'vticker', plugin_dir_url( __FILE__ ) . 'js/jcarousellite.js', array( 'jquery' ) );			

	wp_enqueue_style( 'cssnews' );
	wp_enqueue_script( 'vticker' );
	function mynewsscript() {
	$option = 'NewsWidget_option';
	$newsscrollingoptionadmin = get_option( $option, $default ); 
	$customscrollpost = $newsscrollingoptionadmin['news_width']; 
	$customscrollpostheight = $newsscrollingoptionadmin['news_height'];
	$customscrollpostdelay = $newsscrollingoptionadmin['news_delay'];
	$customscrollpostspeed = $newsscrollingoptionadmin['news_speed'];
  
		if ($customscrollpost == 0 )
		{
			$vtrue = 'true';
		} else { $vtrue = 'false';
		}
		if ($customscrollpostheight == '' )
		{
			$vvisible = 3;
		} else { $vvisible = $customscrollpostheight;
		}
		if ($customscrollpostdelay == '' )
		{
			$vdelay = 500;
		} else { $vdelay = $customscrollpostdelay;
		}
		if ($customscrollpostspeed == '' )
		{
			$vspeed = 2000;
		} else { $vspeed = $customscrollpostspeed;
		}
	?>
	<script type="text/javascript">
	
jQuery(function() {
	 jQuery(".newsticker-jcarousellite").jCarouselLite({
		vertical: <?php echo $vtrue; ?>,
		hoverPause:true,
		visible: <?php echo $vvisible; ?>,
		auto: <?php echo $vdelay; ?>,
		speed:<?php echo $vspeed; ?>,
	});  
	 jQuery(".newstickerthumb-jcarousellite").jCarouselLite({
		vertical: <?php echo $vtrue; ?>,
		hoverPause:true,
		visible: <?php echo $vvisible; ?>,
		auto: <?php echo $vdelay; ?>,
		speed:<?php echo $vspeed; ?>,  
	}); 
});
</script>
	<?php
	}
add_action('wp_head', 'mynewsscript');

class SP_News_setting
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_news_page' ) );
        add_action( 'admin_init', array( $this, 'page_init_news' ) );
    }

    /**
     * Add options page
     */
    public function add_news_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'News Widget Settings', 
            'manage_options', 
            'news-setting-admin', 
            array( $this, 'create_newsadmin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_newsadmin_page()
    {
        // Set class property
        $this->options = get_option( 'NewsWidget_option' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Scrolling News Widget Setting</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'news_option_group' );   
                do_settings_sections( 'news-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init_news()
    {        
        register_setting(
            'news_option_group', // Option group
            'NewsWidget_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Scorlling News Widget Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'news-setting-admin' // Page
        );  

        add_settings_field(
            'news_width', // ID
            'Widget Scrolling Direction (Vertical OR Horizontal) ', // Title 
            array( $this, 'news_width_callback' ), // Callback
            'news-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'news_height', 
            'Number of news visible at a time', 
            array( $this, 'news_height_callback' ), 
            'news-setting-admin', 
            'setting_section_id'
        );      
		add_settings_field(
            'news_delay', // ID
            'Enter delay ', // Title 
            array( $this, 'news_delay_callback' ), // Callback
            'news-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'news_speed', 
            'Enter speed', 
            array( $this, 'news_speed_callback' ), 
            'news-setting-admin', 
            'setting_section_id'
        );     
	
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['news_width'] ) )
            $new_input['news_width'] = absint( $input['news_width'] );

        if( isset( $input['news_height'] ) )
            $new_input['news_height'] = sanitize_text_field( $input['news_height'] );
		
		 if( isset( $input['news_delay'] ) )
            $new_input['news_delay'] = sanitize_text_field( $input['news_delay'] );
			
		 if( isset( $input['news_speed'] ) )
            $new_input['news_speed'] = sanitize_text_field( $input['news_speed'] );	

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function news_width_callback()
    {
        printf(
            '<input type="text" id="news_width" name="NewsWidget_option[news_width]" value="%s" />',
            isset( $this->options['news_width'] ) ? esc_attr( $this->options['news_width']) : ''
        );
		printf(' Enter "0" for <b>Vertical Scrolling</b> and "1" for <b>Horizontal Scrolling</b>');
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function news_height_callback()
    {
        printf(
            '<input type="text" id="news_height" name="NewsWidget_option[news_height]" value="%s" />',
            isset( $this->options['news_height'] ) ? esc_attr( $this->options['news_height']) : ''
        );
		printf(' ie 1, 2, 3, 4 etc');
    }
	 public function news_delay_callback()
    {
        printf(
            '<input type="text" id="news_delay" name="NewsWidget_option[news_delay]" value="%s" />',
            isset( $this->options['news_delay'] ) ? esc_attr( $this->options['news_delay']) : ''
        );
		printf(' ie 500, 1000 milliseconds delay');
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function news_speed_callback()
    {
        printf(
            '<input type="text" id="news_speed" name="NewsWidget_option[news_speed]" value="%s" />',
            isset( $this->options['news_speed'] ) ? esc_attr( $this->options['news_speed']) : ''
        );
		printf(' ie 500, 1000 milliseconds speed');
    }
}

if( is_admin() )
    $my_newssettings_page = new SP_News_setting();
?>
