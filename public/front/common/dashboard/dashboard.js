$(document).ready(function()
{
    $(document).on('click', '.event_pagination .pagination a',function(event)
    {
    	//alert('event');
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        event.preventDefault();
        var myurl = $(this).attr('href');
        var page=$(this).attr('href').split('page=')[1];
        getMyEvents(page);
    });
    
    
    $(document).on('click', '.contribution_pagination .pagination a',function(event)
    {
    	//alert('contribution');
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        event.preventDefault();
        var myurl = $(this).attr('href');
        var page=$(this).attr('href').split('page=')[1];
        getMyContributionData(page);
    });
    
    $(document).on('keyup', '.expandright',function(event)
    {
    	event.stopPropagation();
    	var search_val= $(this).val();
    	var keyword_search_val="";
    	
		$.ajax({
	        type: "GET",
	        url: '/search-custom-exp/'+search_val,
	        success: function (res) {
	        	console.log('final result is',res);
	            if(res)
	            {
	            	$("#user-events").empty().html(res);
	            }
	        }
	    });
    });
});



function getMyEvents(page){
	var search_title= $('.search_custom_exp').val();
    $('body').loading('start');
    $.ajax(
    {
        url: '?page=' + page,
        type: "GET",
        data: { type: "event",search_title:search_title},
        datatype: "html",
    })
    .done(function(data)
    {
        $('body').loading('stop');
        $("#user-events").empty().html(data);
        
        $('html, body').animate({
            scrollTop: $("body").offset().top
         }, 1000);
        //location.hash = page;
    })
    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        $('body').loading('stop');
        alert('No response from server');
    });
}

function getMyContributionData(page)
{
    $('body').loading('start');
	 $.ajax(
    {
        url: '?page=' + page,
        type: "GET",
        data: { type: "contribution"},
        datatype: "html",
        // beforeSend: function()
        // {
        //     you can show your loader 
        // }
    })
    .done(function(data)
    {
        $('body').loading('stop');
        $("#my-contribution").empty().html(data);
        //location.hash = page;
    })
    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        $('body').loading('stop');
        alert('No response from server');
    });
}
