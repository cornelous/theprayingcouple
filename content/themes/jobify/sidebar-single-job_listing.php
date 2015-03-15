<?php
/**
 *
 */

if ( 'top' == jobify_theme_mod( 'jobify_listings', 'jobify_listings_display_area' ) )
	return;

$args = array(
	'before_widget' => '<aside class="job_listing-widget default-widget">',
	'after_widget'  => '</aside>',
	'before_title'  => '<h3 class="job_listing-widget-title">',
	'after_title'   => '</h3>'
);
?>

<?php do_action( 'single_job_listing_info_start' ); ?>

<div class="job-meta col-md-2 col-sm-6 col-xs-12">

	<?php do_action( 'single_job_listing_info_before' ); ?>

	<?php if ( ! is_active_sidebar( 'sidebar-single-job_listing' ) ) : ?>

		<?php the_widget( 'Jobify_Widget_Job_Company_Logo', array(), $args ); ?>

		<?php the_widget( 'Jobify_Widget_Job_Type', array(), $args ); ?>

		<?php the_widget( 'Jobify_Widget_Job_Apply', array(), $args ); ?>

		<?php the_widget( 'Jobify_Widget_Job_Company_Social', array( 'title' => __( 'Company Social', 'jobify' ) ), $args ); ?>

		<?php the_widget( 'Jobify_Widget_Job_Categories', array(), $args ); ?>

		<?php the_widget( 'Jobify_Widget_Job_More_Jobs', array(), $args ); ?>

		<?php the_widget( 'Jobify_Widget_Job_Share', array(), $args ); ?>

	<?php else : ?>
		<?php dynamic_sidebar( 'sidebar-single-job_listing' ); ?>
	<?php endif; ?>

	<?php do_action( 'single_job_listing_info_after' ); ?>
    
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- 336x280 -->
    <ins class="adsbygoogle"
         style="display:inline-block;width:336px;height:280px"
         data-ad-client="ca-pub-8405479344707225"
         data-ad-slot="1079671995"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>

</div>

<?php do_action( 'single_job_listing_info_end' ); ?>