(function(Current) {

	/* Setting up events to communicate with Node.JS (on search processing and finished) */
	if(!Current.EventsInitialized) {						
		Helpers.Core.Events.Subscribe('node_house_search_processing', function(response) {
			Helpers.UI.Loader.Hide();
			Helpers.UI.Loader.Show('Bearbeitung. Suchen und Berechnung der Entfernung. Dies kann bis zu 10 Minuten dauern.');
		});

		Helpers.Core.Events.Subscribe('node_house_search_finished', function(response) {
			Helpers.UI.Loader.Hide();
			Current.OnSearchFinished(response);
		});

		Current.EventsInitialized = true;
	}

	Current.Search = function searchService() {
		var self = this;		

		self.search = function load(data) {
			var deferred = $.Deferred();

			/* Static event called once the search is finished */
			Current.OnSearchFinished = function(response) {
				deferred.resolve(response.$id);
			};

			Helpers.Core.Communication.Post({
				url: 'houses/search',
				loadingMessage: 'In Warteschlange. Suchen und Berechnung der Entfernung. Dies kann bis zu 10 Minuten dauern.',
				keepLoader: true,
				data: $.extend(data, {
					client_id: MainProperties.RabbitMQ.client_id,
					rabbitMQQueue: MainProperties.RabbitMQ.rabbitMQQueue
				})
			}).then(function onSuccess(response) {
				if(response.available) {
					deferred.resolve(response.search_id);
				}
			});

			return deferred.promise();
		};
	}

})(defineNamespace("Services.HausMietenFinder"));