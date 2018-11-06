$('body').on('loading.start', function(event, loadingObj) {
    $('body').addClass('loader-start');
});

$('body').on('loading.stop', function(event, loadingObj) {
    $('body').removeClass('loader-start');
});