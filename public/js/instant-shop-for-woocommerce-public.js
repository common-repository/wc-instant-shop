(function($) {
	'use strict';
	
	$(window).load(function(){
       
		var checkout_page_option = isfw_ajax_obj.checkout_option;
		if (checkout_page_option=='yes'){
			
			// Single page add to cart button ajax
			$('form.cart').on('submit', function(e) {
				e.preventDefault();
				var form = $(this);
				form.block({ message: null, overlayCSS: { background: '#fff', opacity: 0.6 } });
				var formData = new FormData(form[0]);
				formData.append('add-to-cart', form.find('[name=add-to-cart]').val() );
				
				// Ajax action.
				$.ajax({
					url: wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'ace_add_to_cart' ),
					data: formData,
					type: 'POST',
					processData: false,
					contentType: false,
					complete: function( response ) {
						var str= '&action=isfw_woocommerce_button_after__add_to_cart';
						jQuery.ajax({
							dataType: "html",
							url: isfw_ajax_obj.ajaxurl,
							data: str,
							success: function (data) {
	
								var $data = jQuery(data);
								if ($data.length) {
									jQuery("#isfw-checkout").html($data);
									var url = isfw_ajax_obj.checkout_ajax_url;
									$.getScript(url);
									$('html, body').animate({
	
										scrollTop: $("#isfw-one-page-checkout").offset().top
									}, 1000);      
								}
							},
							error: function (jqXHR, textStatus, errorThrown) {
							alert(isfw_ajax_obj.isfw_ajax_issue);
							}
						});
						response = response.responseJSON;
						if ( ! response ) {
							return;
						}

						if ( response.error && response.product_url ) {
							window.location = response.product_url;
							return;
						}
	
						// Redirect to cart option
						if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {
							window.location = wc_add_to_cart_params.cart_url;
							return;
						}
	
						var $thisbutton = form.find('.single_add_to_cart_button'); //
						//var $thisbutton = null; // uncomment this if you don't want the 'View cart' button
						
						$( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ] );
						// Remove existing notices
						$( '.woocommerce-error, .woocommerce-message, .woocommerce-info' ).remove();
						// Add new notices
						form.closest('.product').before(response.fragments.notices_html)
						form.unblock(); 
	
					}
				});
			});
		}
	
		// Order again button functionality.
		$('.ATC-order-again').click(function(e){
			e.preventDefault();
			var i = 0;
			var product = [];
			var order_id = $(this).attr('data-orderid');
			if($('.isfw_select_product').is(':checked')) {
				$('.isfw_select_product:checked').each(function (){
					product[i++] = $(this).val();
				});
	
				$.ajax({
					type : "post",
					beforeSend: function(){
						$('.ATC-order-again').attr('disabled',true);
						$('.isfw_modal-footer .isfw_loader-image').css("display", "block"); 
						$('.isfw_success-message').css("display", "none"); 
						$('.isfw_ofc-message').css("display", "none"); 
					},
					dataType : "json",
					url : isfw_ajax_obj.ajaxurl,
					data : {action: "isfw_addition_in_add_to_cart_function", product : product },
					success: function (response) {
						jQuery.ajax({
							type: "POST",
							dataType: "json",
							url: isfw_ajax_obj.ajaxurl,
							data: { 
									action: 'isfw_reload_table_data', 
									orderid: order_id 
							},
							success: function (data) {
								$('.isfw_ofc-message').html('');
								$('.isfw_success-message').html('');
								$(document.body).trigger('wc_fragment_refresh'); 
								var instock_message=response.success_message;
								var outofstock_message=response.not_in_message;
								if( instock_message ){
									$('.isfw_success-message').append(instock_message); 
									$('.isfw_success-message').css("display", "block"); 
								}
	
								if( outofstock_message ){
									outofstock_message.forEach(function(outofstock_message) {
									$('.isfw_ofc-message').append(outofstock_message); 
									$('.isfw_ofc-message').css("display", "block"); 
									});
								}
															
								$('.ATC-order-again').attr('disabled',false);
								$('.isfw_modal-footer .isfw_loader-image').css("display", "none");
									var data = jQuery(data);
								$('#isfw-popup-data-'+order_id).html('');
								$('#isfw-popup-data-'+order_id).append(data);
																
							},
							error: function (jqXHR, textStatus, errorThrown) {
								alert(isfw_ajax_obj.isfw_ajax_issue);
							}
						});
					},
				});
			}
			else{
              
				alert( isfw_ajax_obj.isfw_select_product);
			}
		});
	
		// Ordrer again popup close button.
		$(document).on('click', '.isfw_box .button', function(){
			var id = $(this).data('orderid');
			$('#order-again-' + id ).css('visibility', 'visible');
		});
	
		// popup close butoon functionality
		$(document).on('click', '.isfw_close', function(){
			$('.isfw_overlay').css('visibility', 'hidden');
			$('.isfw_success-message').css("display", "none");  
			$('.isfw_ofc-message').css("display", "none"); 
			$(".isfw_select_product").prop("checked", false);
		});
	
		// Select All products at once.
		$(document).on('click', '.isfw_select_all', function(){
			var order_id = $(this).data('orderid');
			var checked = !$(this).data('checked');
			$('#isfw-popup-data-'+order_id+' .isfw_select_product').prop('checked', checked);
			$(this).data('checked', checked);
		});
				
		// Reload page after removing item from minicart.
		$(document).on('click', '.remove_from_cart_button', function() {
			setTimeout(function(){ location.reload();}, 1000);
		});
	});
})(jQuery);
	