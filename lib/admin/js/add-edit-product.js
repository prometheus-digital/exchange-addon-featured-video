jQuery(document).ready(function($) {
	/*
	 * We want the ability to edit the data for the
	 * thumbnails. Clicking on the any of the items
	 * will open this frame with the selected item.
	*/
	it_exchange_featured_video_upload_frame = {
		frame: function() {
			if ( this._frame )
				return this._frame;

			this._frame = wp.media({
				title: 'Upload',
				button: {
					text: 'Insert'
				},
				library: {
					type: 'video'
				},
				multiple: false
			});

			this._frame.on( 'open', this.open ).on( 'close', this.close ).state('library').on( 'select', this.select );
			
			return this._frame
		},
		
		open: function() {
			
		},
		
		select: function() {
			source = this.get( 'selection' ).single().toJSON();
			
			$( '#featured-video' ).val( source.url );
			
			if ( source.url !== $( this ).parent().attr( 'data-current' ) ) {
				$( '.featured-video-placeholder' ).hide();
			}
		},
		
		init: function() {
			$( '#wpbody' ).on( 'click', '.it-exchange-featured-video-upload', function( event ) {
				event.preventDefault();
				
				it_exchange_featured_video_upload_frame.frame().open();
			});
		}
	};

	it_exchange_featured_video_upload_frame.init();
	
	$( '#featured-video' ).on( 'focusout', function() {
		if ( $( this ).val() !== $( this ).parent().attr( 'data-current' ) ) {
			$( '.featured-video-placeholder' ).hide();
		}
	});
	
	$( '.featured-video-embeded' ).fitVids();
});