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

			var searchService = new Services.HausMietenFinder.Search();
			searchService.search(data).then(this.onAvailable);
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
			</div>
	      );
	     }
    });
})(defineNamespace("Views.index.index"));