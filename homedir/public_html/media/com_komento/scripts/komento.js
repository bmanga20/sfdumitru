Komento.ready(function($) {
	$(function() {
		$('#section-kmt').responsive([
			{at: 818, switchTo: 'w768'},
			{at: 600, switchTo: 'w600'},
			{at: 400, switchTo: 'w320'}
		]);
	});
});
