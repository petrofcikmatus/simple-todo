(function ($, window, document, undefined) {

    // when document ready
    $(document).ready(function () {
        var form = $('#js-form-add');
        var list = $('#js-list');
        var input = form.find('#js-text');

        input.val('').focus();

        $('.js-hide').hide();

        form.on('submit', function (event) {
            event.preventDefault();

            var req = $.ajax({
                url: base_url + '/ajax',
                type: 'POST',
                data: form.serialize(),
                dataType: 'json'
            });

            req.done(function (data) {
                if (data.status === 'success') {
                    $.ajax({url: base_url}).done(function (html) {
                        var newItem = $(html).find('#item-' + data.id);

                        newItem.prependTo(list);

                        input.val('').focus();
                    });
                }
            });
        });

        // input form on keypress
        input.on('keypress', function (event) {
            if (event.which === 13) {
                form.submit();
                return false;
            }
        });

        // edit form
        $('#js-form-edit').find('#js-text').select();

        // delete form
        $('#js-form-delete').on('submit', function (event) {
            return confirm('for sure?');
        });

        // delete form
        $('#js-link-logout').on('click', function (event) {
            return confirm('for sure?');
        });
    });

}(jQuery, window, document));