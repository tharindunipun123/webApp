$(document).ready(function(){
    $('a.nav-link').click(function(e){
        e.preventDefault();
        var page = $(this).attr('href');
        $('#main-content').load('pages/' + page + '.php');
    });
});
