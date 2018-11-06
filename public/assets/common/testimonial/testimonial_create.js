var html_addimage = $(".file_input_div").html();

$(document).ready(function () {  

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
    

    $('#testimonial').validate({
        ignore: [],   
        rules: {
            name: "required",
            image: {required: true, extension: 'jpg|jpeg|bmp|png|gif'},
            description:"required",
	    author_name: "required"
        },
        messages: {
            name: "Please enter name.",
            image: {required: "Please upload an image.", extension: "Please upload valid image."},
            description:"Please enter description.",
 	    author_name:"Please enter author name."
            
        },
        errorPlacement: function(error, element) 
        {
            if (element.attr("name") == "description"){ 
                error.insertAfter("#cke_description");
            }else if (element.attr("name") == "image"){ 
                error.insertAfter("#testimonal_img");
            }else{
                error.insertAfter(element);
            }  
        }
    });

});
