<?php
/**
 * Plugin Name: WP My Product WebSpark
 * Description: Кастомний плагін для додавання та редагування продуктів через сторінку My Account.
 * Version: 1.0
 * Author: tr1cky
 * Text Domain: wpmpw
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

if ( ! class_exists( 'WooCommerce' ) ) {

    return;
}


require_once __DIR__ . '/includes/class-wp-product-handler.php';
require_once __DIR__ . '/includes/ajax.php';
function wpmpw_enqueue_assets() {

    wp_enqueue_script( 'wpmpw-pagination', __DIR__ . '/assets/js/pagination.js', array( 'jquery' ), null, true );

    wp_localize_script( 'wpmpw-pagination', 'wpmpw_ajax', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
    ));
}
add_action( 'wp_enqueue_scripts', 'wpmpw_enqueue_assets' );
function wpmpw_add_account_pages() {
    $endpoint_add_product = 'add-product';
    $endpoint_my_products = 'my-products';

    add_rewrite_endpoint( $endpoint_add_product, EP_PAGES );
    add_rewrite_endpoint( $endpoint_my_products, EP_PAGES | EP_PERMALINK );
    add_filter( 'woocommerce_account_menu_items', function( $items ) {
        $items['add-product'] = __( 'Add Product', 'wpmpw' );
        $items['my-products'] = __( 'My Products', 'wpmpw' );
        return $items;
    });


    add_action( 'woocommerce_account_' .  $endpoint_add_product . '_endpoint', function() {
        include __DIR__ . '/templates/add-product-form.php';
    });

    add_action( 'woocommerce_account_' .  $endpoint_my_products . '_endpoint', function() {
        include __DIR__ . '/templates/my-products-table.php';
    });
}


add_action( 'init', 'wpmpw_add_account_pages' );

function wpmpw_register_email( $emails ) {
    require_once __DIR__. '/includes/class-wpmpw-admin-email.php';
    if ( class_exists( 'WPMPW_Email_Admin_New_Product' ) ) {
        $emails['WPMPW_Email_Admin_New_Product'] = new WPMPW_Email_Admin_New_Product();
    }
    return $emails;
}
add_action( 'woocommerce_email_classes', 'wpmpw_register_email',);

