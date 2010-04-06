jQuery(document).ready(function() {
	
	// Add the QuickDS shortcut for 2.0.7+
	if(Symphony.Language.add) {
		Symphony.Language.add({'Go to QuickDS':false});
		jQuery('form h2').append('<a class="button" title="Go to QuickDS" href="' + Symphony.WEBSITE + '/symphony/extension/quickds/edit/">' + Symphony.Language.get('Go to QuickDS') + '</a>');
		
	// Add the QuickDS shortcut for 2.0.6
	} else {
		$website = jQuery('script[src]')[0].src.match('(.*)/symphony')[1];
		jQuery('form h2').append('<a class="button" title="Go to QuickDS" href="' + $website + '/symphony/extension/quickds/edit/">' + 'Go to QuickDS' + '</a>');
	}
});