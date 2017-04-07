(function($) {

    $.fn.validateRequired = function( options ) {

        // Establish our default settings
        var bool=false;
        var settings = $.extend({
            text         : '',
            errMsg       : 'Harap isi kolom ini',
            errColor     : '#e74c3c',
            color        : '',
            fontStyle    : null,
            complete	 : null
        }, options);

        this.each( function() {
            var input = $(this).val();
            var label_err = $(this).attr("data-label");
            $(label_err).html("");

            if(!validateEmpty(input)){
                $(label_err).css( 'font-size', '12px').html(settings.errMsg);
            }else{
                bool=true;
            }

        });
        return bool;
    };

    $.fn.validateImgType = function( options ) {

        // Establish our default settings
        var bool=false;
        var settings = $.extend({
            text         : '',
            errMsg       : 'Harap isi dengan tipe file jpg / png',
            errColor     : '#e74c3c',
            color        : '',
            fontStyle    : null,
            complete	 : null
        }, options);

        this.each( function() {
            var input = $(this).val();
            var label_err = $(this).attr("data-label");
            $(label_err).html("");

            var type = this.files[0].type;

            if(type!="image/jpeg" && type!="image/png"){
                $(label_err).css( 'font-size', '12px').html(settings.errMsg);
            }else {
                bool=true;
            }
        });
        return bool;
    };

    $.fn.validateMaxSize = function( options ) {

        // Establish our default settings
        var bool=false;
        var settings = $.extend({
            text         : '',
            errMsg       : 'Ukuran file maksimal haruslah ',
            errColor     : '#e74c3c',
            color        : '',
            size         : '2 Mb',
            max_size	 : 1000*1000*2
        }, options);

        this.each( function() {
            var size = this.files[0].size;
            var input = $(this).val();
            var label_err = $(this).attr("data-label");
            $(label_err).html("");

            if(settings.max_size < size){
                $(label_err).css( 'font-size', '12px').html(settings.errMsg+settings.size);
            }else {
                bool=true;
            }
        });
        return bool;
    };

    $.fn.saveData = function( options ) {

        // Establish our default settings
        var settings = $.extend({
            errMsg       : 'Ukuran file maksimal haruslah ',
            errColor     : '#e74c3c',
            url          : '',
            data         : '',
            redirect     : true,
            locationHref : '',
            hrefDuration : 3000
        }, options);

        return this.each( function() {
            $.ajax({
                url: settings.url,
                data: settings.data,
                type: "POST",
                dataType: 'json',
                cache:false,
                contentType: false,
                processData: false,
                beforeSend:function(){
                    $("#load_screen").show();
                },
                success:function(data){
                    if(data.status != 'error') {
                        $("#load_screen").hide();
                        $(".modal").hide();
                        alertify.set('notifier','position', 'bottom-right');
                        alertify.success(data.msg);
                        if(settings.redirect) {
                            window.setTimeout(function () {
                                location.href = settings.locationHref;
                            }, settings.hrefDuration);
                        }
                    }else{
                        $("#load_screen").hide();
                        alertify.set('notifier','position', 'bottom-right');
                        alertify.error(data.msg);
                    }
                },
                error: function(xhr, status, error) {
                    //var err = eval("(" + xhr.responseText + ")");
                    //alertify.error(xhr.responseText);
                    $("#load_screen").hide();
                    alertify.set('notifier','position', 'bottom-right');
                    alertify.error('Maaf terjadi kesalahan server. Harap coba lagi beberapa saat nanti');
                }
            });
        });
    };

    $.fn.deleteData = function( options ) {

        // Establish our default settings
        var settings = $.extend({
            alertMsg     : '',
            alertTitle   : '',
            url          : '',
            data         : '',
            locationHref : ''
        }, options);

        return this.each( function() {
            alertify.confirm(settings.alertMsg,
                function(){
                    // Confirm Action
                    // ajax mulai disini
                    $.ajax({
                        url: settings.url,
                        data: settings.data,
                        type: "POST",
                        dataType: 'json',
                        cache:false,
                        contentType: false,
                        processData: false,
                        beforeSend:function(){
                            $("#load_screen").show();
                        },
                        success:function(data){
                            if(data.status != 'error') {
                                $("#load_screen").hide();
                                alertify.set('notifier','position', 'top-right');
                                alertify.success(data.msg);
                                window.setTimeout( function(){
                                    location.href = settings.locationHref;
                                }, 1500 );
                            }else{
                                $("#load_screen").hide();
                                alertify.set('notifier','position', 'top-right');
                                alertify.error(data.msg);
                            }
                        },
                        error: function(xhr, status, error) {
                            //var err = eval("(" + xhr.responseText + ")");
                            //alertify.error(xhr.responseText);
                            $("#load_screen").hide();
                            alertify.set('notifier','position', 'top-right');
                            alertify.error('Maaf terjadi kesalahan server. Harap coba lagi beberapa saat nanti');
                        }
                    });
                }
            ).setHeader(settings.alertTitle);
        });
    };

    $.fn.validateConfirmPassword = function( options ) {

        // Establish our default settings
        var bool=false;
        var settings = $.extend({
            text         : '',
            errMsg       : 'This password doesn\'t match !',
            compareValue : ''
        }, options);

        this.each( function() {
            var input = $(this).val();
            var label_err = $(this).attr("data-label");
            $(label_err).html("");

            if(settings.compareValue != input){
                $(label_err).css( 'font-size', '12px').html(settings.errMsg);
            }else{
                bool=true;
            }

        });
        return bool;
    };

    $.fn.validateLengthRange = function( options ) {

        // Establish our default settings
        var bool=false;
        var settings = $.extend({
            text         : '',
            errMsg       : '',
            minLength    : '',
            maxLength    : ''
        }, options);

		if(settings.errMsg != ""){
			var message = settings.errMsg;
		}else{
			var message = "Kolom ini harus diisi antara " + settings.minLength +" hingga "+ settings.maxLength+" digit/karakter";
		}

        this.each( function() {
            var input = $(this).val().trim().length;
            var label_err = $(this).attr("data-label");
            $(label_err).html("");

            if(input >= settings.minLength && input <= settings.maxLength){
                bool=true;
            }else{
                $(label_err).css( 'font-size', '12px').html(message);
            }

        });
        return bool;
    };

    $.fn.validateEmailForm = function( options ) {

        // Establish our default settings
        var bool=false;
        var settings = $.extend({
            text         : '',
            errMsg       : 'harap isi dengan format email yang benar'
        }, options);

        this.each( function() {
            var input = $(this).val();
            var label_err = $(this).attr("data-label");
            $(label_err).html("");

            if(!validateEmail(input)){
                $(label_err).css( 'font-size', '12px').html(settings.errMsg);
            }else{
                bool=true;
            }

        });
        return bool;
    };

    $.fn.validatePhoneForm = function( options ) {

        // Establish our default settings
        var bool=false;
        var settings = $.extend({
            text         : '',
            errMsg       : 'harap isi dengan format telepon yang benar'
        }, options);

        this.each( function() {
            var input = $(this).val();
            var label_err = $(this).attr("data-label");
            $(label_err).html("");

            if(!validatePhoneNumber(input)){
                $(label_err).css( 'font-size', '12px').html(settings.errMsg);
            }else{
                bool=true;
            }

        });
        return bool;
    };

    $.fn.validateNumberForm = function( options ) {

        // Establish our default settings
        var bool=false;
        var settings = $.extend({
            text         : '',
            errMsg       : 'harap isi kolom ini hanya dengan angka'
        }, options);

        this.each( function() {
            var input = $(this).val();
            var label_err = $(this).attr("data-label");
            $(label_err).html("");

            if(!validateNumber(input)){
                $(label_err).css( 'font-size', '12px').html(settings.errMsg);
            }else{
                bool=true;
            }

        });
        return bool;
    };
	
	$.fn.validateAlphaPlusSpaceForm = function( options ) {

        // Establish our default settings
        var bool=false;
        var settings = $.extend({
            text         : '',
            errMsg       : 'harap isi kolom ini hanya dengan huruf alphabet'
        }, options);

        this.each( function() {
            var input = $(this).val();
            var label_err = $(this).attr("data-label");
            $(label_err).html("");

            if(!validateAlphaPlusSpace(input)){
                $(label_err).css( 'font-size', '12px').html(settings.errMsg);
            }else{
                bool=true;
            }

        });
        return bool;
    };

    function validateEmpty(input){
        if(input.trim() == "" || input.trim() == null){
            return false;
        }else{
            return true;
        }
    }

    function validateEmail(email)
    {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    function validatePhoneNumber(phone)
    {
        var regex = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/;
        return regex.test(phone);
    }

    function validateNotNumber(notnumber)
    {
        var regex = /^([^0-9]*)$/;
        return regex.test(notnumber);
    }

    function validateNumber(number)
    {
        var regex = /^[0-9]*$/;
        return regex.test(number);
    }
	
	function validateAlphaPlusSpace(alpha)
    {
        var regex = /^[a-z ]+$/i;
        return regex.test(alpha);
    }

    function checkAll(){
        return bool;
    }

}(jQuery));
