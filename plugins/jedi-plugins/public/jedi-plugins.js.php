<?php header('Content-Type: text/javascript'); ?>
// Server Tracker Widget
function initServerTrackers() {
	jQuery('.jedi_widget_tracker').each(function() {
		var tracker = jQuery(this);
		var content = tracker.find('ul');
		var server = content.find('dl dd').text();
		
		// Create the Refresh link and spinner
		var refreshContainer = jQuery('<div class="refresh">');
		var refreshLink = jQuery('<a href="#">Refresh</a>');
		refreshLink.click(function(e) {
			e.preventDefault();
			var playersVisible = content.find('.players').is(':visible');
			jQuery.ajax({
				url: '<?php echo dirname($_SERVER['REQUEST_URI']) . '/servertracker.php' ?>',
				type: 'POST',
				data: {server: server},
				dataType: 'html',
				beforeSend: function(jqXHR, settings) {
					refreshContainer.addClass('spinning');
				},
				success: function(data, textStatus, jqXHR) {
					content.html(data);
					if (playersVisible) {
						content.find('.players').show();
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					content.html('<li>Error: ' + textStatus + '</li>');
				},
				complete: function(jqXHR, textStatus) {
					refreshContainer.removeClass('spinning');
				}
			});
		});
		refreshContainer.append(refreshLink);
		content.before(refreshContainer);
		
		// Trigger the first update
		refreshLink.trigger('click');
	});
}

// Init
jQuery(document).ready(function() {
	jQuery.ajaxSetup({cache: false});
	initServerTrackers();
});
