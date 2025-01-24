<table cellspacing="0" cellpadding="10" border="0" width="100%">
    <tr>
        <td>
            <h2><?php echo esc_html( $email_heading ); ?></h2>
            <p><?php _e( 'Надіслано новий продукт:', 'wpmpw' ); ?></p>
            <p><strong><?php _e( 'Назва продукту:', 'wpmpw' ); ?></strong> <?php echo esc_html( $product_name ); ?></p>
            <p><strong><?php _e( 'Зміни продукту:', 'wpmpw' ); ?></strong> <a href="<?php echo esc_url( $product_edit ); ?>"><?php _e( 'Click here', 'wpmpw' ); ?></a></p>
            <p><strong><?php _e( 'Профіль автора:', 'wpmpw' ); ?></strong> <a href="<?php echo esc_url( $author_profile ); ?>"><?php _e( 'View Author', 'wpmpw' ); ?></a></p>
        </td>
    </tr>
</table>

<?php do_action( 'woocommerce_email_footer', $email ); ?>