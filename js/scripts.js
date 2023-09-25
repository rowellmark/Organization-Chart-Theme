( function($) {

	var orgchart = {
		init: function(){
			this.legend_list();
			this.header_scroll();
			this.back_to_top();
			this.mobile_nav();
		
			if( $('#org-list').length > 0 ){
				this.orgchart_node();
			}

			if ( $('#homepage-chart').length > 0 ){
				this.homepage_node();
			}

			this.profile_details();

			$(window).load(function(){
				$('.canvas-loading-cover').delay(1000).fadeOut();
			});
			
		},

		orgchart_node: function(){

			$('#chart-container').orgchart({
			  'data' : $('#org-list'),
			  'nodeContent': '',
			  'depth': 9999,
			  'nodeId': 'id',
			});

			$(".orgchart .node").not('div.node[data-class*="node-large"], div.node[data-class*="node-medium"]').chainHeight();

			$('.node i.edge').remove();

		},

		homepage_node: function(){
			// homepage chart
			$('#chart-container').orgchart({
			  'data' : $('#homepage-chart'),
			  'nodeContent': '',
			  'depth': 9999,
			  'nodeId': 'id',
			});

			$(".orgchart .node").not('div.node[data-class*="node-large"], div.node[data-class*="node-medium"]').chainHeight();

			$('.node i.edge').remove();
		},

		legend_list: function(){

			$('.menu li.legend a').click(function(event){
				event.preventDefault();
				event.stopPropagation();
			});

			$('.legend-wrap').detach().appendTo('.menu li.legend a');

			$('.menu li.legend a').hover(function(){
				$(this).find('.legend-wrap').stop(true,true).slideDown();
			},function(){
				$(this).find('.legend-wrap').stop(true,true).slideUp();
			})
		},

		mobile_nav: function(){
			$('.mobile-nav').on('click','.hamburger',function(event){
				$(this).toggleClass('hamburger--spin is-active');
				$(this).parent().find('nav').stop(true,true).slideToggle();
			})
		},

		header_scroll: function(){
			$( window ).on( 'load resize scroll', function() {
		       var scroll = jQuery(window).scrollTop();
		       if(scroll >= 20 && $(window).width() > 992 ) {
		       		$("#main-header").addClass('fixed-header');
		       }
		       else {
		       		$("#main-header").removeClass('fixed-header');
		       }
		   	});
		},


		profile_details: function(){

			popup_main = $('.popup-main-wrapper');

			// get details and show popup
			$('.orgchart .node').click(function(event){

				event.preventDefault();
				event.stopPropagation();

				// check if link - then redirect
				if(  $(this).data("link") != "" && $(this).data("link") != undefined ) {

					window.location.href = $(this).data("link");
					return;

				}

				if ( $(this).attr("id").substring(0, 1) == "#" ){
					return;
				}

				// show popup
				$('body').addClass('no-scroll');
				popup_main.slideDown();
				$('.popup-main-wrapper .popup-inner').empty();
				$('.popup-main-wrapper .popup-loader').fadeIn();
				$('.popup-main-wrapper .popup-inner').fadeOut('fast');

				// get details
				jQuery.post(
				    'http://aios2-staging.agentimage.com/o/orgchart.thedesignpeople.net/htdocs/wp-admin/' + 'admin-ajax.php', 
				    {
				        'action': 'get_profiles',
				        'type': 'POST',
				        'cache': false, 
				        'dataType': 'json', 
				        'data': $(this).attr('id'),
				    }, 

				    function(response){

				        if (JSON.parse(response) != false){

				        	$('.popup-main-wrapper .popup-inner').append(JSON.parse(response));

				        	$('.popup-main-wrapper .popup-loader').fadeOut('slow',function(){
				        		$('.popup-inner').fadeIn();
				        	});

				        }
				    }
				)
				
			});

			// close popup
			$('.popup-main-wrapper').on('click','.popup-close-btn span, .popup-close-btn em',function(event){
				popup_main.slideUp();
				$('body').removeClass('no-scroll');
			});

		},

		back_to_top: function(){

			// hide on ready
			$('.scroll-to-top').animate({
				bottom: "-" + $('.scroll-to-top').outerHeight()
			})

			$('.scroll-to-top').click(function(){
				jQuery('html,body').animate({
					scrollTop: 0
				})
			});

			$(window).scroll(function(){
				if( $(window).scrollTop() >= 350 ) {
					if( !$('.scroll-to-top').hasClass('show') ){
						$('.scroll-to-top').addClass('show');
						$('.scroll-to-top').stop(true,true).animate({
							bottom: 0
						})
					}
				}else {
					if( $('.scroll-to-top').hasClass('show') ){
						$('.scroll-to-top').removeClass('show');
						$('.scroll-to-top').stop(true,true).animate({
							bottom: "-" + $('.scroll-to-top').outerHeight()
						})
					}
				}
			})

		}

	}
	
	jQuery(document).ready( function() {
		orgchart.init();
	});
	
})(jQuery);


