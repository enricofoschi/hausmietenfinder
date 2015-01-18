(function(Current) {

	Current.Distance = function distanceService() {
		var self = this;

		self.load = function load(search_id, page) {
			return Helpers.Core.Communication.Get({
				url: 'houses/getsearch/' + search_id + '/' + page
			});
		};

		self.changeStatus = function(distanceId, remove) {
			return Helpers.Core.Communication.Post({
				url: 'houses/changestatus',
				data: {
					distance_id: distanceId,
					remove: removeItem 
				}
			})
		};
	}

})(defineNamespace("Services.HausMietenFinder"));