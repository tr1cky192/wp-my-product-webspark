<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<h2><?php _e( 'My Products', 'wpmpw' ); ?></h2>

<table>
    <thead>
        <tr>
            <th><?php _e( 'Product Name', 'wpmpw' ); ?></th>
            <th><?php _e( 'Quantity', 'wpmpw' ); ?></th>
            <th><?php _e( 'Price', 'wpmpw' ); ?></th>
            <th><?php _e( 'Status', 'wpmpw' ); ?></th>
            <th><?php _e( 'Actions', 'wpmpw' ); ?></th>
        </tr>
    </thead>
    <tbody id="products-list">
        <?php
        // Отримуємо поточну сторінку для пагінації
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $posts_per_page = 2;

        // WP_Query для "Моїх продуктів"
        $args = array(
            'post_type'      => 'product',
            'post_status'    => array( 'publish', 'pending' ),
            'author'         => get_current_user_id(),
            'paged'          => $paged,
            'posts_per_page' => $posts_per_page,
        );

        $query = new WP_Query( $args );

        if ( $query->have_posts() ) :
            while ( $query->have_posts() ) : $query->the_post();
                ?>
                <tr>
                    <td><?php the_title(); ?></td>
                    <td><?php echo get_post_meta( get_the_ID(), '_stock', true ); ?></td>
                    <td><?php echo get_post_meta( get_the_ID(), '_price', true ); ?></td>
                    <td><?php echo get_post_status( get_the_ID() ); ?></td>
                    <td>
                        <a href="<?php echo esc_url( get_edit_post_link( get_the_ID() ) ); ?>"><?php _e( 'Edit', 'wpmpw' ); ?></a> |
                        <a href="<?php echo esc_url( get_delete_post_link( get_the_ID() ) ); ?>"><?php _e( 'Delete', 'wpmpw' ); ?></a>
                    </td>
                </tr>
            <?php endwhile;
        else :
            ?>
            <tr>
                <td colspan="5"><?php _e( 'No products found.', 'wpmpw' ); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div id="pagination-container">
    <?php
    // Пагінація
    $total_pages = $query->max_num_pages;
    if ( $total_pages > 1 ) :
        $current_page = max( 1, get_query_var( 'paged' ) );
        ?>
        <ul class="pagination">
            <?php
            echo paginate_links( array(
                'base'     => add_query_arg( 'paged', '%#%' ),
                'format'   => '',
                'current'  => $current_page,
                'total'    => $total_pages,
                'prev_text'=> __( '&laquo; Previous', 'wpmpw' ),
                'next_text'=> __( 'Next &raquo;', 'wpmpw' ),
                'type'     => 'list',
                'end_size' => 1,
                'mid_size' => 2,
            ) );
            ?>
        </ul>
    <?php endif; ?>
</div>

<?php wp_reset_postdata(); ?>
