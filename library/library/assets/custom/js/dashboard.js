(function($) {
	"use strict";

    $(document).ready(function() {
        // SLIMSCROLL FOR CHAT WIDGET
        $('#chat-box').slimScroll({
            height: '350px',
            start:'bottom'
        });

        $('.loadmore').hide();
        $('#chatmessagesend').on('click', function() {
            var chatmessage = $('#chatmessage').val();
            if(chatmessage != '') {
                $.ajax ({
                    data: {message:chatmessage},
                    type: "POST",
                    url: THEME_BASE_URL + "dashboard/chat",
                    success: function(data) {
                        var responsemessage = JSON.parse(data);
                        var appendmessage = '<div class="item">'+
                            '<img src="'+responsemessage.photo+'" alt="member image" class="offline">'+ 
                            '<p class="message">'+
                                '<a href="#" class="name">'+
                                    '<small class="text-muted pull-right"><i class="fa fa-clock-o"></i> '+responsemessage.time+'</small>'+
                                    responsemessage.name+
                                '</a>'+
                                responsemessage.message +
                            '</p>'+
                        '</div>';
                        $('.mainchatbox').append(appendmessage);
                        $('#chatmessage').val('');

                        $(".mainchatbox").scrollTop($(".mainchatbox")[0].scrollHeight);
                    },
                });
            } else {
                toastr.error(dashboard_provide_message);
            }
        });

        $('.mainchatbox').on('scroll', function() {
            if($(".mainchatbox").scrollTop() == 0) {
                $('.loadmore').show('slow');
            } else {
                $('.loadmore').hide('slow');
            }
        });

        $('.loadmore').on('click', function() {
            var firstchatID = $('.chatID:first').data('chatid');

            $.ajax({
                type: "POST",
                url: THEME_BASE_URL + 'dashboard/getchatmessage',
                data: {'chatID':firstchatID},
                dataType: 'html',
                success: function(data) {
                    response = JSON.parse(data);
                    if(response.status) {
                        $('.chatboxmessage').prepend(response.data);
                    }
                }
            });
        });

        $('#chatmessagerefresh').on('click', function() {
            window.location.reload();
        });
    });

    var ctx = document.getElementById('canvas');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'Auguest', 'September', 'October', 'November', 'December'],
            datasets: [
                {
                    label: 'Income',
                    data: dashboard_income,
                    backgroundColor: 'rgba(0,153,0,0.8)',
                    borderColor: 'rgba(17,17,17,0.8)',
                    borderWidth: 1
                },
                {
                    label: 'Expense',
                    data: dashboard_expense,
                    backgroundColor: 'rgba(204,0,0,0.8)',
                    borderColor: 'rgba(17,17,17,0.8)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

})(jQuery);