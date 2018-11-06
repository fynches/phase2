var html_addimage = $(".file_input_div").html();

$(document).ready(function () {  
var amt_recevied= $('#total_amt_recevied').text();
	    
	CKEDITOR.replace('description',
    {
        toolbar: 'Basic',  
        uiColor: '#9AB8F3'
    });

    CKEDITOR.on('instanceReady', function () {
        $.each(CKEDITOR.instances, function (instance) {
            CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
            CKEDITOR.instances[instance].document.on("paste", CK_jQ);
            CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
            CKEDITOR.instances[instance].document.on("blur", CK_jQ);
            CKEDITOR.instances[instance].document.on("change", CK_jQ);
        });
    }); 
    //ckeditor juery validation remove when user type any thing into it
    function CK_jQ() { 
        for (instance in CKEDITOR.instances) {
            var str = CKEDITOR.instances[instance].getData();
            if(getPlainText(str)!=""){
                $("#description-error").remove();
            } 
            CKEDITOR.instances[instance].updateElement();
        }
    }
    //ckeditor remove space trim date when user type in ckeditor and ignore p tag for checking
    function getPlainText( strSrc ) {
        var resultStr = "";
        
        // Ignore the <p> tag if it is in very start of the text
        if(strSrc.indexOf('<p>') == 0)
            resultStr = strSrc.substring(3); 
            resultStr = resultStr.replace(/<p>/gi, "\r\n\r\n");
            // Replace <br /> with one newline
            resultStr = resultStr.replace(/<br \/>/gi, "\r\n");
            resultStr = resultStr.replace(/<br>/gi, "\r\n"); 
             
            // Strip off other HTML tags. 
            resultStr = resultStr.replace(/\r?\n|\r/gm," "); // remove line breaks   
            resultStr = resultStr.replace(/\s\s+/g, " ").trim(); 
            return  resultStr.replace( /<[^<|>]+?>/gi,'' );
    }


    $('#experience').validate({
        ignore: [],   
        rules: {
            event_id: "required",
            exp_name: "required",
            //gift_needed: { required: true,min: $('#actual_received_amt').text()},
            
            gift_needed: { required: true, min: function(){
                        if($('#actual_received_amt').text() < $('#actual_gift_needed').text()){
                        	return parseFloat($('#actual_received_amt').text());
                        }else{
                        	return parseFloat($('#actual_gift_needed').text());
                        }
                  }},
            
            image: {extension: 'jpg|jpeg|bmp|png|gif'},
            description:"required"
        },
        messages: {
            event_id: "Please select Event.",
            exp_name: "Please enter your experience.",
            gift_needed: {required:"Please enter gift needed.",min:"You cannot enter less than actual amount"},
            image: {extension: "Please upload valid image."},
            description:"Please enter description."
        },
        errorPlacement: function(error, element) 
        {
            if (element.attr("name") == "description"){ 
                error.insertAfter("#cke_description");
            }else if (element.attr("name") == "image"){ 
	                error.insertAfter("#exp_img");
	        }else{
                error.insertAfter(element);
            }  
        }
    });
    
    //$('#gift_needed').on('blur change',blurChange);
    
    $('.number').keypress(function(event) {
    	
	    var $this = $(this);
	   
	    if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
	       ((event.which < 48 || event.which > 57) &&
	       (event.which != 0 && event.which != 8))) {
	           event.preventDefault();
	    }
	
	    var text = $(this).val();
	    
	    if ((event.which == 46) && (text.indexOf('.') == -1)) {
	        setTimeout(function() {
	            if ($this.val().substring($this.val().indexOf('.')).length > 3) {
	                $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
	            }
	        }, 1);
	    }
	
	    if ((text.indexOf('.') != -1) &&
	        (text.substring(text.indexOf('.')).length > 2) &&
	        (event.which != 0 && event.which != 8) &&
	        ($(this)[0].selectionStart >= text.length - 2)) {
	            event.preventDefault();
	    }      
	});
	
	$('.number').bind("paste", function(e) {
		
		var text = e.originalEvent.clipboardData.getData('Text');
		if ($.isNumeric(text)) {
		    if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
		        e.preventDefault();
		        $(this).val(text.substring(0, text.indexOf('.') + 3));
		   }
		}
		else {
		        e.preventDefault();
		     }
		});

});


// function blurChange(e){
// 	
	// var $this = $(this);
//     
    // var actual_gift_needed_amt = $('#actual_gift_needed').text();
    // var actual_received_amt = $('#actual_received_amt').text();
    // var amt_remaining= $('#total_amt_remaining').text();
// 	
	// var current_gift_needed_amt = $('#gift_needed').val();
// 	
	// var text = $(this).val();
// 	
	// //alert(amt_remaining);
// 	
	// if(amt_remaining < actual_received_amt)
	// {
		// if(text > amt_remaining)
	    // {
	    	// //alert('you cannot modify amount less than remaining amount');
	    	// return false;
	    	// //current_gift_needed_amt <= actual_received_amt;
	    // }
    // }
// 	
// }


