
jQuery(document).ready(function ($) {
    $('.list-select').each(function(){
        var $this = $(this);
        $this.addClass('initialized').on('click', function(){
            $this.toggleClass('active');
        });
    });
});
