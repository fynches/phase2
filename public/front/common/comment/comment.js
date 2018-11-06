var pageNumber = 2;

/* Save comment */
$(document).on('click touchstart', '.add-comment', function(event) {
    var $desc = $(this).parent().find('.comment-desc');
    var comment = $desc.val();
    var parent_id = $desc.attr('data-parent') || 0;
    // if(comment === ""){
    //     $( '<label class="error" >Please enter comment</label>' ).insertAfter($desc);
    //     console.log('Erorr');
    //     return false;
    // }
    if(validateComment($desc)){
        saveComment(comment, parent_id);
    }
    
});
function validateComment($desc){
    var comment = $desc.val();
    if(comment === ""){
        $desc.nextAll('label').remove();
        $( '<label class="error" >Please enter comment</label>' ).insertAfter($desc);       
        return false;
    }else{
        $desc.next('label').remove();
        return true;
    }
}
/* Replay comment */
$(document).on('click', '.replay-comment', function(event) {
    $this = $(this);
    $this.closest('.addcmt').next().removeClass('hide');    
});
var countkeyup = 0;
$(document).on('keyup', '.comment-desc', function(e) {
    var begin = 0;
    var max = 1001;
    var len = $(this).val().length;
    var allowedKeys = [8,46,35,36,37,38,39,40];
    $(this).parent().find('label.error').remove();
  //  alert(len);
    if (len >= max) {       
        $(this).parent().find('label:not(".error")').text('you have reached the limit');
        if($.inArray(e.keyCode, allowedKeys) == -1) {
            e.preventDefault();
        } 
    } else {
        var char = begin + len;
        console.log('len', len);
        $(this).parent().find('label:not(".error")').text(char + ' / 1000 text remaining');
    }
    
});

/* Load comment */
$(document).on('click', '.load-more', function(event) {
    $this = $(this);  
    var redirect_url = window.base_url+'/load-more-comment';
    var event_id = $('#event_id').val();
    $('body').loading('start');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {event_id: event_id, page: pageNumber,token:$('meta[name="csrf-token"]').attr('content')},
        url: redirect_url,
        success: function (res) {
            pageNumber++;
            $('.load-html').append(res.html);
            if(!res.morepage){
                $('.load-more-div').hide();
            }
            $('body').loading('stop');
        },
        error: function (request, status, error) {
            $('body').loading('stop');
        }
    });
});

/* Save comment */
function saveComment(comment, parent_id = 0){
    var redirect_url = window.base_url+'/save-comment';
    var event_id = $('#event_id').val();
    var total_div = $(".comment-item").length;
    
    $('body').loading('start');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "post",
        data: {event_id: event_id, parent_id: parent_id, total_div:total_div, comment_description: comment,token:$('meta[name="csrf-token"]').attr('content')},
        url: redirect_url,
        success: function (res) {
        	//$('#comment_forms').reset();
        	$('.custom_msgs').html('1000 characters remaining');
            $('.comment-desc').val('');
            $('.comment-loop').html(res.html); 
            var text = $(".comment-tab").text();
            text = text.replace(/\D/g,'');
            //console.log('text',text);
            if(parent_id=="0")
            {
            	text = parseInt(text)+1;	
            }
            
            $(".comment-tab").text("Comments ("+text+")");
            $('body').loading('stop');
        },
        error: function (request, status, error) {
            $('body').loading('stop');
        }
    });
}


/* Delete Comment */
function DeleteComment(comment){

    if(confirm("Are you sure want to delete comment?")){
        var redirect_url = window.base_url+'/delete-comment';    
        var event_id = $('#event_id').val();
        var total_div = $(".comment-item").length;
        $('body').loading('start');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "post",
            data: {event_id: event_id, comment_id: comment,total_div:total_div, token:$('meta[name="csrf-token"]').attr('content')},
            url: redirect_url,
            success: function (res) {
                $('.comment-loop').html(res.html);
                $('body').loading('stop');
                // if(!res.morepage){
                //     $('.load-more-div').hide();
                // }
            },
            error: function (request, status, error) {
                $('body').loading('stop');
            }
        });
    }   
}