
<h2><?php _e( 'Add New Product', 'wpmpw' ); ?></h2>

<form method="post" enctype="multipart/form-data">
    <?php wp_nonce_field( 'wpmpw_add_product' ); ?>

    <label for="product_name"><?php _e( 'Product Name', 'wpmpw' ); ?></label>
    <input type="text" id="product_name" name="product_name" required />

    <label for="price"><?php _e( 'Price', 'wpmpw' ); ?></label>
    <input type="text" id="price" name="price" required />

    <label for="quantity"><?php _e( 'Quantity', 'wpmpw' ); ?></label>
    <input type="number" id="quantity" name="quantity" required />

    <label for="description"><?php _e( 'Product Description', 'wpmpw' ); ?></label>
    <?php wp_editor( '', 'description', array( 'textarea_name' => 'description' ) ); ?>

    <label for="product_image"><?php _e( 'Product Image', 'wpmpw' ); ?></label>
    <input type="text" id="product_image" name="product_image" value="" />
    <button type="button" class="button" id="upload_image_button"><?php _e( 'Select Image', 'wpmpw' ); ?></button>

    <button type="submit" name="wpmpw_product_submit"><?php _e('Submit Product', 'wpmpw'); ?></button>
</form>

<script type="text/javascript">
    jQuery(document).ready(function($){
        var mediaUploader;

        $('#upload_image_button').click(function(e) {
            e.preventDefault();

            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: '<?php _e( 'Select Product Image', 'wpmpw' ); ?>',
                button: {
                    text: '<?php _e( 'Select Image', 'wpmpw' ); ?>'
                },
                multiple: false
            });

            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#product_image').val(attachment.id);
            });

            mediaUploader.open();
        });
    });
</script>
