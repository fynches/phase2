$("#emailtemplate").validate({
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
                    subject: {required: true},
                    content: {required: true}
                },
        messages: {
            subject: {required: "Please enter subject."},
            content: {required: "Please enter content."}
        },
        errorPlacement: function (error, element) {
            // if (element.is('textarea[name="content"]')) {
            //     error.insertAfter(element.next());
            // } else {
            //     error.insertAfter(element);
            // }

            if (element.attr("name") == "content"){ 
                error.insertAfter("#cke_content");
            }else{
                error.insertAfter(element);
            }  
        },
        success: function (element) {
            $(element).parent('.form-group').removeClass('has-error');
        },
    });

    $(document).ready(function () {
        CKEDITOR.config.height = '125px';

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
                    $("#content-error").remove();
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
    });
