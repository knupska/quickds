jQuery(document).ready(function() {
	
	Symphony.Language.add({
	    'Go to QuickDS' : false
	});
	
	// Add the 'show/hide all' button
	jQuery('form h2').append('<a class="button" title="Go to QuickDS" href="' + Symphony.WEBSITE + '/symphony/extension/quickds/edit/">' + Symphony.Language.get('Go to QuickDS') + '</a>');

});