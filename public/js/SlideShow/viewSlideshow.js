var viewSlideShow = {
    
    
    listenKeyboardEvents : function(slideShow){
        $(document).keydown(function(event){
            switch(event.keyCode){
                case 37 :
                    slideShow.switchToPrevSlide();
                    break;
                    
                case 39 : 
                    slideShow.switchToNextSlide();
                    break;
            };
        });
    },
    
    listenMouseEvents : function(SlideShow){
        document.getElementById("modalSlideLeft").addEventListener('click', function(){
            SlideShow.switchToPrevSlide();
        });
        document.getElementById('modalSlideRight').addEventListener('click', function(){
            SlideShow.switchToNextSlide();
        });
    },
    
    
    updateSlider : function(currentSlideIndex){
        
        $("#modalViewerStyle img").css('display', 'none');
        var currentSlide = $("#modalViewerStyle img.src").eq(currentSlideIndex);
        currentSlide.css('display', 'block'); 
    }

                    
    
    
}