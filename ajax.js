// $(document).ready(function(){
//     $('a.nav-link').click(function(e){
//         e.preventDefault();
//         var page = $(this).attr('href');
//         $('#main-content').load('pages/' + page + '.php');
//     });
// });


$(document).ready(function() {
    setInterval(function() {
        $.ajax({
            url: 'pages/update_balance.php',  // The PHP script that updates balance
            method: 'POST',
            data: { user_id: userId },  // Send the logged-in user's ID
            success: function(data) {
                // Update the balance display with the new balance
                $('.display-4').text(data + ' BOTH');
            },
            error: function(xhr, status, error) {
                console.error("Balance update error: " + error);
            }
        });
    }, 6000); // Run every 60 seconds
});

