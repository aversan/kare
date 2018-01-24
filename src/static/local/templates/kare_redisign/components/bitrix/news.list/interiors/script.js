$(function() {
	$(document).on('click', '.js-interiors-more', function(e){
		var $this = $(this);
		var url = $this.attr('href');
		
		$.ajax({
			method: 'get',
			url: url,
			data: {
				ajax: 'Y'					
			},
			success: function(result) {
				var $result = $(result);
				
				$('.js-interiors-list').append($result.find('.js-interiors-list').html());
				$this.attr('href', $result.find('.js-interiors-more').attr('href'));
				
				var total = $this.data('total');
				var current = $result.find('.js-interiors-more').data('current');
				if (current == total)
					$this.closest('.interiors-more').remove();
				else
					$this.data('current', current);
			}
		});
		
		e.preventDefault();
		return false;
	});
});