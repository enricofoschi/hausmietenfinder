(function(Current) {
	"use strict";

	var mounted = false;

	Current.Main = React.createClass({

		mixins: [ReactRouter.Navigation],

		onAvailable: function onAvailable(search_id) {
			this.transitionTo('default', {
				splat: 'search/' + search_id
			});
		},

		submit: function submit(data) {
			var thisRef = this;

			Helpers.Core.Communication.Post({
				url: 'houses/search',
				data: $.extend(data, {
					client_id: MainProperties.RabbitMQ.client_id,
					rabbitMQQueue: MainProperties.RabbitMQ.rabbitMQQueue
				}),
			}).then(function onSuccess(response) {

				if(response.available) {
					thisRef.onAvailable(response.search_id);
				} else {
					Helpers.UI.Loader.Show('Suchen und Berechnung der Entfernung. Dies kann bis zu 10 Minuten dauern.');

					Helpers.Core.Events.Subscribe('node_house_search_finished', function() {
						if(mounted) {
							Helpers.UI.Loader.Hide();
							thisRef.onAvailable(response.search_id);
						}
					});
				}
			});
		},

		componentDidMount: function() {
			mounted = true
		},

		componentWillUnmount: function() {
			mounted = false;
		},

	    render: function () {

	      return (
	      	<div className="container ptop50">
      			<div className="row">
					<div className="col-md-8 col-md-offset-2 text-center">
						<h1>Finden Sie Häuser oder Wohnungen zu vermieten in der Nähe Ihres Arbeitsplatzes!</h1>
						<div className="row top25">
							<div className="col-sm-10 col-sm-offset-1">
								<Components.Forms.GoogleMaps.GooglePlacesAutocomplete submitCallback={this.submit} />
							</div>
						</div>
					</div>
				</div>
				<Link to="default" params={{splat:'Test'}}>Test</Link> - <Link to="default" params={{splat:''}}>Home</Link>	        
			</div>
	      );
	     }
    });
})(defineNamespace("Views.index.index"));