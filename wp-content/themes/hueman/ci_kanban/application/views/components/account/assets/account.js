var account = {

    register: {

        submit: function()
        {
            var parameters      = {};
            parameters.accType  = $("#register_profile_type").val(),
            parameters.username = $("#register_username").val(),
            parameters.password = $("#register_password").val();
            parameters.name     = $("#register_name").val();

            $.post('/account/account_register_controller/register', parameters, function(response) {
                response = JSON.parse(response);

                if(response.status == 'success')
                account.register.success(response);
            else
                account.register.error(response);
            })
        },

        success: function(response) {
//            topSiteAlert(response.data);
            window.location.href = '/';
        },

        error: function(response) {
            topSiteAlert(response.data);
        },

        selectProfileType: function(type) {
            $("#loginRegister-header").css({'height':'791px'});

            $("#register_name").attr('placeholder', (type == 'startup' ? 'Startup name' : 'Full name'));


            $("#loginRegister-header h2").hide();

            if(type == 'intern')
                $("#loginRegister-header h1").text('Register as an intern');
            else
                $("#loginRegister-header h1").text('Register as a startup');

            $("#register_profile_type").val(type);
            $("#register_area .accTypeSel").fadeOut('fast', function() {
                $("#register_form").fadeIn();
            });
        },

        clearProfileTypeSelection: function() {
            $("#loginRegister-header").css({'height':'570px'});

            $("#loginRegister-header h1").text('Find interns. Join a startup');
            $("#loginRegister-header h2").show();
            $("#register_form").fadeOut('fast', function() {
                $("#register_area .accTypeSel").fadeIn();
            });
        }
    },


    login: {

        detectSubmit: function(e) {
            if(e.which == 13) account.login.submit()
        },

        submit: function()
        {
            var parameters      = {};
            parameters.username = $("#login_username").val(),
                parameters.password = $("#login_password").val();

            $.post('/account/account_login_controller/login', parameters, function(response) {
//                    response = JSON.parse(response);
                    console.log(response);
                    if(response.status == 'success')
                        account.login.success(response);
                    else
                        account.login.error(response);
                },
                'json'
            );
        },

        success: function(response) {
            window.location.href = '/';
        },

        error: function(response) {
            alert(response.data.msg);
        }
    },


    avatarUpload: {

        onUploadComplete: function(data) {
            $("#avatarUploadArea img").attr('src', data);
            $("#user-menu img").attr('src', data);
        },

        onProgress: function(file, e) {

        },

        onUpload: function() {

        }
    },

    recovery: {

        togglePassRequestTab: function() {
            $("#login_form").toggle();
            $("#recoverPass_form").toggle();
        },


        sendNewPassRequest: function() {
            var parameters      = {};
            parameters.email    = $("#passRecover_email").val();

            $.post('/account/account_recovery_controller/password_sendNew', parameters, function(response) {});
            alert('A new password has been sent to your email');
            account.recovery.togglePassRequestTab();
        }
    }
}
