/*
 * jquery.chain-height.js v1.0.5
 *
 * Description: Synchronizes heights of selected elements on window resize
 * Copyright: http://www.agentimage.com
 * License: Proprietary
 */
 
( function() {
	
	jQuery.fn.chainHeight = function(settings) {
		
		ChainHeight( jQuery(this), settings );
		return jQuery(this);
		
	}
		
	function ChainHeight(elem,settings) {
		
		var target = jQuery(elem);
		settings = jQuery.extend({
            'master': false,
			'breakpoints':[
				{
					'min':0,
					'max':'none'
				}
			],
            'refreshDelay':1000
		},settings);
		
		( function() {
			
			doResizeEvents();
			jQuery(window).resize(doResizeEvents);
			jQuery(window).load(doResizeEvents);
			
		})();
		
		function doResizeEvents() {
            
            setTimeout( function() {
                chainHeights();
            },settings.refreshDelay);
            
		}
		
		function allowedToRun() {
			
			var winWidth = jQuery(window).width();
			var result = false;
			
			jQuery.each( settings.breakpoints, function(index,elem) {
				
				/* If window width is within breakpoints */
				if ( winWidth >= elem.min && winWidth <= elem.max ) {
					result = true;
				}
				
				/* If there is no max width */
				else if ( winWidth >= elem.min && elem.max == 'none' ) {
					result = true;
				}
				
				/* Else default */
				else {
					result = false;
				}
				
			});
			
			return result;
			
		}
		
		function chainHeights() {
			
			var height = 0;
			target.css('height','auto');
			
			if ( allowedToRun() ) {
                
                if ( jQuery(settings.master).length > 0 ) {
                    height = jQuery(settings.master).height();
                }
                
                else {
					
					/* Loop through elements */
                    target.each( function(i,v) {
                        
						var elemHeight = jQuery(v).outerHeight();
                        
						/* Check box-sizing */
						if ( jQuery(v).css('box-sizing') == 'border-box' ) {
							elemHeight += ( parseInt( jQuery(v).css('padding-top') ) + parseInt( jQuery(v).css('padding-bottom') ) );
						}
							
						/* Compare heights */
						if ( elemHeight > height ) {
                            height = elemHeight;
                        }
						
                    });
					
                }
                
			}
			else {
				
				/* Reset height */
				height = 'auto';
				
			}
			
			/* Loop through elements */
            target.each( function(i,v) {
				
				/* Check box-sizing */
				if ( jQuery(v).css('box-sizing') != 'border-box' ) {
					height -= ( parseInt( jQuery(v).css('padding-top') ) + parseInt( jQuery(v).css('padding-bottom') ) );
				}
				
				/* Apply height */
				jQuery(v).css('height',height);
				
			});
			
		}
		
	}

})();