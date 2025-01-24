jQuery(function($) {
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        
        var page = $(this).attr('href').split('paged=')[1];
        
        var data = {
            action: 'wpmpw_load_products',
            paged: page,
        };

        $.post(wpmpw_ajax.ajaxurl, data, function(response) {
            var data = JSON.parse(response);
            $('#products-list').html(data.products);
            $('#pagination-container').html(data.pagination);
        });
    });
});
