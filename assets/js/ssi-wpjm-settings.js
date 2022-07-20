(function( $ ) {

	// Add Color Picker to all inputs that have .ssi-wpjm-input--color-picker
	$( function() {
		$( '.ssi-wpjm-input--color-picker' ).wpColorPicker();
	});

	$('body').on('click', '.ssi-wpjm-image-button', function(e) {
        e.preventDefault();
		
		// get the previous input.
		var inputID = $( this ).prev();
		var imgID = $( inputID ).prev().children('img')[0];

        ssi_wpjm_image_uploader = wp.media({
            title: 'Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function() {

			var attachment = ssi_wpjm_image_uploader.state().get('selection').first().toJSON();
            $( inputID ).val(attachment.id);
			$( imgID ).attr( 'src', attachment.sizes.full.url );
        })
        .open();
    });

	$('body').on('click', '.ssi-wpjm-image--remove', function(e) {

		// get the previous input.
		var img = $( this ).prev();
		
		$( img ).attr( 'src', $( this ).data( 'placeholder' ) );
		$( '#' + $( this ).data( 'input-id' ) ).val('');

	});

	$('body').on('click', '.ssi-wpjm-gallery-button', function(e) {

        e.preventDefault();

		// get the current values.
		currentValue = $( this ).prev().val();

		// if current value is currently empty.
		if ( currentValue === '' ) {
			currentValues = [];
		} else {
			currentValues = currentValue.split( ',' );
		}

		// get the current gallery images stored.
		var galleryImageIds = $( this ).prev();

		// get the gallery wrapper element.
		var galleryWrapper = galleryImageIds.prev();

		ssi_wpjm_gallery_uploader = wp.media({
            title: 'Image',
            button: {
                text: 'Use this image'
            },
            multiple: true
        }).on('select', function() {
			
			var attachments = ssi_wpjm_gallery_uploader.state().get('selection').toJSON();

			// loop through each of the attachments selected.
			$.each( attachments , function( index, val ) {

				// if this attachment id is not already in the current values array.
				if ( currentValues.indexOf( val.id + '' ) !== 0 ) {

					// add attachment ID to the array.
					currentValues.push( val.id );

					// output a new figure and image element on the page.

					// create a new figure element.
					imageFigure = $( '<figure class="ssi-wpjm-gallery-item"></figure>' );
					
					// create the removal span.
					removeSpan = $( '<span class="dashicons dashicons-no ssi-wpjm-gallery--remove"></span>' );
					removeSpan.attr( 'data-image-id', val.id );
					
					// add the span for removing.
					$( imageFigure ).prepend( removeSpan );
					
					imageEl = $( '<img class="ssi-wpjm-gallery-image">' )
					imageEl.attr( 'src', val.sizes.thumbnail.url );

					// add a new image to the figure.
					$( imageFigure ).prepend( imageEl );

					// append to the gallery wrapper.
					$( galleryWrapper ).prepend( imageFigure );


				}

			});

			// convert the current values array to a new values string.
			newValues = currentValues.toString();

			// set the input to the new current values.
			$( galleryImageIds ).val( newValues );
			
        })
        .open();

	});

	$('body').on('click', '.ssi-wpjm-gallery--remove', function(e) {
		
		// get the previous input.
		var img = $( this ).prev();
		var figure = $( img ).parent();

		// remove this image.
		figure.remove();

		// get the current value of the background images input.
		// NEED TO FIND THIS DYNAMICALLY BASED ON WHERE WE ARE ON CLICK!
		var currentImages = $( '#ssi_wpjm_background_images-input').val();
		var currentImagesArray = currentImages.split( ',' );

		var imageID = $( this ).data( 'image-id' );

		// find the key of this image id in the current images array.
		var key = currentImagesArray.indexOf( imageID + '' );
		
		// remove the imageID from the current images array.
		currentImagesArray.splice( key, 1 );

		// NEED TO FIND THIS DYNAMICALLY BASED ON WHERE WE ARE ON CLICK!
		$( '.ssi-wpjm-input--gallery' ).val( currentImagesArray );		

	});






	/* Live preview settings */

	/* Logo size */
	var logoSize = document.querySelector("#ssi_wpjm_logo_size");

	logoSize.addEventListener("change", function() {
		document.querySelector(".hdsmi-template").style.setProperty("--hdsmi--logo--height", this.value);
	});

	/* Text color */
	$('#ssi_wpjm_text_color').iris({
		//hide: false,
		change: function(event, ui) {
			// event = standard jQuery event, produced by whichever control was changed.
			// ui = standard jQuery UI object, with a color member containing a Color.js object
	
			// update customproperty on 'Clear' button press.
			$('.wp-picker-clear').on('click', function(){
				
				// update the custom property.
				document.querySelector(".hdsmi-template").style.setProperty("--hdsmi--text--background-color", 'transparent');

			});

			// update the custom property.
			document.querySelector(".hdsmi-template").style.setProperty("--hdsmi--text--color", ui.color.toString());
			
			// update the color preview background color.
			$(this).parents('.wp-picker-container').find('.button.wp-color-result').css('background-color', ui.color.toString());

		}
	});

	/* Text background color */
	//var added_clearer = false;
	$('#ssi_wpjm_text_bg_color').iris({
		//hide: false,
		change: function(event, ui) {
			// event = standard jQuery event, produced by whichever control was changed.
			// ui = standard jQuery UI object, with a color member containing a Color.js object
	
			// update customproperty on 'Clear' button press.
			$('.wp-picker-clear').on('click', function(){
				
				// update the custom property.
				document.querySelector(".hdsmi-template").style.setProperty("--hdsmi--text--background-color", 'transparent');

			});
			
			// update the custom property.
			document.querySelector(".hdsmi-template").style.setProperty("--hdsmi--text--background-color", ui.color.toString());
			
			// update the color preview background color.
			$(this).parents('.wp-picker-container').find('.button.wp-color-result').css('background-color', ui.color.toString());

		}
	});


	/* Background color */
	$('#ssi_wpjm_bg_color').iris({
		//hide: false,
		change: function(event, ui) {
			// event = standard jQuery event, produced by whichever control was changed.
			// ui = standard jQuery UI object, with a color member containing a Color.js object
	
			// update customproperty on 'Clear' button press.
			$('.wp-picker-clear').on('click', function(){
				
				// update the custom property.
				document.querySelector(".hdsmi-template").style.setProperty("--hdsmi--text--background-color", 'transparent');

			});

			// update the custom property.
			document.querySelector(".hdsmi-template").style.setProperty("--hdsmi--background-color", ui.color.toString());

			// update the color preview background color.
			$(this).parents('.wp-picker-container').find('.button.wp-color-result').css('background-color', ui.color.toString());

		}
	});

	/* Title font size */
	var titleFontSize = document.querySelector("#ssi_wpjm_title_size");

	titleFontSize.addEventListener("change", function() {
		document.querySelector(".hdsmi-template").style.setProperty("--hdsmi--title--font-size", this.value);
	});

	/* Location font size */
	var locationFontSize = document.querySelector("#ssi_wpjm_location_size");

	locationFontSize.addEventListener("change", function() {
		document.querySelector(".hdsmi-template").style.setProperty("--hdsmi--location--font-size", this.value);
	});

	/* Salary font size */
	var salaryFontSize = document.querySelector("#ssi_wpjm_salary_size");

	salaryFontSize.addEventListener("change", function() {
	var newFontSize = this.value;
		document.querySelector(".hdsmi-template").style.setProperty("--hdsmi--salary--font-size", newFontSize);
	});

	/* Template choice */
	var templateChoice = document.querySelector("#ssi_wpjm_template");

	templateChoice.addEventListener("change", function() {
	
		var template = document.querySelector(".hdsmi-template");
	
		template.classList.remove('hdsmi-template--1', 'hdsmi-template--2', 'hdsmi-template--3', 'hdsmi-template--4', 'hdsmi-template--5');
	
		template.classList.add("hdsmi-template--" + this.value);
	
	});

	/* Logo file */
	$('img.ssi-wpjm-image').on('load', function () {
		$('.hdsmi-template__logo').attr('src', $('img.ssi-wpjm-image').attr('src'));
	});

	/* Background images */
		
	$('img.ssi-wpjm-gallery-image').on('load', function () {

		console.log('Image loaded');

		imgSrc = $(this).attr('src');
		fullImgSrc = imgSrc.replace("-150x150", "");
		$('.hdsmi-template__image').attr('src', fullImgSrc);

	});

	$(document).on('click', $('.ssi-wpjm-gallery-image'), function () {

		console.log('clicked');

		console.log( $(this) );

		// get the image source.
		var imgSrc = $(this).attr('src');

		console.log('imgSrc = ' + imgSrc);

		// set template image source.
		$('.hdsmi-template__image').attr('src', imgSrc);

	});

})( jQuery );