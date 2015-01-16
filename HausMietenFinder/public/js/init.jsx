// Initializing Routing
routingInit();

ReactRouter.run(routes, ReactRouter.HistoryLocation, function (Handler) {
  React.render(<Handler/>, Helpers.UI.DOM.GetMainContentContainer()[0]);
});

// Initializing NodeJS Server Connection
var socket = io.connect('http://localhost:3000/');
socket.on('connect', function () {
  socket.on('message', function (msg) {

  	if(msg.type == 'main_properties') {
	  	MainProperties.RabbitMQ = msg;
	} else {
		Helpers.Core.Events.Publish('node_' + msg.type, msg);
	}
  });
});

// Loading Font Awesome
Helpers.UI.DOM.LoadCSSDynamically('//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');

// Loading Open Sans
Helpers.UI.DOM.LoadCSSDynamically('//fonts.googleapis.com/css?family=Open+Sans');