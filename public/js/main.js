$(document).ready(function () {
    $('#login-form').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            }
        },
        messages: {
            email: {
                required: window.translate('email_required'),
                email: window.translate('email_notvalid'),
            },
            password: {
                required: window.translate('passw_required'),
                minlength: window.translate('passw_minlength')
            }
        }
    });
    
    $('#register-form').validate({
        rules: {
            login: {
                required: true,
                customLogin: true
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                customPassword: true,
                minlength: 6
            }
        },
        messages: {
            login: {
                required: window.translate('login_required')
            },
            email: {
                required: window.translate('email_required'),
                email: window.translate('email_notvalid'),
            },
            password: {
                required: window.translate('passw_required'),
                minlength: window.translate('passw_minlength')
            }
        }
    });
    
    $.validator.addMethod("customPassword", function(value, element) {
        // Define regular expressions
        let
          
            hasACapitalLetter = /[A-Z]/,
            hasALowerCase = /[a-z]/,
            hasADigit = /(?=.*\d)/;

        // Check if the value matches all three rules
        let matchesAllRules = hasACapitalLetter.test(value) && hasALowerCase.test(value) && hasADigit.test(value);

        return this.optional(element) || matchesAllRules;
    }, window.translate('passw_notvalid'));
    
    $.validator.addMethod("customLogin", function(value, element) {
        // Define regular expressions
        let
            regex = /^[a-zA-Z0-9]{3,16}$/;

        // Check if the value matches all three rules
        let matchesAllRules = regex.test(value);

        return this.optional(element) || matchesAllRules;
    }, window.translate('login_notvalid'));
    
    $('.js-event-show-form').on('click', function(){
       
        if($('.create-item-form:eq(0)').is(':visible')) return false;

        let 
            template = $("#addNewTemplate").template(),
            addTo = '.js-event-put-template-here',
            data = {
                firstName: window.translate('first_name_default'),
                lastName: window.translate('last_name_default'),
                selectFile: window.translate('select_file'),
                phone: window.translate('phone'),
                email: window.translate('email_new'),
                storeItem: window.translate('store_item'),
                csrf: getCsrf()
            },
            output = $.tmpl(template, data);

       output.prependTo(addTo);
       initValidateForForm();
    });
    
    /**
     * Obtain the sequrity token
     * @returns {jQuery}
     */
    function getCsrf() {
        return $("meta[name='csrf-token']").attr("content");
    }
    
    /**
     * Hide the alert about missing phone book records.
     * @returns {undefined}
     */
    function hideNotFoudAlert() {
        $('.js-event-hide-allert').hide();
    }
    
    /**
     * Bind data and generate a jquery template for the newly created record
     * @param {object} ajaxData
     * @returns {undefined}
     */
    function renderInsertedItem(ajaxData) {
        let
            template = $("#itemTemplate").template(),
            addTo = '.js-event-put-template-here',
            defaultPictureURL = '/img/user_default_picture.png',
            data = {
                itemId: ajaxData.res_create.insert_id,
        
                picture: ajaxData.stored_data.picture ? ajaxData.stored_data.picture : defaultPictureURL,
                firstName: ajaxData.stored_data.first_name,
                lastName: ajaxData.stored_data.last_name,
                phone: ajaxData.stored_data.phone,
                email: ajaxData.stored_data.email  
            },
            output = $.tmpl(template, data);

       output.prependTo(addTo);
    }
    
    /**
     * After inserting the record, remove the form.
     * @param {object} form
     * @returns {undefined}
     */
    function removeForm(form) {
        $(form).remove();
    }
    
    $(document).on('submit', '#create-new-item', function(e) {
        var 
            formData = new FormData(this),
            form = this;
            
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            data: formData,
            contentType: false,
            processData: false, 
            success: function(data) {
                if(data.res_create.res === true){
                    if (data.errors != null && data.errors.length > 0) {
                        alert(data.errors);
                    }
                    removeForm(form);
                    hideNotFoudAlert();
                    renderInsertedItem(data);
                }
                if(data.res_create === false){
                    if (data.errors != null && data.errors.length > 0) {
                        alert(data.errors);

                        return false;
                    }
                }
            },
            error: function(xhr, status, error) {
                if (typeof this.statusCode[xhr.status] != 'undefined') {
                    return false;
                }
       
                alert(window.translate('error_ajax_store') + xhr.status + window.translate('error_ajax_store_part') + xhr.statusText);            
            }, 
            statusCode: {
                0: function(){
                    alert(window.translate('error_ajax_store_network'));
                                  
                    return false;
                }
            }
        });
    });
    
    $(document).on('click', '.js-event-remove-new-record', function(e) {
        let conf = window.confirm(window.translate('confirm'));
        
        if(conf){
            let item = $(this).closest('#create-new-item');
            item.remove();
        }
        
    });
    
    $(document).on('click', '.js-event-remove', function(e) {
        let conf = window.confirm(window.translate('confirm'));
        
        if(conf){
            let 
                item = $(this).closest('.book-item'),
                id = item.attr('data-item-id');
            
            $.ajax({
                url: "/removeItem",
                type: "POST",
                async: true,
                data: ({
                    item_id: id,
                    csrf: getCsrf()
                }),
                dataType: "json",
                success: function(data) {
                    if(data.res_delete.res === true){
                        if (data.errors != null && data.errors.length > 0) {
                            alert(data.errors);
                        }
                        item.remove();
                    }
                    if(data.res_delete === false){
                        if (data.errors != null && data.errors.length > 0) {
                            alert(data.errors);

                            return false;
                        }
                    }
                },
                error: function(xhr, status, error) {
                    if (typeof this.statusCode[xhr.status] != 'undefined') {
                        return false;
                    }
       
                    alert(window.translate('error_ajax_remove') + xhr.status + window.translate('error_ajax_store_part') + xhr.statusText);            
                }, 
                statusCode: {
                    0: function(){
                        alert(window.translate('error_ajax_remove_network'));
                                  
                        return false;
                    }
                }
            });     
        } 
    });
    
    /**
     * Initiate jquery validation for the form in order to create a new phone book record.
     * @returns {undefined}
     */
    function initValidateForForm(){
        $('#create-new-item').validate({
            rules: {
                first_name: {
                    required: true,
                    customTextInput: true
                },
                last_name: {
                    required: true,
                    customTextInput: true
                },
                phone: {
                    required: true,
                    customPhoneUA: true
                },
                email: {
                    email: true
                },
                user_picture: {
                    accept: "image/jpeg, image/png",
                    filesize: 5
                }
            },
            messages: {
                first_name: {
                    required: window.translate('first_name_required'),
                    customTextInput: window.translate('first_name_invalid')
                },
                last_name: {
                    required: window.translate('last_name_required'),
                    customTextInput: window.translate('last_name_invalid')
                },
                phone: {
                    required: window.translate('phone_required'),
                    customPhoneUA: window.translate('phone_invalid')
                },
                email: {
                    email: window.translate('email_notvalid')
                },
                user_picture: {
                    accept: window.translate('user_picture_invalid'),
                    filesize: window.translate('user_picture_invalid'),
                }
               
            }
        });
        $('#phone').mask('+380000000000');
    }
    
    $.validator.addMethod("customTextInput", function(value, element) {
        // Define regular expressions
        let
            regex = /^[a-zA-Zа-яА-ЯіїєёІЇЄЁ\_\-0-9 ]+$/;

        // Check if the value matches all three rules
        let matchesAllRules = regex.test(value);

        return this.optional(element) || matchesAllRules;
    }, window.translate('text_input_invalid'));
    
    $.validator.addMethod("customPhoneUA", function(phone_number, element) {
        // Define regular expressions
        phone_number = phone_number.replace( /\s+/g, "" );
        
	return this.optional( element ) || phone_number.length > 9 &&
		phone_number.match( /^\+380\d{9}$/ );
    }, window.translate('phone_invalid'));
    
    $.validator.addMethod('filesize', function(value, element, param) {
        var fileInput = $(element);

        if (fileInput.get(0).files.length > 0) {
            var 
                fileSize = fileInput.get(0).files[0].size,
                maxSize = param * 1024 * 1024;

            return fileSize <= maxSize;
        }

        return true;
    }, window.translate('user_picture_invalid')); 
});