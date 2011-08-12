/**
* @version 1.3 RC4
* @package DJ Image Slider
* @subpackage DJ Image Slider Module
* @copyright Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
*
*
* DJ Image Slider is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Image Slider is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Image Slider. If not, see <http://www.gnu.org/licenses/>.
*
*/
(function($){

var DocumentLoaded = false;

window.addEvent('load',function(){DocumentLoaded = true});

this.DJImageSlider = new Class({

    initialize: function(settings, options){

        var slider_size = 0;
        var loaded_images = 0;
        var max_slides = 0;
        var current_slide = 0;
        var slider = 'slider' + settings.id;
        var autoplay = options.auto;
        var stop = 0;
        var show_nav = 0;
		var is_fading = false;
        
        var slides = $('slider'+ settings.id).getChildren('li');
        slides.each(function(){
            slider_size += settings.slide_size;
            loaded_images++;
        })
        
        max_slides = loaded_images - settings.visible_slides;
		
        $(slider).setStyle('position', 'relative');
		
        var slideImages;
		if (settings.slider_type == 2) { // fade
			slides.setStyle('position', 'absolute');
			slides.setStyle('top', 0);
			slides.setStyle('left', 0);
			$(slider).setStyle('width', settings.slide_size);
			slides.setStyle('opacity',0);
			slides[0].setStyle('opacity',1);
			slides[0].setStyle('z-index','10');
			$('navigation' + settings.id).setStyle('z-index',20);
			slides.set('tween',{property: 'opacity', duration: options.duration});
						
		} else if (settings.slider_type == 1) { // vertical
            $(slider).setStyle('top', 0);
            $(slider).setStyle('height', slider_size);
            slideImages = new Fx.Tween(slider, {
				property: 'top', 
                duration: options.duration,
                transition: options.transition,
                link: 'cancel'
            });
        }
        else { // horizontal
            $(slider).setStyle('left', 0);
            $(slider).setStyle('width', slider_size);
            slideImages = new Fx.Tween(slider, {
				property: 'left', 
                duration: options.duration,
                transition: options.transition,
                link: 'cancel'
            });
        }
        
		// navigation effects
		if (settings.show_buttons==1) {
			var play = new Fx.Tween('play' + settings.id, {
				property: 'opacity', 
				duration: 200,
				link: 'cancel'
			}).set('opacity',0);
			var pause = new Fx.Tween('pause' + settings.id, {
				property: 'opacity', 
				duration: 200,
				link: 'cancel'
			}).set('opacity',0);
		}
		if (settings.show_arrows==1) {
			var nextFx = new Fx.Tween('next' + settings.id, {
				property: 'opacity', 
				duration: 200,
				link: 'cancel'
			}).set('opacity',0);
			var prevFx = new Fx.Tween('prev' + settings.id, {
				property: 'opacity', 
				duration: 200,
				link: 'cancel'
			}).set('opacity',0);
		}
		
        $('next' + settings.id).addEvent('click', function(){
            if (settings.show_buttons==1) hideNavigation();
            nextSlide();
        });        
        $('prev' + settings.id).addEvent('click', function(){
            if (settings.show_buttons==1) hideNavigation();
            prevSlide();
        });        
        $('play' + settings.id).addEvent('click', function(){
            changeNavigation();
            autoplay = 1;
        });        
        $('pause' + settings.id).addEvent('click', function(){
            changeNavigation();
            autoplay = 0;
        });
        
		$('djslider-loader' + settings.id).addEvents({
            'mouseenter': function(){
                if (settings.show_buttons==1) showNavigation();
				if (settings.show_arrows==1) {
					nextFx.start(1);
					prevFx.start(1);
				}
				stop = 1;
            },
            'mouseleave': function(){
                if (settings.show_buttons==1) hideNavigation();
				if (settings.show_arrows==1) {
					nextFx.start(0);
					prevFx.start(0);
				}
				stop = 0;
            }
        });
		
		var buttons = $('cust-navigation' + settings.id).getElements('.load-button');
		buttons.each(function(el,index){
			el.addEvent('click',function(e){
				if (!is_fading && !el.hasClass('load-button-active')) {
					loadSlide(index);
				}
			});
		});
		
		function updateActiveButton(active){
			buttons.each(function(button,index){
				button.removeClass('load-button-active');
				if(index==active) button.addClass('load-button-active');
			});			
		}
		
		function loadSlide(i) {
			if (settings.slider_type == 2) {
				if(is_fading) return;
				is_fading = true;
				prev_slide = current_slide;
				current_slide = i;
				makeFade(prev_slide);
				
			}
			else {
				current_slide = i;
				slideImages.start(-settings.slide_size * current_slide);
				updateActiveButton(current_slide);
			}
		}
		
        function nextSlide(){
			if (settings.slider_type == 2)
				nextFade();
			else {
				if (current_slide < max_slides) 
					current_slide++;
				else 
					current_slide = 0;
				slideImages.start(-settings.slide_size * current_slide);
				updateActiveButton(current_slide);
			}
        }
        
        function prevSlide(){
			if (settings.slider_type == 2) {
				prevFade();
			}
			else {
				if (current_slide > 0) {
					current_slide--;
				}
				else {
					current_slide = max_slides;
				}
				slideImages.start(-settings.slide_size * current_slide);
				updateActiveButton(current_slide);
			}
        }
        
		function nextFade(){
			if(is_fading) return;
			is_fading = true;
			prev_slide = current_slide;
			if (current_slide < max_slides) {
				current_slide++;
			}
			else {
				current_slide = 0;
			}
				
			makeFade(prev_slide);
		}
		
		function prevFade(){
			if(is_fading) return;
			is_fading = true;
			prev_slide = current_slide;
			if (current_slide > 0) {
				current_slide--;
			}
			else {
				current_slide = max_slides;
			}
			
			makeFade(prev_slide);			
		}
		
		function makeFade(prev_slide){
			slides[current_slide].get('tween').set(1);
			slides[prev_slide].get('tween').start(0).chain(function(){
				slides[prev_slide].setStyle('z-index',0);
				slides[current_slide].setStyle('z-index',10);
				is_fading = false;
			});
			updateActiveButton(current_slide);
		}
		
        function hideNavigation(){
            if (!autoplay) {
                play.start(stop, 0).chain(function(){
                    if (!show_nav) 
                        $('play' + settings.id).setStyle('display', 'none');
                });
            }
            else {
                pause.start(stop, 0).chain(function(){
                    if (!show_nav) 
                        $('pause' + settings.id).setStyle('display', 'none');
                });
            }
            show_nav = 0;
        }
        
        function showNavigation(){
            if (!autoplay) {
                $('play' + settings.id).setStyle('display', 'block');
                play.start(stop, 1);
            }
            else {
                $('pause' + settings.id).setStyle('display', 'block');
                pause.start(stop, 1);
            }
            show_nav = 1;
        }
        function changeNavigation(){
            if (autoplay) {
                $('pause' + settings.id).setStyle('display', 'none');
                if (settings.show_buttons==1) pause.set('opacity',0);
                $('play' + settings.id).setStyle('display', 'block');
                if (settings.show_buttons==1) play.set('opacity',1);
            }
            else {
                $('play' + settings.id).setStyle('display', 'none');
                if (settings.show_buttons==1) play.set('opacity',0);
                $('pause' + settings.id).setStyle('display', 'block');
                if (settings.show_buttons==1) pause.set('opacity',1);
            }
        }
        
        function slidePlay(){
            setTimeout(function(){
                if (autoplay && !stop) 
                    nextSlide();
                slidePlay();
            }, options.delay);
        }
		
		function sliderLoaded(){
			// hide loader and show slider
			if (/\bMSIE 8.0\b/.test(navigator.appVersion)) { // only for IE8
				var visibles = new Array();
				for (var i = 0; i < settings.visible_slides; i++) {
					visibles[i] = slides[i];
				}
				visibles.each(function(el){
					el.setStyle('opacity', 0);
				});
			}
			
			$('djslider' + settings.id).setStyle('opacity',0);
			$('djslider' + settings.id).setStyle('display','block');
			$('djslider-loader' + settings.id).setStyle('background','url(blank.gif)');
			
			$('djslider' + settings.id).fade('in');
			
			if (/\bMSIE 8.0\b/.test(navigator.appVersion)) { // only for IE8
				visibles.each(function(el){
					el.fade('in');
				});
			}
			// count and change djslider dimensions
			buttons_height = $('next' + settings.id).getStyle('height').toInt();
			buttons_height = Math.max(buttons_height,$('prev' + settings.id).getStyle('height').toInt());
			//$('navigation' + settings.id).setStyle('height',buttons_height);
        	button_pos = $('navigation' + settings.id).getStyle('top').toInt();
        	djslider_height = $('djslider' + settings.id).getStyle('height').toInt();
			if(button_pos > 0) {
				new_height = buttons_height + button_pos;
			} else {
				new_height = djslider_height - button_pos;
			}        	
        	if (new_height > djslider_height) {
            	$('djslider' + settings.id).setStyle('height', new_height);
				$('djslider-loader' + settings.id).setStyle('height', new_height);
        		if (button_pos < 0) {
					$('navigation' + settings.id).setStyle('top', 0);
					$('slider-container' + settings.id).setStyle('top', -button_pos);
				}
        	}
			buttons_margin = $('navigation' + settings.id).getStyle('margin-left').toInt() + $('navigation' + settings.id).getStyle('margin-right').toInt();
			djslider_width = $('djslider' + settings.id).getStyle('width').toInt();
			if(buttons_margin < 0) {
				$('djslider-loader' + settings.id).setStyle('width', djslider_width - buttons_margin);
			}
			nav_width = $('navigation' + settings.id).getStyle('width').toInt();
			play_width = $('play' + settings.id).getStyle('width').toInt();
			$('play' + settings.id).setStyle('left',(nav_width/2 - play_width/2));
			pause_width = $('play' + settings.id).getStyle('width').toInt();
			$('pause' + settings.id).setStyle('left',(nav_width/2 - pause_width/2));
			
			if(autoplay) {
				$('play' + settings.id).setStyle('display','none');
			} else {
				$('pause' + settings.id).setStyle('display','none');
			}
			
			// start autoplay
			slidePlay();
		}
		
		if(settings.preload) sliderLoaded.delay(settings.preload);
		else if (DocumentLoaded) sliderLoaded();
		else window.addEvent('load', sliderLoaded);
        
    }
    
});

})(document.id);