<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-sm-12">
				<section class="panel">
					<header class="panel-heading">
						
						<div class="col-md-11">
							Data Geofencing
						</div>
						<div class="col-md-1">
							<button class="btn btn-round btn-primary">Delete</button>
						</div>
						<br>

					</header>
					<div class="panel-body">
						<div id="map"></div>
					</div>
				</section>
			</div>
		</div>
		<!-- page end-->
	</section>
	<!-- /wrapper -->
</section>

<script type="text/javascript">
	var drawingManager;
	var selectedShape;
	var colors = [ '#1E90FF', '#FF1493', '#32CD32', '#FF8C00', '#4B0082' ];
	var selectedColor;
	var colorButtons = {};

	var map; //= new google.maps.Map(document.getElementById('map'), {
	// these must have global refs too!:
	var placeMarkers = [];
	var input;
	var searchBox;
	var curposdiv;
	var curseldiv;

	function initMap() {
		// The location of Uluru
		var myLatlng = new google.maps.LatLng( -6.930447, 107.654425 );
		var myOptions = {
			zoom: 19,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			disableDefaultUI: false,
			zoomControl: true
		}
		map = new google.maps.Map( document.getElementById( "map" ),
			myOptions );

		var polyOptions = {
			strokeWeight: 0,
			fillOpacity: 0.45,
			editable: true
		};

		drawingManager = new google.maps.drawing.DrawingManager( {
			drawingMode: google.maps.drawing.OverlayType.NULL,
			markerOptions: {
				draggable: true,
				editable: true,
			},
			polylineOptions: {
				editable: true
			},
			drawingControlOptions: {
				position: google.maps.ControlPosition.TOP_CENTER,
				drawingModes: [ 'polygon', 'rectangle' ]
			},
			rectangleOptions: polyOptions,
			circleOptions: polyOptions,
			polygonOptions: polyOptions,
			map: map
		} );
		
		google.maps.event.addListener( drawingManager, 'overlaycomplete', function ( e ) {
				//~ if (e.type != google.maps.drawing.OverlayType.MARKER) {
				var isNotMarker = ( e.type != google.maps.drawing.OverlayType.MARKER );
				// Switch back to non-drawing mode after drawing a shape.
				drawingManager.setDrawingMode( null );
				// Add an event listener that selects the newly-drawn shape when the user
				// mouses down on it.
				var newShape = e.overlay;
				newShape.type = e.type;
				google.maps.event.addListener( newShape, 'click', function () {
					setSelection( newShape, isNotMarker );
				} );
				google.maps.event.addListener( newShape, 'drag', function () {
					updateCurSelText( newShape );
				} );
				google.maps.event.addListener( newShape, 'dragend', function () {
					updateCurSelText( newShape );
				} );
				setSelection( newShape, isNotMarker );
				//~ }// end if
			} );
		// The marker, positioned at Uluru
	}
	
	function setSelection( shape, isNotMarker ) {
			clearSelection();
			selectedShape = shape;
			if ( isNotMarker )
				shape.setEditable( true );
			selectColor( shape.get( 'fillColor' ) || shape.get( 'strokeColor' ) );
			updateCurSelText( shape );
		}
</script>