
jQuery(document).ready(function ($) {
	$('.list-select').each(function(){
		var $this = $(this);
		$this.addClass('initialized').on('click', function(e){
			$this.toggleClass('active');
		});
	});
	$('.tab-link').each(function(){
		var $this = $(this),
			$parent = $this.parent(),
			$section = $parent.closest('.tab-section'),
			$target = $($this.attr('href'));
		if (!$section.hasClass('initialized')) {
			$section.addClass('initialized');
		}
		$this.on('click', function(e){
			e && (e.preventDefault());
			if ($parent.hasClass('active')) {
				return;
			}
			$section.find('.active').removeClass('active');
			$target.addClass('active');
			$parent.addClass('active');
		});
	});
});
