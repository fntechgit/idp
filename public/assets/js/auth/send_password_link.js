(function( $ ){

    $(document).ready(function($){

        var form = $('#form-send-password-reset-link');

        var validator = form.validate({
            rules: {
                email: {
                    required: true
                },
                'g_recaptcha_hidden': {required: true}
            },
            messages: {
                'g_recaptcha_hidden': { required: 'Please confirm that you are not a robot.'}
            }
        });

        form.submit(function(e){
            var is_valid = $(this).valid();
            if (!is_valid) {
                e.preventDefault();
                return false;
            }
            $('.btn-primary').attr('disabled', 'disabled');
            return true;
        });

    });

// End of closure.
}( jQuery ));