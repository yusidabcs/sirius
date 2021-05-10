$(document).ready(function() {

    $('.mdb-select').materialSelect();
    const jobapplication_link = $('#available_jobs').data('base-url');
    const address_book_id = $('#available_jobs').data('ab');

    $('#table_category_search').on('change', function () {
        $('.category').hide();
        $('#category'+$(this).val()).show();

    });
});
