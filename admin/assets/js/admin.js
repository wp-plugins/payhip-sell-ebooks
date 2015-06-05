var valid;

(function ( $ ) {
    "use strict";
    
    $(function () {        
        
        // form submit action
        $('#pfSaveSettings').bind('submit', function(e) {
            valid = true;
                                                                                                                                          
            $('.pfFormReq').each(function(){                       
                if($(this).val()==null || $(this).val()=='' || $(this).val()==' '){                            
                    $(this).addClass('pfError');
                    valid = false; 
                } else { 
                    $(this).removeClass('pfError');
                }                        
            });
            
            if (valid == true) {
            // continue
            } else {
                // scroll to top of error
                if($('.pfError').length > 0) {
                    var firstErrorTop = $('.pfError').eq(0).offset().top;
                    $("html, body").animate({
                        scrollTop: firstErrorTop-100
                    },1000,'swing');
                }
                e.preventDefault();
            }
        });        

    });

}(jQuery));

function pfValid_payhip_user(elementD,jQ,eee) {
    
    var formHtml = elementD.closest('form');
    var elementVal = elementD.val();    
    
    if(elementVal != '') {
        valid = false;
        jQ.ajax({
            type: 'POST',
            url: pf_json_admin_data .ajaxURL,
            dataType : "json",
            data: {
                userNa : encodeURIComponent(elementD.val()),
                action: 'pfPayhipData'
            },
            success: function(data) {
                if (data.status == 'success') { // success
                    valid = true;
                    if (data.count == '0') {
                        valid = false;
                        elementD.addClass('pfError');
                        if(formHtml.length) {
                            formHtml.prepend("<div class='updated'><p>Payhip username is correct but 0 items found on web.</p></div>");
                            formHtml.find(".updated").delay(5000).fadeOut(500);
                        }
                    } else {
                        valid = true;
                        elementD.off('submit');
                    }
                } else if (data.status == 'failure') {
                    valid = false;
                    elementD.addClass('pfError');
                    if(formHtml.length) {
                        formHtml.prepend("<div class='updated'><p>Payhip username is wrong.</p></div>");
                        formHtml.find(".updated").delay(5000).fadeOut(500);
                    }
                }
                console.log('success ' + valid);
            },
            complete: function(data) {
                var parsePFDate = JSON.parse(data.responseText);
                console.log('status : ' + parsePFDate.status + ' & count : ' + parsePFDate.count);
                if (parsePFDate.status == 'success' && parsePFDate.count != '0') {
                    valid = true;
                    console.log('complete ' + valid);
                }
            }
        })
    }
    
    
}