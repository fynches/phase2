$("#global_setting").validate({
        ignore: [],
        highlight: function (element) {
            $(element).parent('div').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).parent('div').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block help-block-error',
        errorElement: 'div',
                rules: {
                    secret_key: {required: true},
                    publish_key: {required: true},
                    commission: {required: true},
                    fb_client_id: {required: true},
                    fb_client_secret: {required: true},
                    fb_redirect: {required: true},
                    google_plus_client_id: {required: true},
                    google_plus_secret: {required: true},
                    google_plus_redirect: {required: true}
                    
                },
        messages: {
            secret_key: {required: "Please enter stripe secret key."},
            publish_key: {required: "Please enter stripe publish key."},
            commission: {required: "Please enter commision."},
            fb_client_id: {required: "Please enter facebook client id."},
            fb_client_secret: {required: "Please enter facebook secret id."},
            fb_redirect: {required: "Please enter enter facebook redirect url."},
            google_plus_client_id: {required: "Please enter google plus client id."},
            google_plus_secret: {required: "Please enter google plus secret id."},
            google_plus_redirect: {required: "Please enter google plus redirect url."}
        },
        success: function (element) {
            $(element).parent('.form-group').removeClass('has-error');
        }
    });