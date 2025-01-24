<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if ( ! class_exists( 'WC_Email' ) ) {
    return;
}
if ( ! class_exists( 'WPMPW_Email_Admin_New_Product' ) ) {
class WPMPW_Email_Admin_New_Product extends WC_Email {
    public function __construct() {
        
        $this->id             = 'wpmpw_admin_new_product';
        $this->title          = __( 'Нове додавання товару', 'wpmpw' );
        $this->description    = __( 'Цей лист надсилається адміністратору, коли новий товар додано', 'wpmpw' );
        $this->recipient      = get_option( 'admin_email' );
        $this->template_html  = 'emails/admin-product-notification.php';
        $this->template_plain = 'emails/plain/admin-product-notification.php';
        $this->template_base  = plugin_dir_path( __FILE__ ) . '../templates/';

        parent::__construct();
    }

    public function trigger( $product_id, $user_id ) {
        error_log("Attempting to send email for product ID: $product_id");
    

        if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
            error_log('Email not enabled or recipient missing.');
            return;
        }


        $this->object = wc_get_product( $product_id );
    

        $this->placeholders = [
            '{product_name}'   => $this->object->get_name(), 
            '{product_edit}'   => admin_url( "post.php?post=$product_id&action=edit" ),
            '{author_profile}' => admin_url( "user-edit.php?user_id=$user_id" )
        ];

        $email_content = $this->get_content();
        $subject = $this->get_subject();
        $recipient = $this->get_recipient();
        $headers = $this->get_headers();
        $attachments = $this->get_attachments();
    
        error_log("Sending email to $recipient with subject: $subject");
    
        $this->send( $recipient, $subject, $email_content, $headers, $attachments );
        error_log("Email sent for product ID: $product_id");
    }

    public function get_content_html() {
        $product_name = isset( $this->placeholders['{product_name}'] ) ? $this->placeholders['{product_name}'] : '';
        $product_edit = isset( $this->placeholders['{product_edit}'] ) ? $this->placeholders['{product_edit}'] : '';
        $author_profile = isset( $this->placeholders['{author_profile}'] ) ? $this->placeholders['{author_profile}'] : '';

        return wc_get_template_html( $this->template_html, [
            'email_heading'  => $this->get_heading(),
            'product_name'   => $product_name,
            'product_edit'   => $product_edit,
            'author_profile' => $author_profile,
            'email'          => $this
        ], '', $this->template_base );
    }
}
}