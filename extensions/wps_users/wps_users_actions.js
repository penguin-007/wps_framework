(function ($) {
    $('.fn_register__form').submit(function () {
        var form = $(this);
        var btn = form.find('button');
        var data = {};
        data.action = 'addUser';
        data.data = form.serialize();
        $.ajax({
            url: theme_ajax.url,
            dataType: 'json',
            type: 'POST',
            data: data,
            beforeSend: function () {
                form.find('.fn_form_errors div').hide();
                btn.prop("disabled", true);
            },
            success: function (data) {
                btn.prop("disabled", false);
                console.log(data);
                if (data.success) {
                    alert('Регистрация успешна');
                    window.location.href = '/user/';
                } else {
                    console.error('register_error');
                    $.each(data.errors, function (key, value) {
                        form.find('.fn_form_errors .fn_' + value).show();
                    });
                }
            },
            error: function (data) {
            }
        });
        return false;
    });


    /* авторизация */
    $('.fn_auth__form').submit(function () {
        var form = $(this);
        var btn = form.find('button');
        var data = {};
        data.action = 'authUser';
        data.data = form.serialize();
        $.ajax({
            url: theme_ajax.url,
            dataType: 'json',
            type: 'POST',
            data: data,
            beforeSend: function () {
                form.find('.fn_form_errors div').hide();
                btn.prop("disabled", true);
            },
            success: function (data) {
                btn.prop("disabled", false);
                console.log(data);
                if (data.success) {
                    alert('Авторизация успешна');
                    window.location.href = '/user/';
                } else {
                    console.error('auth_error');
                    $.each(data.errors, function (key, value) {
                        console.log(value);
                        form.find('.fn_form_errors .fn_' + value).show();
                    });
                }
            },
            error: function (data) {
            }
        });
        return false;
    });

    /* верификация в личном кабинете */
    $(document).on('click', '.fn_verify', function () {
        var data = {};
        data.action = 'setVerify';
        data.verify_code = $('.fn_verify_code').val();
        $.ajax({
            url: theme_ajax.url,
            dataType: 'json',
            type: 'POST',
            data: data,
            beforeSend: function () {
                $('.fn_verify_error').hide();
            },
            success: function (data) {
                console.log(data);
                if (data.success) {
                    $('.fn_verify_form').hide();
                    window.location.reload(true);
                } else {
                    $('.fn_verify_error').show();
                }
            },
            error: function (data) {
            }
        });
        return false;
    });


    /* генерация нового кода смс*/
    $(document).on('click', '.fn_recode', function () {
        var data = {};
        data.action = 'reGenerateCode';
        data.user_phone = $('.fn_new_phone').val();
        $.ajax({
            url: theme_ajax.url,
            dataType: 'json',
            type: 'POST',
            data: data,
            beforeSend: function () {

            },
            success: function (data) {
                console.log(data);
                if (data.success) {
                    alert('смс отправлена на указанный номер телефона');
                } else {
                }
            },
            error: function (data) {
            }
        });
        return false;
    });


    $(".fn__same_password_on").on('keyup',function () {
        var pass_in = $(".fn__same_password_in").val();
        var pass_on = $(this).val();
        var error_s = $('.pass_same_error');
        if (pass_in === pass_on) {
            hide_error();
        } else {
            show_error();
        }

        function show_error() {
            error_s.show();
        }

        function hide_error() {
            error_s.hide();
        }
    });

    /*Загрузка фото и ее кроп*/
    $('#upload').on('change', function () {
        readFile(this);
    });
    $('.upload-result').on('click', function (ev) {
        $uploadCrop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (resp) {
            $('.fn_user_avatar').val(resp);
            $(".fn_delete_avatar").val(1);
        });
    });
    $uploadCrop = $('#upload-demo').croppie({
        enableExif: true,
        viewport: {
            width: 200,
            height: 200,
            showZoomer: false,
            enableResize: true,
            enableOrientation: true,
            mouseWheelZoom: 'ctrl'
        },
        boundary: {
            width: 300,
            height: 300
        }
    });

    function readFile(input) {
        $('.fn_resize_canvas').fadeIn(500);
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.upload-demo').addClass('ready');
                $uploadCrop.croppie('bind', {
                    url: e.target.result
                }).then(function () {
                    console.log('jQuery bind complete');
                });
            };

            reader.readAsDataURL(input.files[0]);
        }
        else {
            swal("Sorry - you're browser doesn't support the FileReader API");
        }
    }

})(jQuery);

function socialAuth(token) {
    console.log(token);
    console.log('test');
    $.getJSON("//ulogin.ru/token.php?host=" +
        encodeURIComponent(location.toString()) + "&token=" + token + "&callback=?",
        function (res) {
            console.log(res);
            res = $.parseJSON(res.toString());
            console.log(res);
            if (!res.error) {
                console.log(res);
                var data = {};
                data.action = 'socialAuth';
                user_data = new Array();
                var password = getRandomInt(100000, 999999);

                var user_phone = res.phone != 'undefined' ? res.phone : res.email;
                console.log(typeof res.phone);
                if(typeof res.phone === 'undefined') {
                    user_phone = res.email;
                }

                var string_data = 'user_email=' + res.email + '&user_name=' + res.first_name + ' ' + res.last_name + '&user_phone=' + user_phone + '&user_password=' + password;
                data.data = string_data;
                data.social = 1;
                console.log(data);
                $.ajax({
                    url: theme_ajax.url,
                    dataType: 'json',
                    type: 'POST',
                    data: data,
                    beforeSend: function () {
                        //form.find('.fn_form_errors div').hide();
                    },
                    success: function (data) {
                        console.log(data);
                        if (data.success) {
                            alert('Авторизация успешна');
                            window.location.href = '/user/';
                        } else {
                            console.error('register_error');
                        }
                    },
                    error: function (data) {
                    }
                });

            }
        });
}
function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min)) + min; //The maximum is exclusive and the minimum is inclusive
}