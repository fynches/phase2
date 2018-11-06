var html_addimage = $(".file_input_div").html();
$(document).ready(function () {  
    $(function() {
        var date = new Date();
        date.setDate(date.getDate());
        $( "#datepicker" ).datepicker({format: 'dd-mm-yyyy', startDate: date});
    }); 
   

    $('#event').load(function(){});

    // CKEDITOR.replace('description',
    // {
        // toolbar: 'Basic',  
        // uiColor: '#9AB8F3'
    // });

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

    $.validator.addMethod("noSpace", function(value, element) { 
        return value.indexOf(" ") < 0 && value != ""; 
      }, "Space are not allowed");

    $('#cms').validate({
        ignore: [],   
        rules: {
            title: "required",
            slug: { required: true, noSpace: true }, 
            description:"required"
        },
        messages: {
            title: "Please enter title.",
            slug: { required: 'Please enter slug' },  
            description:"Please enter descriptions"
        },
        errorPlacement: function(error, element) 
        {
            if (element.attr("name") == "description"){ 
                error.insertAfter("#cke_description");
            }else{
                error.insertAfter(element);
            }  
        }
    });

    // Image change ans show over their
    

    $('#upload_img').click(function(){
      $('.images').click();
      return false;
    });

    $('#upload_background_img').click(function(){
       $('.background_image').click();
       return false;
    });

    $(".images").change(function () {
        readURL(this);
    });

    $(".background_image").change(function () {
        readURLBackground(this);
    });

    function readURL(input) {
        if (input.files && input.files[0]) {

            var reader = new FileReader();
            reader.onload = function (e) {

                if($('#preview_image').length === 0){
                    $("#profileImg").html("<a href = '"+e.target.result + "' target='_blank'> <img id='preview_image' src = '"+e.target.result + "' class='m-r-sm' style='max-width: 255px; max-height: 255px;'></a> ");
                }else{
                    $('#preview_image').attr('src', e.target.result);
                }

            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function readURLBackground(input) {

        if (input.files && input.files[0]) {

            var reader = new FileReader();
            reader.onload = function (e) {

                if($('#preview_background').length === 0){
                    $("#preview_background").html("<a href = '"+e.target.result + "' target='_blank'> <img id='preview_image' src = '"+e.target.result + "' class='m-r-sm' style='max-width: 255px; max-height: 255px;'></a> ");
                }else{
                    $('#preview_background').attr('src', e.target.result);
                }

            }
            reader.readAsDataURL(input.files[0]);
        }
    }

});
