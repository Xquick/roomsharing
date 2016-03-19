var chat;
jQuery(function () {
    chat = {
        append: function (conversationId, reload, message) {
            //set default parameter RELOAD
            var $chat;
            var $loader = $(document).find(".user-contact-card .loader");
            $.ajax({
                url: '/site_c/userChat/' + conversationId,
                data: [
                ],
                beforeSend: function () {
                    $loader.show();
                },
                success: function (output) {
                    console.log(output);
                    $('.current-chat').append(output);
                    $chat = $(document).find('.current-chat');
                    var $textarea = $chat.find('textarea');
                    $chat.scrollTop($chat[0].scrollHeight);
                    $chat.closest('.current-chat').find('input').focus();
                    //external plugin - autoresize textarea when typing
                    $textarea.autosize({
                        callback: function () {
                            $textarea.trigger('resize');
                        }
                    });
                    checkOnlineUsers();
                    $loader.hide();
                }
            });

        }
    };

    $(document).on("click", ".chat-close", function () {
        $(this).closest(".user-contact-card").remove();
    });

//    getAllUserChats();

    $.post('/user_api/getUserId', function (output) {
        var userId = JSON.parse(output);
//        var nodeServer = 'http://intense-headland-7332.herokuapp.com/';
        var nodeServer = 'http://localhost:3919/';
        var socket = io.connect(nodeServer, {query: "userId=" + userId});
        socket.userId = userId;

        //odeslaní nové zprávy při stisku enteru
        $(document).on('keypress', '.send-chat-message textarea', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();

                var $messageInput = $(this);
                var message = $messageInput.val();
                var targetUserId = $(this).closest('.send-chat-message').attr('data-user-id');
                var conversationId = $(this).closest('.send-chat-message').attr('data-id');

                //data k odeslani na server a ulozeni zpravy
                var data = {
                    sourceUserId: userId,
                    targetUserId: targetUserId,
                    conversationId: conversationId,
                    message: message
                };

                if ($(document).find(".messages-conversation-wrapper").length) {
                    data.conversationBool = true;
                }
                //odeslani zpravy realtime
                socket.emit('send message', data);

                $.ajax({
                    url: '/chat_c/sendMessage',
                    type: "get",
                    data: data,
                    context: e,
                    success: function (output) {
//
                    }
                });
                //po odeslání zprávy se vyčistí textarea
                $messageInput.val('');
            }
        });

        //příjem nové zprávy
        socket.on('new message', function (data) {
            var $conversationItem = $(document).find('.message-item[data-id=' + data.conversationId + ']');
            var $messageInfoCount = $('.messages-info-count');
            var data = {
                sourceUserId: data.sourceUserId,
                targetUserId: data.targetUserId,
                conversationId: data.conversationId,
                message: data.message,
                direction: data.direction,
                type: 1
            };

            if ($(document).find(".messages-conversation-wrapper").length) {
                data.conversationBool = true;
            }
            $.ajax({
                url: '/site_c/newMessage',
                type: "post",
                data: data,
                success: function (output) {
                    console.log(output);
                    var $conversation = $(document).find(".messages-conversation .scrollable");
                    var message;
//                    var $html = $($.parseHTML(output));
                    if ($conversation.length) {
//                        message = $html.filter(".conversation-item");
                        $conversation.append(output);
                        $conversation.scrollTop($conversation[0].scrollHeight);
                    }
                    //when sending from chat window
                    var $chat = $(document).find(".current-chat-scroll");

                    if ($chat.length) {
                        $chat.append(output);
                        $chat.scrollTop($chat.find(".scrollable")[0].scrollHeight);
                    }
                    var newMessage = $('#sound-new-message');
                    //pokud nemáme kurzor v tomto chatu
                    if (!$(document).find('.message-item[data-id=' + data.conversationId + '].active').length) {
                        //pokud jde o zprávu, která nepřišla od nás (více oken)
                        if (data.direction == 1) {
                            $messageInfoCount.text(parseInt($messageInfoCount.text()) + 1);
                            $messageInfoCount.removeClass("hidden");
                            $conversationItem.addClass('unread-message');
                            var $recentUserMessage = $conversationItem.find('.recent-user-new-messages');
                            var recentUserMessageCount = parseInt($recentUserMessage.text());
                            if (isNaN(recentUserMessageCount)) {
                                recentUserMessageCount = 0;
                            }
                            $recentUserMessage.text(recentUserMessageCount + 1);
                            newMessage[0].play();
                        }
                    }
                }
            });
        });

        //získání online uživatelů najednou
        socket.on('user map', function (data) {
            console.log(data);
            var onlineUsers = new Array();
            for (var i = 0; i < data.length; i++) {
                if (data[i]) {
                    changeUserStatus('online', i);
                    onlineUsers.push(i);
                }
            }
            setCookie('onlineUsers', JSON.stringify(onlineUsers), 365);
        });

        //nějaký uživatel je ONLINE
        socket.on('user online', function (data) {
            var userId = parseInt(data);
            changeUserStatus('online', userId);
            console.log('USER ' + data + ' ONLINE');
            var onlineUsers = JSON.parse(getCookie('onlineUsers'));
            if (onlineUsers.indexOf(userId) == -1) {
                onlineUsers.push(userId);
                setCookie('onlineUsers', JSON.stringify(onlineUsers), 365);
            }
        });

        //nějaký uživatel šel OFFLINE
        socket.on('user offline', function (data) {
            var userId = parseInt(data);
            changeUserStatus('offline', userId);
            console.log('USER ' + data + ' OFFLINE');
            var onlineUsers = JSON.parse(getCookie('onlineUsers'));
            if (onlineUsers.indexOf(userId) > -1) {
                onlineUsers.splice(onlineUsers.indexOf(userId), 1);
                setCookie('onlineUsers', JSON.stringify(onlineUsers), 365);
            }
        });

        //uživatel kliknul do chatovacího okna
        $(document).on('click', '.message-item', function (e) {

            $(e.target).closest(".message-item").removeClass('unread-message');
            var conversationId = $(e.target).closest(".message-item").attr('data-id');
            var $messageInfoCount = $(document).find('.messages-info-count');
            var $recentUser = $(document).find('.recent-user[data-id=' + conversationId + ']');

            console.log(conversationId);
            //vynulování nepřečtené konverzace
            $.ajax({
                url: '/chat_c/zeroOutMessageCount/' + conversationId,
                success: function (output) {
                    var totalCount = parseInt(output);
                    $recentUser.removeClass('unread-message');
                    if (!isNaN(totalCount)) {
                        if (totalCount == 0) {
                            $(document).find(".messages-info-count.notification:not(.hidden)").addClass("hidden");
                        }
                        $messageInfoCount.text(totalCount);
                    }
                }
            });
        });

        var typing = false;
        var timeout;
        //odeslání informace zda uživatel píše nebo ne
        $(document).on('keyup', '.send-chat-message textarea, .messages-conversation-new-message textarea', function (e) {
            var context = this;

            function getSendingData(context) {
                if ($(document).find(".send-chat-message").length) {
                    var targetUserId = $(context).closest('.send-chat-message').attr('data-user-id');
                    var conversationId = $(context).closest('.send-chat-message').attr('data-id');
                } else {
                    var targetUserId = $(context).closest('.messages-conversation-new-message').attr('data-user-id');
                    var conversationId = $(context).closest('.messages-conversation-new-message').attr('data-id');
                }
                return {
                    sourceUserId: userId,
                    conversationId: conversationId,
                    targetUserId: targetUserId
                };
            }

            function stoppedTyping() {
                typing = false;
                socket.emit('not typing', getSendingData(context));
            }

            if (realCharacter(e)) {
                if (!typing) {
                    typing = true;
                    var data = getSendingData(this);
                    socket.emit('typing', data);
                    timeout = setTimeout(stoppedTyping, 3500);
                } else {
                    window.clearTimeout(timeout);
                    timeout = setTimeout(stoppedTyping, 3500);
                }
            }
            if (e.keyCode == 13) {
                stoppedTyping();
            }
        });

        //uživatel píše
        socket.on('typing', function (data) {
            var userId = data['sourceUserId'];
            var conversationId = data['conversationId'];
            console.log('píše v konverzaci ' + conversationId);
            var $chat = $(document).find('.current-chat-' + data['conversationId']).find('.current-chat-body-inner');
            if ($chat.find('.host-message').length > 0) {
                console.log('found host-message');
                var $appendElement = $('.host-message').clone();
                console.log(typeof $appendElement);
                $appendElement.find('.message-content').text('...');
                $chat.find('.current-chat-scroll').append('<div class="typing">' + $appendElement[0].outerHTML + '</div>');
                $chat.scrollTop($chat[0].scrollHeight);
            }
        });

        //uživatel přestal psát
        socket.on('not typing', function (data) {
            console.log("NOT TYPING");
            var userId = data['sourceUserId'];
            var conversationId = data['conversationId'];
            console.log('přestal psát v konverzaci ' + conversationId);

            var $chat = $('body').find('.current-chat-' + conversationId).find('.current-chat-scroll');

            //odstranění indikátoru psaní
            $chat.find('.typing').remove();
        });
    });
    checkOnlineUsers = function () {
        var onlineUsers = JSON.parse(getCookie('onlineUsers'));
        for (var i = 0; i < onlineUsers.length; i++) {
            changeUserStatus('online', onlineUsers[i]);
        }
    };

    function changeUserStatus(state, userId) {
        var $statusChat = $(document).find('.recent-user[data-user-id=' + userId + ']').find('.availability-state');
        var $statusCurrentChat = $(document).find('.current-chat[data-user-id=' + userId + ']').find('.recent-user-availability');
        var $statusConversation = $(document).find('.messages-conversation-header[data-user-id=' + userId + ']').find('.availability-state');
        switch (state) {
            case 'online':
                $statusChat.addClass('online');
                $statusConversation.addClass('online');
                $statusCurrentChat.show();
                break;
            case 'offline':
                $statusChat.removeClass('online');
                $statusConversation.removeClass('online');
                $statusCurrentChat.hide();
                break;
        }
    }

    removeUserChat = function ($context, userId) {
        var chatUsers = getCookie('chatUsers');
        chatUsers = chatUsers.replace(userId + ',', '');
        setCookie('chatUsers', chatUsers, 1000);
        $(document).find('.current-chat-' + userId).closest('.current-chat-wrapper').remove();
    }

    $(document).on("keyup", "#find-chat-people input", function (e) {
        var $target = $(e.target);
        var $conversation = $(document).find("");
        var text = $target.val();

        $(".message-item-name a:not(:containsNC(" + text + "))").each(function () {
            $(this).closest(".message-item").hide();
        });
        $(".message-item-name a:containsNC(" + text + ")").each(function () {
            $(this).closest(".message-item").show();
        });
    });
});

function realCharacter(event) {
    var keyCode = event.keyCode;
    return (keyCode >= 48 && keyCode <= 90) ||
        (keyCode >= 96 && keyCode <= 111) ||
        (keyCode >= 186 && keyCode <= 192) ||
        (keyCode >= 219 && keyCode <= 222);
}

var appendUserChat;
var removeUserChat;
var getAllUserChats;
var checkOnlineUsers;

