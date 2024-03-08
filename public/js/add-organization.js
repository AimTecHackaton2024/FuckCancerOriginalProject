const $perexInput = $('textarea[name="perex"]');

if ($perexInput.length) {
    $perexInput.after('<span class="input-limit-indicator"><span class="input-limit-indicator__current">0</span> / '+ $perexInput.data('max') +'</span>');

    $perexInput.on('input', function() {
        const valLength = $(this).val().length;
        const limit = $(this).data('max');
        const $indicator = $(this).parent().find('.input-limit-indicator');

        $indicator.find('.input-limit-indicator__current').text(valLength);
        $(this).removeClass('input-limit-warning input-limit-reached');

        if (valLength > limit) {
            $(this).addClass('input-limit-reached');
        } else if (valLength > limit - 50) {
            $(this).addClass('input-limit-warning');
        }
    }).trigger('input');
}


$('input').iCheck({
    checkboxClass: 'icheckbox_minimal',
    radioClass: 'iradio_minimal'
});