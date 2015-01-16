<html>
    <head>
        <title>Test WebSockets</title>
        <script src="//cdn.socket.io/socket.io-1.2.1.js"></script>
    </head>
    <body>

    <script>
        var socket = io('http://localhost:3000');

        socket.on('message', function(message) {
           console.log(message);
        });
    </script>
    </body>
</html>