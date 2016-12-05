
jQuery(document).ready(function ($) {
    $('.list-select').each(function(){
        var $this = $(this);
        $this.addClass('initialized').on('click', function(e){
            $this.toggleClass('active');
        });
    });
    $('.sibling-toggle-control').each(function(){        
        var $this = $(this),
            $parent = $this.closest('.sibling-toggle-parent');
        $this.addClass('initialized')
        .on('click', function(e){
            e && (e.preventDefault());
            if($this.hasClass('active')){
                return;
            }
            $parent.find('.active').removeClass('active');
            $($this.attr('href')).addClass('active');
            $this.addClass('active');
        });
    });
});
