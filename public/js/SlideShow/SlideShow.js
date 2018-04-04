class SlideShow {
    constructor (currentSlide,slideLength,duration){
        
        this.slideLength = slideLength;
        this.currentSlide = currentSlide;
        this.duration = duration;
    };
    
    init(){
        
      
        viewSlideShow.updateSlider(this.currentSlide);
        //keyBoardControl
        viewSlideShow.listenKeyboardEvents(this);
        //MouseControl
        viewSlideShow.listenMouseEvents(this);
        
        //Slider
        this.setSlideInterval();
        
    };

    switchToNextSlide(){
        if( this.currentSlide == this.slideLength ){
            this.currentSlide=0;
                        
        }
        else{
            this.currentSlide ++;
        };
        
        viewSlideShow.updateSlider(this.currentSlide);

    };
  
    switchToPrevSlide(){
        if( this.currentSlide == 0 ){
            this.currentSlide = this.slideLength;
        }
        
        else{
            this.currentSlide--;
        };
        viewSlideShow.updateSlider(this.currentSlide);
    };
    
    setSlideInterval(){    
        setInterval(function(){ 

            this.switchToNextSlide();
        }.bind(this), this.duration); 

    };





}