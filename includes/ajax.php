<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_ajax_wpmpw_load_products', 'wpmpw_load_products' );
add_action( 'wp_ajax_nopriv_wpmpw_load_products', 'wpmpw_load_products' );

function wpmpw_load_products() {
    $paged = isset( $_POST['paged'] ) ? $_POST['paged'] : 1;
    $posts_per_page = 2;


    $args = array(
        'post_type'      => 'product',
        'post_status'    => array( 'publish', 'pending' ),
        'author'         => get_current_user_id(),
        'paged'          => $paged,
        'posts_per_page' => $posts_per_page,
    );

    $query = new WP_Query( $args );


    if ( $query->have_posts() ) :
        ob_start();
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
        $products = ob_get_clean();
    else :
        $products = '<tr><td colspan="5">' . __( 'No products found.', 'wpmpw' ) . '</td></tr>';
    endif;


    $total_pages = $query->max_num_pages;
    if ( $total_pages > 1 ) :
        $current_page = max( 1, $paged );
        ob_start();
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
        <?php
        $pagination = ob_get_clean();
    else :
        $pagination = '';
    endif;


    echo json_encode( array(
        'products'  => $products,
        'pagination' => $pagination,
    ) );

    wp_die();
}
