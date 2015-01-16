(function (Current) {
    "use strict";
 
    var activeRequest = 0;

    Current.Get = function Get(properties) {
        properties.method = 'GET';
        return Current.Ajax(properties);
    };
 
    Current.Delete = function Delete(properties) {
        properties.method = 'DELETE';
        return Current.Ajax(properties);
    };
 
    Current.Post = function Post(properties) {
        properties.method = 'POST';
        properties.cache = false;
        properties.rvar = Math.random();
        return Current.Ajax(properties);
    };
 
    Current.Ajax = function Ajax(properties) {
 
        /* Setting Defaults */
        properties.method = properties.method || 'GET';
        properties.data = properties.data || {};
        properties.url = MainProperties.base_url_api + properties.url;

        if(properties.method === 'POST') {
            properties.dataType = 'json';
            properties.data = JSON.stringify(properties.data);
        }
 
        /* Persisting Debug Properties */
        if (MainProperties.MockIp) {
            properties.url = Current.AddParameterToUrl(properties.url, "mockIp", MainProperties.MockIp);
        }
 
        if (MainProperties.MockLive) {
            properties.url = Current.AddParameterToUrl(properties.url, "mockLive", MainProperties.MockLive);
        }
 
        if (!properties.background) {
            Helpers.UI.Loader.Show(properties.loadingMessage);
        }

        /* Handling Deferred */
        var deferred = properties.deferred || $.Deferred();         
        properties.success = function onSuccess(response) {
            activeRequest--;
            Helpers.UI.Loader.Hide();
            deferred.resolve(response);
        };
        properties.error = function onError() {
            activeRequest--;
            Helpers.UI.Loader.Hide();
            Helpers.UI.Notifications.Error('Something wrong happened when calling ' + properties.url);
            deferred.reject();
        };
 
        /* Ajax Call */
        $.ajax(properties);
        activeRequest++;

        return deferred.promise();
    };
 
    Current.AddParameterToUrl = function AddParameterToUrl(url, param, value) {
        url = url || '';
        return url + (url.indexOf("?") > -1 ? "&" : "?") + param + "=" + value;
    };

})(defineNamespace("Helpers.Data.Communication"));