(function(Current) {
	"use strict";

	Current.StopPropagation = function StopPropagation(e) {
		if(e.preventDefault) e.preventDefault();
		if(e.stopPropagation) e.stopPropagation();
	};

	Current.GetBody = function() {
		if(!Helpers.UI.DOM.BodyRef) {
			Helpers.UI.DOM.BodyRef = $(document.body);
		}
		return Helpers.UI.DOM.BodyRef;
	};

	Current.GetMainContent = function() {
		return $('#main-content'); // cannot be cached as it's dynamically replaced at every routing
	};

    Current.GetMainContentContainer = function() {
        if(!Current.MainContentContainerRef) {
            Current.MainContentContainerRef = $('#main-content-container');
        }
        return Current.MainContentContainerRef;
    };

	Current.LoadCSSDynamically = function LoadCSSDynamically(cssUrl) {
        var cssLink = $("<link rel='stylesheet' type='text/css' href='" + cssUrl + "'>");
        $("head").append(cssLink);
    };

    var cachedJS = [];
    Current.LoadJSDynamically = function LoadJSDynamically(jsSrc, callback) {
    	var loadedJS = Helpers.Core.Arrays.First(cachedJS, function (js) {
    		return js.src === jsSrc;
    	});

    	if(loadedJS) {
    		if(loadedJS.isLoading) {
    			loadedJS.callbacks.push(callback);
    		} else {
    			callback();
    		}
    	} else {
    		var newJS = {
    			isLoading: false,
    			callbacks: [callback]
    		};

    		$.getScript(jsSrc, function onLoad() {
        		newJS.isLoading = false;

        		$.each(newJS.callbacks, function() {
        			if(this) this();
        		});
        	});

    		cachedJS.push(newJS);
    	}
    };

    Current.ScrollTo = function ScrollTo($element) {
        $("html, body").animate({ scrollTop: $element ? $element.offset().top - 15 : 0 });
    };
    
})(defineNamespace("Helpers.UI.DOM"));