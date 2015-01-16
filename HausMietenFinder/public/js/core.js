var defineNamespace = function defineNamespace(namespaceToDefine) {
	var parts = namespaceToDefine.split('.');

	var partIndex = 0;
	var namespaceContainer = window;

	while(partIndex < parts.length) {

		if(!namespaceContainer[parts[partIndex]]) {
			namespaceContainer[parts[partIndex]] = {};
		}

		namespaceContainer = namespaceContainer[parts[partIndex]];

		partIndex++;
	}

	return namespaceContainer;
};