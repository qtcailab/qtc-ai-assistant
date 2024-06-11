<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>智能助教</title>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="chat">
        <div class="top">
        </div>

        <div class="message">
            <div class="left message">
                <img src="/svg/chatbot.svg" style="height: 50px;">
                <p>我是青职C语言小助教，请和我对话吧~~<</p>
            </div>

        </div>

        <div class="bottom">
            <img src="/svg/student.svg" style="height:30px;">
            <form>
                <input type="text" id="message" name="message" placeholder="输入你的问题..." autocomplete="off">
                <button type="submit"></button>
            </form>
        </div>
    </div>
    
</body>
<script>
    $("form").submit(function(event) {
        event.preventDefault();

        $("form #message").prop('disabled', true);
        $("form button").prop('disabled', true);

        $.ajax({
            url: "/chat",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            },
            data: {
                "content": $("form #message").val()
            }
        }).done(function(res) {
            $(".message > .message").last().after('<div class="right message">' + 
                '<img src="/svg/student.svg" style="height:30px;">' +
                '<p>' + $("form #message").val() + '</p>' + 
                '</div>' 
            );

            $(".message > .message").last().after('<div class="left message">' + 
                '<img src="/svg/chatbot.svg" style="height:30px;">' +
                '<p>' + res + '</p>' + 
                '</div>'
            );

            $("form #message").val('');
            $(document).scrollTop($(document).height());

            $("form #message").prop('disabled', false);
            $("form button").prop('disabled', false);
        });
    });
</script>
</html>