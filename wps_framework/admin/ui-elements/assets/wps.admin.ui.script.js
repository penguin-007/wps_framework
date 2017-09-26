(function ($) {

	/* WPS UI Image */
	$('body').on( 'click', '.wps__ui_image__holder', function(){
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var image_wrap = $(this);
		wp.media.editor.send.attachment = function (props, attachment) {
			image_wrap.find('img').attr('src', attachment.url);
			image_wrap.find('input').val(attachment.id);
			wp.media.editor.send.attachment = send_attachment_bkp;
		};
		wp.media.editor.open(image_wrap);
		return false;
	});
	$('body').on('click', '.wps__ui_image__remove', function(){
		var image_wrap = $(this).parent();
		if(confirm('Удалить?')) {
			image_wrap.find('img').attr('src', '');
			image_wrap.find('input').val( '' );
			return false;
		}
	});

	/* WPS UI Gallery */
	$('body').on('click', '.wps__simple_gallery__add_before', function(){
		var gallery_holder = $(this).parent();
		var gallery_clone  = gallery_holder.find('.wps__simple_gallery__clone').html();
		var gallery_wrap   = gallery_holder.find('.wps__simple_gallery__wrap');
		gallery_wrap.prepend( gallery_clone );
		return false;
	});
	$('body').on('click', '.wps__simple_gallery__remove_item', function(){
		var item = $(this).parent();
		if(confirm('Удалить?')) {
			item.remove();
			return false;
		}
	});
	$( ".wps__simple_gallery__wrap" ).sortable();
  $( ".wps__simple_gallery__wrap" ).disableSelection();



	/* WPS UI Repeater */
	$('body').on('click', '.wps__repeater__add_before', function(){
		var repeater_holder = $(this).parent();
		var repeater_clone  = repeater_holder.find('.wps__repeater__clone').html();
		var repeater_wrap   = repeater_holder.find('.wps__repeater__wrap');
		var repeater_item   = repeater_holder.find('.wps__repeater__item');

		var ID = function () {
		  // Math.random should be unique because of its seeding algorithm.
		  // Convert it to base 36 (numbers + letters), and grab the first 9 characters
		  // after the decimal.
		  return '_' + Math.random().toString(36).substr(2, 9);
		};
		var uniqID = ID();

		repeater_clone      = repeater_clone.replace( /repeater_number/g, uniqID );
		repeater_wrap.prepend( repeater_clone );
		return false;
	});
	$('body').on('click', '.wps__repeater__remove_item', function(){
		var item = $(this).parent();
		var wrap = item.parent();
		if(confirm('Удалить?')) {
			item.remove();
			return false;
		}
	});
	$( ".wps__repeater__wrap" ).sortable();
  $( ".wps__repeater__wrap" ).disableSelection();



	/* WPS UI CheckBox ajax */
	$(".wps_save_my_checkbox").change( function(){
		var value = $(this).is(":checked");
		var key   = $(this).attr("data-key");
		var id    = $(this).attr("data-id");

		var data = {
			action: 'wps_save_checkbox',
			value: value,
			key: key,
			id: id
		};

		$.ajax({
			url: ajaxurl,
			type: "POST",
			data: data,
			success: function(data) {
				console.log(data);
			},
		});
	return false;
	});



	/* wps_ui_file */
	$('body').on( 'click', '.wps_ui_file_btn', function(){
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var file = $(this);
		wp.media.editor.send.attachment = function (props, attachment) {
			file.next('input').val(attachment.url);
			file.siblings('.wps_ui_file__name').text(attachment.name);
			wp.media.editor.send.attachment = send_attachment_bkp;
		};
		wp.media.editor.open(file);
		return false;
	});

  /* color picker */
  $('.wps_ui_input_color').wpColorPicker();

  /* wps__ui_button__ajax */
  $('body').on( 'click', '.wps__ui_button__ajax', function(){
  	var btn         = $(this);
  	var wp_action   = btn.data('ajax_action');
  	var set_timeout = btn.data('ajax_set_timeout');
  	var alert_b     = btn.siblings('.wps__ui_button__alert_holder');
  	var post_id     = btn.data("post-id");
  	var confirm_t   = btn.data("confirm");

  	// if confirm
  	if ( confirm_t != "" && !confirm(confirm_t) ) return false;

  	var data = {
			action: wp_action,
			post_id: post_id
		};

  	$.ajax({
			url: ajaxurl,
			type: "POST",
			data: data,
			beforeSend: function(){
				// btn active and blocked
				btn.addClass("active");
				btn.prop('disabled', true);
			},
			success: function(data) {
				// btn not active and not blocked
				btn.removeClass("active");
				btn.prop('disabled', false);
				// data
				alert_b.html(data);
				if ( set_timeout != '' ){
					setTimeout(function(){ alert_b.text(''); }, set_timeout);
				}
			},
			error: function(data){
				alert_b.text("Ошибка выполнения!");
			}
		});

		return false;
  });

 
  // textarea editor
  //$('.textarea__simple_editor').summernote(); 

  // select2
  $(".wps_ui_select2").select2();

  /* wps__row_color */
  $(".wps__row_color").each(
  	function(){
  		var _this   = $(this);
  		var wrapper = _this.parent().parent();
  		var color   = _this.text();
  		if ( color != "" ){
  			wrapper.css( "background", color );
  		}
  	}
  );
  

})(jQuery);