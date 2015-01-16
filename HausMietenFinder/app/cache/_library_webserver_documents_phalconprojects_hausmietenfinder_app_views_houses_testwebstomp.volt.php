<html>
    <head>
        <title>Test WebSockets</title>
        <script src="https://cdn.socket.io/socket.io-1.2.1.js"></script>
    </head>
    <body>

    <script>
        var socket = io('http://localhost:3000');
        socket.on('chat', function(chat) {
            console.log(chat);
        });
    </script>
    </body>
</html>