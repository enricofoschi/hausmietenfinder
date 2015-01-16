(function(Current) {
	"use strict";

    Current.RequireAPI = function RequireAPI(callback) {

        window.onGoogleMapsLoaded = function() {
            callback();
        };

        Helpers.UI.DOM.LoadJSDynamically('https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&callback=onGoogleMapsLoaded');
    };
    
})(defineNamespace("Helpers.ThirdParties.GoogleMaps"));