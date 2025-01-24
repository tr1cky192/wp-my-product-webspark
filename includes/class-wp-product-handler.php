<?php
require_once __DIR__ . '/class-wpmpw-admin-email.php';

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPMPW_Product_Handler {
    public static function handle_product_form_submission() {
        if ( isset( $_POST['wpmpw_product_submit'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'wpmpw_add_product' ) ) {
            $user_id = get_current_user_id();
            $product_name = sanitize_text_field( $_POST['product_name'] );
            $price = sanitize_text_field( $_POST['price'] );
            $quantity = intval( $_POST['quantity'] );
            $description = wp_kses_post( $_POST['description'] );
            $image_id = intval( $_POST['product_image'] ); 

            if ( empty( $image_id ) ) {
                $image_id = null;
            }

            $post_data = array(
                'post_title'   => $product_name,
                'post_content' => $description,
                'post_status'  => 'pending', 
                'post_type'    => 'product',
                'post_author'  => $user_id,
            );

            $product_id = wp_insert_post( $post_data );

            if ( is_wp_error( $product_id ) ) {
                error_log( 'Product creation failed: ' . $product_id->get_error_message() );
            } else {
                error_log( 'Product created successfully with ID: ' . $product_id );
            }

            // Add price and stock
            update_post_meta( $product_id, '_regular_price', $price );  
            update_post_meta( $product_id, '_price', $price );          
            update_post_meta( $product_id, '_stock', $quantity );       

            // Add product image if exists
            if ( $image_id ) {
                update_post_meta( $product_id, '_product_image', $image_id );
            }

            // Trigger product created action
            error_log('Firing wpmpw_product_created action for product ID: ' . $product_id);
            do_action( 'wpmpw_product_created', $product_id, $user_id ); 
        }
    }
}

function wpmpw_trigger_email_on_product_submission( $product_id, $user_id ) {
    if ( class_exists( 'WPMPW_Email_Admin_New_Product' ) ) {
        $email = new WPMPW_Email_Admin_New_Product();
        error_log( 'Attempting to send email for product ID: ' . $product_id . ' and user ID: ' . $user_id );
        $email->trigger( $product_id, $user_id );
        error_log( 'Email triggered for product ID: ' . $product_id );
    } else {
        error_log('Email class WPMPW_Email_Admin_New_Product not found.');
    }
}

add_action( 'wpmpw_product_created', 'wpmpw_trigger_email_on_product_submission', 20, 2 );
add_action( 'init', [ 'WPMPW_Product_Handler', 'handle_product_form_submission' ] );

// Ensure email class is loaded
error_log('Checking if email class exists: ' . (class_exists('WPMPW_Email_Admin_New_Product') ? 'Yes' : 'No'));
?>
