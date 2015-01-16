(function(Current) {
	"use strict";

	Current.Show = function Show(message) {

		message = '<i class="fa fa-gear fa-spin"></i>' + (message ? ' ' + message : '');

		$.blockUI({
			message: message
		});
	};

	Current.Hide = function Hide() {
		$.unblockUI();
	};

})(defineNamespace("Helpers.UI.Loader"));