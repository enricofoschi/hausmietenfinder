var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var amqp = require('amqp');
var connection = amqp.createConnection({ 
	host: 'localhost', 
	port: 5672 ,
	login: 'guest',
	password: 'guest'
});
var rabbitMQQueue = 'hausmietenfinder.search_finished_' + (Math.random() * 10000);

// List of connected client to this instance of Node server
var connectedClients = {};

// Socket.IO On Connected event
io.on('connection', function(socket){

	console.log("Socket connection ready");

	// Adding the client to the list of connected clients
	if(!connectedClients[socket.id]) {
		connectedClients[socket.id] = socket;

		console.log(socket.id + ' connected - connected clients: ' + Object.keys(connectedClients).length);
	}

	// Assigning ID to the client
	socket.send({
		type: 'main_properties',
		client_id: socket.id,
		rabbitMQQueue: rabbitMQQueue
	});
  
  	// Socket.IO On Disconnect event
	socket.on('disconnect', function(){

		// Removing client id from the list of connected clients
		if(connectedClients[socket.id]) {
			delete connectedClients[socket.id];
			console.log(socket.id + ' disconnected - connected clients: ' + Object.keys(connectedClients).length);
		}
	});
});

/* AMQP Issues */
connection.on('error', function(error) {
	
	console.log('Node-AMQP error:');

	if(error.code === 404) {
		console.log('Queue ' + rabbitMQQueue + ' not there yet');
	} else {
		console.log(error);
	}
});

/* AMQP Connection */
connection.on('ready', function () {

	console.log("Rabbit connection ready");

	// Use the default 'amq.topic' exchange
	connection.queue(rabbitMQQueue, {
		passive: true,
		durable: true
	}, function (q) {
		console.log('Connected to the queue ' + q.name);
			
		// Receive messages
		q.subscribe({ 
			ack: true, // requires explicit aknowledgement
			prefetchCount: 1 // 1 message at a time max (QoS)
		}, function (message, headers, deliveryInfo, messageObject) {
			// Print messages to stdout
			var response = JSON.parse(message.data.toString());
			response.type = response.processing ? 'house_search_processing' : 'house_search_finished';

			console.log(response);
			
			try {
				connectedClients[response.nodejs_client_id].send(response);
			}
			catch(e) {
				console.log(e);
			}

			messageObject.acknowledge(true);
		});
  	});
});

// Starting the Web Server
http.listen(3000, function(){
  console.log('listening on *:3000');
});