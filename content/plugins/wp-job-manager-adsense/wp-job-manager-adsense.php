<?php
/**
 * Plugin Name: WP Job Manager - AdSense
 * Plugin URI:  http://remicorson.com
 * Description: Add AdSense ads between jobs
 * Author:      Remi Corson
 * Author URI:  http://remicorson.com
 * Version:     1.0
 * Text Domain: job_manager_adsense
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WP_Job_Manager_Adsense {

	private static $instance;

	public static function instance() {
		if ( ! isset ( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Class Constructor
	 *
	 * @since 1.0
	*/
	public function __construct() {
		$this->setup_actions();
	}

	/**
	 * Setup Actions
	 *
	 * @since 1.0
	*/
	private function setup_actions() {
		add_filter( 'job_manager_settings', array( $this, 'job_manager_settings' ) );
		$this->load_textdomain();
		$this->remove_jobs_shortcode();
		$this->add_jobs_shortcode();
	}
	
	/**
	 * Loads the plugin language files
	 *
	 * @since 1.0
	 */
	public function load_textdomain() {
		// Traditional WordPress plugin locale filter
		$locale        = apply_filters( 'plugin_locale', get_locale(), $this->domain );
		$mofile        = sprintf( '%1$s-%2$s.mo', $this->domain, $locale );

		// Setup paths to current locale file
		$mofile_local  = $this->lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/' . $this->domain . '/' . $mofile;

		// Look in global /wp-content/languages/job_manager_adsense folder
		if ( file_exists( $mofile_global ) ) {
			return load_textdomain( $this->domain, $mofile_global );

		// Look in local /wp-content/plugins/job_manager_adsense/languages/ folder
		} elseif ( file_exists( $mofile_local ) ) {
			return load_textdomain( $this->domain, $mofile_local );
		}

		return false;
	}

	/**
	 * Add New Settings Tab
	 *
	 * @since 1.0
	*/
	public function job_manager_settings( $settings ) {
		$settings[ 'job_adsense' ] = array(
			__( 'AdSense', 'job_manager_adsense' ),
			$this->create_options()
		);

		return $settings;
	}

	/**
	 * Create New Options
	 *
	 * @since 1.0
	*/
	private function create_options() {

		$options = array();

		$options[] = array(
			'name' 		  => 'job_manager_adsense_step',
			'std' 		  => '2',
			'placeholder' => '',
			'label' 	  => __( 'Number of jobs between Ads', 'job_manager_adsense' ),
			'desc'		  => __( 'Insert a number', 'job_manager_adsense' )
		);
		
		$options[] = array(
			'name' 		  => 'job_manager_adsense_code',
			'type' 		  => 'textarea',
			'std' 		  => '',
			'placeholder' => '',
			'label' 	  => __( 'AdSense Ad Code', 'job_manager_adsense' ),
			'desc'		  => __( 'Paste the AdSense Ad code here (Vertical ad is recommended, eg: 468 x 60px)', 'job_manager_adsense' )
		);

		return $options;
	}
	
	/**
	 * remove_jobs_shortcode function.
	 *
	 * @since 1.0
	 */
	function remove_jobs_shortcode() {
		remove_shortcode( 'jobs', array( 'WP_Job_Manager_Shortcodes', 'output_jobs' ) );
	}
	
	/**
	 * add_jobs_shortcode function.
	 *
	 * @since 1.0
	 */
	function add_jobs_shortcode() {
		add_shortcode( 'jobs', array( $this, 'outputs_job_adsense' ) );
	}
	
	/**
	 * output_jobs function.
	 *
	 * @param mixed $atts
	 * @since 1.0
	 */
	function outputs_job_adsense( $atts ) {
		global $job_manager;

		ob_start();

		extract( $atts = shortcode_atts( apply_filters( 'job_manager_output_jobs_defaults', array(
			'per_page'        => get_option( 'job_manager_per_page' ),
			'orderby'         => 'featured',
			'order'           => 'DESC',
			'show_filters'    => true,
			'show_categories' => get_option( 'job_manager_enable_categories' ),
			'categories'      => '',
			'job_types'       => '',
			'location'        => '', 
			'keywords'        => ''
		) ), $atts ) );

		$categories = array_filter( array_map( 'trim', explode( ',', $categories ) ) );
		$job_types  = array_filter( array_map( 'trim', explode( ',', $job_types ) ) );

		// Get keywords and location from querystring if set
		if ( ! empty( $_GET['search_keywords'] ) ) {
			$keywords = sanitize_text_field( $_GET['search_keywords'] );
		}
		if ( ! empty( $_GET['search_location'] ) ) {
			$location = sanitize_text_field( $_GET['search_location'] );
		}

		if ( $show_filters && $show_filters !== 'false' ) {

			get_job_manager_template( 'job-filters.php', array( 'per_page' => $per_page, 'orderby' => $orderby, 'order' => $order, 'show_categories' => $show_categories, 'categories' => $categories, 'job_types' => $job_types, 'atts' => $atts, 'location' => $location, 'keywords' => $keywords ) );

			?><ul class="job_listings"></ul><a class="load_more_jobs" href="#" style="display:none;"><strong><?php _e( 'Load more job listings', 'wp-job-manager' ); ?></strong></a><?php

		} else {

			$jobs = get_job_listings( apply_filters( 'job_manager_output_jobs_args', array(
				'search_location'   => $location,
				'search_keywords'   => $keywords,
				'search_categories' => $categories,
				'job_types'         => $job_types,
				'orderby'           => $orderby,
				'order'             => $order,
				'posts_per_page'    => $per_page
			) ) );

			if ( $jobs->have_posts() ) : ?>
				<?php $i = 1; ?>
				<ul class="job_listings">

					<?php while ( $jobs->have_posts() ) : $jobs->the_post();
						
						$i++;
						
						get_job_manager_template_part( 'content', 'job_listing' );
						
						if( $i > get_option( 'job_manager_adsense_step' ) ) { ?>

							<?php echo '<div style="width: 100%; text-align: center;">' . get_option( 'job_manager_adsense_code' ) . '</div>'; ?>
							
						<?php $i = 1; }
					endwhile; ?>

				</ul>

				<?php if ( $jobs->found_posts > $per_page ) : ?>

					<?php wp_enqueue_script( 'wp-job-manager-ajax-filters' ); ?>

					<a class="load_more_jobs" href="#"><strong><?php _e( 'Load more job listings', 'wp-job-manager' ); ?></strong></a>

				<?php endif; ?>

			<?php endif;

			wp_reset_postdata();
		}

		return '<div class="job_listings" data-location="' . esc_attr( $location ) . '" data-keywords="' . esc_attr( $keywords ) . '" data-show_filters="' . ( $show_filters && $show_filters !== 'false' ? 1 : 0 ) . '" data-per_page="' . esc_attr( $per_page ) . '" data-orderby="' . esc_attr( $orderby ) . '" data-order="' . esc_attr( $order ) . '" data-categories="' . esc_attr( implode( ',', $categories ) ) . '">' . ob_get_clean() . '</div>';
	}

}

add_action( 'init', array( 'WP_Job_Manager_Adsense', 'instance' ) );