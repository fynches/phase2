/*this funciton is used for Admin user edit profile*/
var html_addimage = $(".file_input_div").html();

function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image_upload_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#profile_image").change(function () {
        readURL(this);
    });
/*Admin user edit profile end*/

function randomPassword(length) {
        var chars = "abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>ABCDEFGHIJKLMNOP1234567890";
        var pass = "";
        for (var x = 0; x < length; x++) {
            var i = Math.floor(Math.random() * chars.length);
            pass += chars.charAt(i);
        }
        return pass;
    }

function generate() {
    var auto_pass = randomPassword(8);
    $('#password,#confirmpassword').val(auto_pass);
}
$(document).ready(function () {
    $('.onlynumallow').ForceNumericOnly();
    $('#user').validate({
        rules: {
            firstname: "required",
            lastname: "required",
            email: {
                required: true,
                email: true,
            },
            user_type: "required"
        },
        messages: {
            firstname: "Please enter your first name.",
            lastname: "Please enter your last name.",
            email: {
                required: "Please enter email address.",
                email: "Please enter a valid email address."
            },
            user_type: "Please select user type."
        }
    });
});

