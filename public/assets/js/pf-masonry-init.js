(function ( $ ) {
    "use strict";

    $(function() {
        $(document).ready(function(){
            // hiding pagination
            $(".pfPagination").css('opacity',0);
            
            // initialize Masonry            
            var $container = $('#pf_mason_grid').masonry({
                itemSelector: '.pf_prod_box',
                gutter: 10
            });
            // layout Masonry again after all images have loaded
            $container.imagesLoaded( function() {
                $container.masonry();
            });
            
        })
        
        // below will execute after page completely loads
        $(window).bind("load",function(){
            // showing pagination after page load
            $(".pfPagination").css('opacity','1');
            
            // hiding loading gif
            $(".pf_loadGif").fadeOut(400);
            
            // loading images slow
            setTimeout(function(){
                $('#pf_mason_grid .pf_prod_box').each(function(i){    
                    var $this = $(this);
                    setTimeout(function(){
                        $this.css('opacity','1');
                    },parseInt(i*400));
                });
            },500);
        });

    });
}(jQuery));