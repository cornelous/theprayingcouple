<?php
/**
 * Jobify Child Theme
 *
 * Place any custom functionality/code snippets here.
 *
 * @since Jobify Child 1.0.0
 */


function jobify_child_styles() {
    wp_enqueue_style( 'jobify-child', get_stylesheet_uri() );
}
add_action( 'wp

_enqueue_scripts', 'jobify_child_styles', 20 );

function my_login_logo_one() {
    ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(http://localhost/content/images/cv.jpg);
            padding-bottom: 30px;
        }
    </style>
<?php
} add_action( 'login_enqueue_scripts', 'my_login_logo_one' );