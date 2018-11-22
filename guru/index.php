<?php
session_start();
require( '../koneksi.php' );
$link = koneksi_db();
if ( isset( $_SESSION[ 's_nuptk' ] ) ) {
	if ( $_SESSION[ 's_kode_jabatan' ] != 'BKS' && $_SESSION[ 's_kode_jabatan' ] != 'KSK' ) {
		require( 'guru_header.php' );
		?>
		<style>
			.chat_box {
				position: fixed;
				right: 20px;
				bottom: 0px;
				width: 250px;
			}
			
			.chat_body {
				background: white;
				height: 400px;
				padding: 5px 0px;
				overflow: scroll;
				overflow-x: hidden;
				display: none;
			}
			
			.chat_head,
			.msg_head {
				background: #f39c12;
				color: white;
				padding: 15px;
				font-weight: bold;
				cursor: pointer;
				border-radius: 5px 5px 0px 0px;
			}
			
			.msg_box {
				position: fixed;
				bottom: -5px;
				width: 250px;
				background: white;
				border-radius: 5px 5px 0px 0px;
				display: none;
			}
			
			.msg_head {
				background: #3498db;
			}
			
			.msg_body {
				background: white;
				height: 200px;
				font-size: 12px;
				padding: 15px;
				overflow: auto;
				overflow-x: hidden;
			}
			
			.msg_input {
				width: 100%;
				border: 1px solid white;
				border-top: 1px solid #DDDDDD;
				-webkit-box-sizing: border-box;
				/* Safari/Chrome, other WebKit */
				-moz-box-sizing: border-box;
				/* Firefox, other Gecko */
				box-sizing: border-box;
			}
			
			.close {
				float: right;
				cursor: pointer;
			}
			
			.minimize {
				float: right;
				cursor: pointer;
				padding-right: 5px;
			}
			
			.user_head_orangtua {
				position: relative;
				padding: 10px 10px;
			}
			
			.user_head_orangtua:hover {
				background: #f8f8f8;
				cursor: pointer;
			}
			
			.user_head_orangtua:before {
				position: absolute;
				background: #2ecc71;
				height: 10px;
				width: 10px;
				left: 10px;
				top: 15px;
				border-radius: 6px;
			}
			
			.user_head_guru {
				position: relative;
				padding: 10px 10px;
			}
			
			.user_head_guru:hover {
				background: #f8f8f8;
				cursor: pointer;
			}
			
			.user_head_guru:before {
				position: absolute;
				background: #2ecc71;
				height: 10px;
				width: 10px;
				left: 10px;
				top: 15px;
				border-radius: 6px;
			}
			
			.user {
				position: relative;
				padding: 10px 30px;
			}
			
			.user:hover {
				background: #f8f8f8;
				cursor: pointer;
			}
			
			.user:before {
				content: '';
				position: absolute;
				background: #2ecc71;
				height: 10px;
				width: 10px;
				left: 10px;
				top: 15px;
				border-radius: 6px;
			}
			
			.msg_a {
				position: relative;
				background: #FDE4CE;
				padding: 10px;
				min-height: 10px;
				margin-bottom: 5px;
				margin-right: 10px;
				border-radius: 5px;
			}
			
			.msg_a:before {
				content: "";
				position: absolute;
				width: 0px;
				height: 0px;
				border: 10px solid;
				border-color: transparent #FDE4CE transparent transparent;
				left: -20px;
				top: 7px;
			}
			
			.msg_b {
				background: #EEF2E7;
				padding: 10px;
				min-height: 15px;
				margin-bottom: 5px;
				position: relative;
				margin-left: 10px;
				border-radius: 5px;
				word-wrap: break-word;
			}
			
			.msg_b:after {
				content: "";
				position: absolute;
				width: 0px;
				height: 0px;
				border: 10px solid;
				border-color: transparent transparent transparent #EEF2E7;
				right: -20px;
				top: 7px;
			}
			
			.customMarker {
				position: absolute;
				cursor: pointer;
				background: #000000;
				width: 100px;
				height: 100px;
				/* -width/2 */
				margin-left: -50px;
				/* -height + arrow */
				margin-top: -110px;
				border-radius: 50%;
				padding: 0px;
			}
			
			.customMarker:after {
				content: "";
				position: absolute;
				bottom: -10px;
				left: 40px;
				border-width: 10px 10px 0;
				border-style: solid;
				border-color: #000000 transparent;
				display: block;
				width: 0;
			}
			
			.customMarker img {
				width: 90px;
				height: 90px;
				margin: 5px;
				border-radius: 50%;
			}
			
			.customMarkerGuru {
				position: absolute;
				cursor: pointer;
				background: #3AFF00;
				width: 100px;
				height: 100px;
				/* -width/2 */
				margin-left: -50px;
				/* -height + arrow */
				margin-top: -110px;
				border-radius: 50%;
				padding: 0px;
			}
			
			.customMarkerGuru:after {
				content: "";
				position: absolute;
				bottom: -10px;
				left: 40px;
				border-width: 10px 10px 0;
				border-style: solid;
				border-color: #3AFF00 transparent;
				display: block;
				width: 0;
			}
			
			.customMarkerGuru img {
				width: 90px;
				height: 90px;
				margin: 5px;
				border-radius: 50%;
			}
			
			.modal-dialog,
			.modal-content {
				/* 80% of window height */
				height: 95%;
			}
			
			.modal-body {
				/* 100% = dialog height, 120px = header + footer */
				max-height: calc(100% - 135px);
				overflow-y: scroll;
			}
		</style>
		<section id="container">
			<!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
			<!--header start-->
			<script>
				function load_notifikasi() {
					var jumlah_notif;
					var jumlah_pesan;
					$.ajax( {
						type: 'post',
						url: 'ajax_angka_notif.php',
						dataType: "json",
						success: function ( data_angka_notif ) {
							$( '.angka-notif' ).html( data_angka_notif.jumlah );
						}
					} );

					$.ajax( {
						type: 'post',
						url: 'ajax_angka_pesan.php',
						dataType: "json",
						success: function ( data_angka_pesan ) {
							$( '.angka-pesan' ).html( data_angka_pesan.jumlah );
						}
					} );

					$.ajax( {
						type: 'post',
						url: 'ajax_angka_notif.php',
						async: false,
						dataType: "json",
						success: function ( data_angka_notif ) {
							jumlah_notif = data_angka_notif.jumlah;
						}
					} );

					$.ajax( {
						type: 'post',
						url: 'ajax_angka_pesan.php',
						async: false,
						dataType: "json",
						success: function ( data_angka_pesan ) {
							jumlah_pesan = data_angka_pesan.jumlah;
						}
					} );

					$.ajax( {
						type: 'post',
						url: 'ajax_tampil_notif.php',
						data: {
							jumlah_notif: jumlah_notif
						},
						success: function ( data ) {
							$( '.tampil_notif' ).html( data );
						}
					} );

					$.ajax( {
						type: 'post',
						url: 'ajax_tampil_pesan.php',
						data: {
							jumlah_pesan: jumlah_pesan
						},
						success: function ( data ) {
							$( '.tampil_pesan' ).html( data );
						}
					} );
				}

				function telah_dibaca() {
					$.ajax( {
						type: 'post',
						url: 'ajax_telah_dibaca.php',
						success: function ( data ) {

						}
					} );
					load_notifikasi();
					return false;
				}

				function pesan_dibaca( id ) {
					$.ajax( {
						type: 'post',
						url: 'ajax_dibaca.php',
						data: {
							id: id
						},
						success: function ( data ) {

						}
					} );
					load_notifikasi();
				}
				load_notifikasi();
			</script>
			<header class="header black-bg">
				<div class="sidebar-toggle-box">
					<div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
				</div>
				<!--logo start-->
				<a href="index.php" class="logo"><b>GU<span>RU</span></b></a>
				<!--logo end-->
				<div class="nav notify-row" id="top_menu">
					<!--  notification start -->
					<ul class="nav top-menu">

						<li id="header_inbox_bar" class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle" href="index.html#"><i class="fa fa-bell-o"></i><span class="badge bg-theme angka-notif"></span></a>
							<ul class="dropdown-menu extended inbox tampil_notif">
							</ul>
						</li>

						<li id="header_inbox_bar" class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle" href="index.html#"><i class="fa fa-envelope-o"></i><span class="badge bg-theme angka-pesan"></span></a>
							<ul class="dropdown-menu extended inbox tampil_pesan">

							</ul>
						</li>

					</ul>

					<!--  notification end -->
				</div>


				<div class="top-menu">
					<ul class="nav pull-right top-menu">
						<li>
							<a class="logout" href="../logout.php">Logout</a>
						</li>
					</ul>
				</div>
			</header>

			<div class="modal fade" id="konfirmasi" tabindex="-1" role="dialog" aria-labelledby="Konfimasi" aria-hidden="true">
				<div class="modal-dialog" role="document">

					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<center>
								<h4 class="modal-title">Konfirmasi</h4>
							</center>
						</div>
						<div class="modal-body">
							<div class="detail-konfirmasi"></div>
						</div>
						<div class="modal-footer">
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" id="konfirmasi_pilih" tabindex="-1" role="dialog" aria-labelledby="Konfimasi" aria-hidden="true">
				<div class="modal-dialog" role="document">

					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<center>
								<h4 class="modal-title">Konfirmasi</h4>
							</center>
						</div>
						<div class="modal-body">
							<div class="detail-konfirmasi-pilih"></div>
						</div>

					</div>
				</div>
			</div>

			<div class="modal fade" id="detail_siswa" tabindex="-1" role="dialog" aria-labelledby="detail" aria-hidden="true">
				<div class="modal-dialog" role="document">

					<!-- Modal content-->
					<div class="modal-content detail-siswa">
					</div>
				</div>
			</div>

			<script>
				$( document ).ready( function () {
					$( '#konfirmasi' ).on( 'show.bs.modal', function ( e ) {
						//harus tetap id, jika tidak akan data tak akan terambil
						//menggunakan fungsi ajax untuk pengambilan data
						$.ajax( {
							type: 'post',
							url: 'ajax_modal_konfirmasi.php',
							success: function ( data ) {
								$( '.detail-konfirmasi' ).html( data ); //menampilkan data ke dalam modal
								load_notifikasi();
							}
						} );
					} );
				} );

				$( document ).ready( function () {
					$( '#konfirmasi_pilih' ).on( 'show.bs.modal', function ( e ) {
						//harus tetap id, jika tidak akan data tak akan terambil
						//menggunakan fungsi ajax untuk pengambilan data
						var idx = $( e.relatedTarget ).data( 'id' );
						var id_status = $( e.relatedTarget ).data( 'status' );
						$.ajax( {
							type: 'post',
							url: 'ajax_pilih_notifikasi.php',
							data: {
								idNotifikasi: idx,
								idStatus: id_status
							},
							success: function ( data ) {
								$( '.detail-konfirmasi-pilih' ).html( data ); //menampilkan data ke dalam modal
								load_notifikasi();
							}
						} );
					} );
				} );

				$( document ).on( 'click', '.konfirmasi-ya', function () {
					//menampilkan jumlah status 1
					var idx = $( this ).data( 'id' );
					var waktu = $( this ).data( 'waktu' );
					var nis = $( this ).data( 'nis' );
					var pesan = $( this ).data( 'pesan' );
					$.ajax( {
						type: 'post',
						url: 'ajax_konfirmasi_ya.php',
						data: {
							idNotifikasi: idx,
							waktu: waktu,
							nis: nis,
							pesan: pesan
						},
						success: function ( data ) {
							load_konfirmasi();
						}
					} );
				} );

				$( document ).on( 'click', '.konfirmasi_pilih', function () {
					//menampilkan jumlah status 1
					var idx = $( this ).data( 'id' );
					var waktu = $( this ).data( 'waktu' );
					var nis = $( this ).data( 'nis' );
					var pesan = $( this ).data( 'pesan' );
					$.ajax( {
						type: 'post',
						url: 'ajax_konfirmasi_ya.php',
						data: {
							idNotifikasi: idx,
							waktu: waktu,
							nis: nis,
							pesan: pesan
						},
						success: function ( data ) {
							load_konfirmasi();
						}
					} );
				} );

				$( document ).on( 'click', '.konfirmasi-tidak', function () {
					//menampilkan jumlah status 1
					var idx = $( this ).data( 'id' );
					$.ajax( {
						type: 'post',
						url: 'ajax_konfirmasi_tidak.php',
						data: {
							idNotifikasi: idx,
						},
						success: function ( data ) {
							load_konfirmasi();
						}
					} );
				} );

				$( document ).on( 'click', '.ketemu', function () {
					//menampilkan jumlah status 1
					var waktu = $( this ).data( 'waktu' );
					var lat = $( this ).data( 'lat' );
					var longitude = $( this ).data( 'longitude' );
					$.ajax( {
						type: 'post',
						url: 'ajax_konfirmasi_ketemu.php',
						data: {
							waktu: waktu,
							lat: lat,
							longitude: longitude
						},
						success: function ( data ) {
							load_konfirmasi();
						}
					} );
				} );

				function load_konfirmasi() {
					$.ajax( {
						type: 'post',
						url: 'ajax_modal_konfirmasi.php',
						success: function ( data ) {
							$( '.detail-konfirmasi' ).html( data ); //menampilkan data ke dalam modal
						}
					} );
				}

				$( document ).ready( function () {
					$( '#detail_siswa' ).on( 'show.bs.modal', function ( e ) {
						var idx = $( e.relatedTarget ).data( 'id' );
						var latitude = $( e.relatedTarget ).data( 'latitude' );
						var longitude = $( e.relatedTarget ).data( 'longitude' );
						//harus tetap id, jika tidak akan data tak akan terambil
						//menggunakan fungsi ajax untuk pengambilan data
						$.ajax( {
							type: 'post',
							url: 'ajax_detail_siswa.php',
							data: {
								nis: idx,
							},
							success: function ( data ) {
								$( '.detail-siswa' ).html( data ); //menampilkan data ke dalam modal
								geocodeLatLng( geocoder, latitude, longitude );
							}
						} );
					} );
				} );

				function geocodeLatLng( geocoder, lat, lng ) {
					var latlng = {
						lat: parseFloat( lat ),
						lng: parseFloat( lng )
					};
					geocoder.geocode( {
						'location': latlng
					}, function ( results, status ) {
						if ( status === 'OK' ) {
							if ( results[ 0 ] ) {
								document.getElementById( 'lokasi_terakhir' ).innerHTML = results[ 0 ].formatted_address;
							} else {
								document.getElementById( 'lokasi_terakhir' ).innerHTML = 'Lokasi Tidak Ditemukan';
							}
						} else {
							document.getElementById( 'lokasi_terakhir' ).innerHTML = 'Lokasi Tidak Ditemukan';
						}
					} );
				}
			</script>
			<!--header end-->
			<!-- **********************************************************************************************************************************************************
        MAIN SIDEBAR MENU
        *********************************************************************************************************************************************************** -->
			<!--sidebar start-->
			<aside>
				<div id="sidebar" class="nav-collapse ">
					<!-- sidebar menu start-->
					<ul class="sidebar-menu baterai" id="nav-accordion">
					</ul>
					<!-- sidebar menu end-->
				</div>
			</aside>

			<script>
				var refresh_baterai = function () {
					$.ajax( {
						type: 'post',
						url: 'ajax_baterai.php',
						success: function ( data ) {
							$( '.baterai' ).html( data );
						}
					} );
				}
				refresh_baterai();
				setInterval( refresh_baterai, 30000 );
			</script>
			<!--sidebar end-->
			<!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
			<!--main content start-->
			<section id="main-content">
				<section class="wrapper">
					<div class="row">
						<div class="col-sm-12">
							<section class="panel">
								<header class="panel-heading">
									<div class="col-md-12 col-xs-12 col-sm-12">
										<select id="cari_siswa" name="cari_murid" class="form-control">
											<option value="">Cari Siswa...</option>
											<?php
											$sql_cari_siswa = "SELECT `nis`,`nama` FROM `tb_siswa` WHERE `status` != 1";
											$result_cari_siswa = mysqli_query( $link, $sql_cari_siswa );
											while ( $data_cari_siswa = mysqli_fetch_array( $result_cari_siswa ) ) {
												?>
											<option value="<?php echo $data_cari_siswa['nis']; ?>">
												<?php echo $data_cari_siswa['nama']; ?>
											</option>
											<?php
											}
											?>
										</select>
									</div>
									<br>
								</header>
								<div class="panel-body">
									<div id="map"></div>
									<audio id="audiotag1" src="../audio/chess-chess1.mp3" preload="auto"></audio>
								</div>
							</section>
						</div>
					</div>
					<!-- page end-->
				</section>
				<!-- /wrapper -->
			</section>
			<!--main content end-->
			<!--footer start-->

			<!--footer end-->
			<script type="text/javascript">
				var map = null;
				var infoWindow = null;
				var geocoder = null;
				var geofencing_polygon = [];
				var markersArray = [];
				var guruMarkerArray = [];

				$( document ).ready( function () {

					$( "#cari_siswa" ).select2( {
						placeholder: "Cari Siswa"
					} );
					$( "#cari_siswa" ).on( "select2:select", function ( e ) {
						var selected_element = $( e.currentTarget );
						var select_val = selected_element.val();
						$.ajax( {
							type: 'post',
							url: 'ajax_lat_lng_siswa.php',
							data: {
								nis: select_val
							},
							dataType: "json",
							success: function ( data ) {
								var myLatlng = new google.maps.LatLng( data.lat, data.lng );
								if ( myLatlng == "(0, 0)" ) {
									alert( data.nama + " tidak ada di map!!" );
								} else {
									map.panTo( myLatlng )
								}


							}
						} );
					} );

				} );



				google.setOnLoadCallback( initMap );

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

					geocoder = new google.maps.Geocoder;
					infoWindow = new google.maps.InfoWindow;

					$.ajax( {
						type: 'post',
						url: 'ajax_geofencing.php',
						async: false,
						dataType: "json",
						success: function ( data_geofencing ) {
							var jml_geofencing = data_geofencing.length;

							if ( jml_geofencing != 0 ) {
								for ( var i = 0; i < jml_geofencing; i++ ) {
									$.ajax( {
										type: 'post',
										url: 'ajax_koordinat.php',
										async: false,
										data: {
											id_geofencing: data_geofencing[ i ][ "id_geofencing" ]
										},
										dataType: "json",
										success: function ( data_koordinat ) {
											var jmlKoordinat = data_koordinat.length;

											var triangleCoords = [];
											for ( var j = 0; j < jmlKoordinat; j++ ) {
												triangleCoords.push( {
													lat: parseFloat( data_koordinat[ j ][ "latitude" ] ),
													lng: parseFloat( data_koordinat[ j ][ "longitude" ] )
												} );
											}
											geofencing_polygon[ i ] = new google.maps.Polygon( {
												paths: triangleCoords,
												strokeColor: '#FF0000',
												strokeOpacity: 0.8,
												strokeWeight: 3,
												fillColor: '#FF0000',
												fillOpacity: 0.35,
											} );

											geofencing_polygon[ i ].setMap( map );

										}
									} );
								}


							}
						}
					} );
					updateMaps();
					window.setInterval( updateMaps, 10000 );
				}

				function CustomMarker( latlng, map, imageSrc ) {
					this.latlng_ = latlng;
					this.imageSrc = imageSrc;

					// Once the LatLng and text are set, add the overlay to the map.  This will
					// trigger a call to panes_changed which should in turn call draw.
					this.setMap( map );
				}

				CustomMarker.prototype = new google.maps.OverlayView();

				CustomMarker.prototype.draw = function () {
					// Check if the div has been created.
					var div = this.div_;
					if ( !div ) {
						// Create a overlay text DIV
						div = this.div_ = document.createElement( 'div' );
						// Create the DIV representing our CustomMarker
						div.className = "customMarker"


						var img = document.createElement( "img" );
						img.src = this.imageSrc;
						div.appendChild( img );
						var me = this;
						google.maps.event.addDomListener( div, "click", function ( event ) {
							google.maps.event.trigger( me, "click" );
						} );

						// Then add the overlay to the DOM
						var panes = this.getPanes();
						panes.overlayImage.appendChild( div );
					}

					// Position the overlay 
					var point = this.getProjection().fromLatLngToDivPixel( this.latlng_ );
					if ( point ) {
						div.style.left = point.x + 'px';
						div.style.top = point.y + 'px';
					}
				};

				CustomMarker.prototype.remove = function () {
					// Check if the overlay was on the map and needs to be removed.
					if ( this.div_ ) {
						this.div_.parentNode.removeChild( this.div_ );
						this.div_ = null;
					}
				};

				CustomMarker.prototype.getPosition = function () {
					return this.latlng_;
				};

				function CustomMarkerGuru( latlng, map, imageSrc ) {
					this.latlng_ = latlng;
					this.imageSrc = imageSrc;

					// Once the LatLng and text are set, add the overlay to the map.  This will
					// trigger a call to panes_changed which should in turn call draw.
					this.setMap( map );
				}

				CustomMarkerGuru.prototype = new google.maps.OverlayView();

				CustomMarkerGuru.prototype.draw = function () {
					// Check if the div has been created.
					//			alert(this.div_);
					var div = this.div_;

					if ( !div ) {
						// Create a overlay text DIV
						div = this.div_ = document.createElement( 'div' );
						// Create the DIV representing our CustomMarker
						div.className = "customMarkerGuru"


						var img = document.createElement( "img" );

						img.src = this.imageSrc;
						div.appendChild( img );
						google.maps.event.addDomListener( div, "click", function ( event ) {
							google.maps.event.trigger( me, "click" );
						} );


						// Then add the overlay to the DOM
						var panes = this.getPanes();
						panes.overlayImage.appendChild( div );
					}

					// Position the overlay 
					var point = this.getProjection().fromLatLngToDivPixel( this.latlng_ );
					if ( point ) {
						div.style.left = point.x + 'px';
						div.style.top = point.y + 'px';
					}
				};

				CustomMarkerGuru.prototype.remove = function () {
					// Check if the overlay was on the map and needs to be removed.
					//					this.div_.parentNode.removeChild( this.div_ );
					//					this.div_ = null;
					if ( this.div_ ) {
						this.div_.parentNode.removeChild( this.div_ );
						this.div_ = null;
					}
				};

				CustomMarkerGuru.prototype.getPosition = function () {
					return this.latlng_;
				};

				function clearOverlays() {
					for ( var i = 0; i < markersArray.length; i++ ) {
						markersArray[ i ].setMap( null );
					}

					for ( var i = 0; i < guruMarkerArray.length; i++ ) {
						guruMarkerArray[ i ].setMap( null );
					}
				}

				function menampilkan_posisi_guru( position ) {
					var data = 'data_guru.php?lat=' + position.coords.latitude + '&longitude=' + position.coords.longitude;

					//Me guardo o direito a não explicar o óbvio, novamente
					$.get( data, {}, function ( data ) {
						$( data ).find( "marker" ).each(
							function () {
								var marker = $( this );

								var myLatLngGuru = new CustomMarkerGuru( new google.maps.LatLng( parseFloat( marker.attr( "lat" ) ), parseFloat( marker.attr( "lng" ) ) ), map, "../foto/guru/" + marker.attr( "foto" ) );


								markersArray.push( myLatLngGuru );
							} );
					} );
				}

				function notifikasiPopup() {
					if ( Notification.permission !== "granted" ) {
						Notification.requestPermission();
					} else {
						$.ajax( {
							type: 'post',
							url: 'ajax_popup.php',
							dataType: "json",
							success: function ( data ) {
								var data_notif = data.notif;
								var notifikasi = [];
								for ( var i = data_notif.length - 1; i >= 0; i-- ) {
									notifikasi[ i ] = new Notification( data_notif[ i ][ 'nama' ], {
										icon: "../foto/guru/" + data_notif[ i ][ 'foto' ],
										body: data_notif[ i ][ 'pesan_notif' ],
									} );
									document.getElementById( 'audiotag1' ).play();
									setTimeout( function () {
										notifikasi[ i ].close();
									}, 5000 );
								};
							}
						} );
					}
				}

				function notifikasi_sms_gateway() {
					$.ajax( {
						type: 'post',
						url: 'ajax_ambil_guru_tanpa_smartphone.php',
						aasync: false,
						dataType: "json",
						success: function ( data_nuptk ) {
							var jml_nuptk = data_nuptk.length;
							for ( var k = 0; k < jml_nuptk; k++ ) {

								$.ajax( {
									type: 'post',
									url: 'ajax_ambil_id_notifikasi.php',
									async: false,
									data: {
										nuptk: data_nuptk[ k ][ "nuptk" ]
									},
									dataType: "json",
									success: function ( data_id_notifikasi ) {
										var jml_id_notifikasi = data_id_notifikasi.length;

										for ( var l = 0; l < jml_id_notifikasi; l++ ) {

											$.ajax( {
												type: 'post',
												url: 'ajax_kirim_sms.php',
												data: {
													nuptk: data_nuptk[ k ][ "nuptk" ],
													id_notifikasi: data_id_notifikasi[ l ][ "id_notifikasi" ],
													no_hp: data_nuptk[ k ][ "no_hp" ],
													lokasi: data_id_notifikasi[ l ][ "alamat" ],
													nama_siswa: data_id_notifikasi[ l ][ "nama" ],
													pesan: data_id_notifikasi[ l ][ "pesan_notif" ],
													kelas: data_id_notifikasi[ l ][ "kelas" ],
													tingkatan: data_id_notifikasi[ l ][ "tingkatan" ]

												},
												dataType: "json",
												success: function ( data ) {

												}
											} );
										}
									}
								} );
							}
						}
					} );
				}

				function load_notification() {
					var jumlah_notif;
					notifikasiPopup();

					$.ajax( {
						type: 'post',
						url: 'ajax_angka_notif.php',
						dataType: "json",
						success: function ( data_angka_notif ) {
							$( '.angka-notif' ).html( data_angka_notif.jumlah );
						}
					} );

					$.ajax( {
						type: 'post',
						url: 'ajax_angka_notif.php',
						async: false,
						dataType: "json",
						success: function ( data_angka_notif ) {
							jumlah_notif = data_angka_notif.jumlah;
						}
					} );

					$.ajax( {
						type: 'post',
						url: 'ajax_tampil_notif.php',
						data: {
							jumlah_notif: jumlah_notif
						},
						success: function ( data ) {
							$( '.tampil_notif' ).html( data );
						}
					} );
				}



				var i = 0;

				function updateMaps() {
					load_notification();
					notifikasi_sms_gateway();

					var cek_jarak_guru_siswa;
					if ( i != 0 ) {
						clearOverlays();
						i = i + 1;
					} else if ( i == 0 ) {
						i = i + 1;
					}

					navigator.geolocation.getCurrentPosition( menampilkan_posisi_guru );

					var data = 'data_siswa.php';

					var jml_polygon = geofencing_polygon.length;
					//Me guardo o direito a não explicar o óbvio, novamente
					$.get( data, {}, function ( data ) {
						$( data ).find( "marker" ).each(
							function () {
								var marker = $( this );

								var ubah_status = "tetap";

								var lat_lng = new google.maps.LatLng( parseFloat( marker.attr( "lat" ) ), parseFloat( marker.attr( "lng" ) ) );

								var overlay = new CustomMarker( lat_lng, map, "../foto/siswa/" + marker.attr( "foto" ) );

								markersArray.push( overlay );

								var myLatlng = new google.maps.LatLng( marker.attr( "lat" ), marker.attr( "lng" ) );


								for ( var q = 0; q < jml_polygon; q++ ) {
									var hasil = google.maps.geometry.poly.containsLocation( myLatlng, geofencing_polygon[ q ] ) ? "didalam" : "diluar";

									if ( hasil == "didalam" ) {
										break;
									}
								}

								var cek_jarak_guru_siswa = 1;

								$.ajax( {
									type: 'post',
									url: 'ajax_posisi_guru.php',
									async: false,
									dataType: "json",
									success: function ( data_posisi_guru ) {
										var jml_posisi_guru = data_posisi_guru.length;

										for ( var j = 0; j < jml_posisi_guru; j++ ) {

											var posisi_guru = new google.maps.LatLng( data_posisi_guru[ j ][ "latitude" ], data_posisi_guru[ j ][ "longitude" ] );

											var jarak = Math.round( google.maps.geometry.spherical.computeDistanceBetween( myLatlng, posisi_guru ) );

											if ( jarak < 10 ) {
												cek_jarak_guru_siswa = 0;
											}
										}
									}
								} );


								if ( marker.attr( "status" ) == 0 ) {
									if ( marker.attr( "cek_jadwal" ) == "yes" ) {
										if ( hasil == "diluar" ) {
											if ( cek_jarak_guru_siswa == 1 ) {
												if ( marker.attr( "baterai" ) <= 15 ) {
													ubah_status = "0 jadi 4";
												} else if ( marker.attr( "baterai" ) > 15 ) {
													ubah_status = "0 jadi 2";
												}
											} else if ( cek_jarak_guru_siswa == 0 ) {
												if ( marker.attr( "baterai" ) <= 15 ) {
													ubah_status = "0 jadi 3";
												}
											}

										} else if ( hasil == "didalam" ) {
											if ( marker.attr( "baterai" ) <= 15 ) {
												ubah_status = "0 jadi 3";
											}
										}
									} else if ( marker.attr( "cek_jadwal" ) == "no" ) {
										ubah_status = "0 jadi 5";
									}
								} else if ( marker.attr( "status" ) == 2 ) {
									if ( marker.attr( "cek_jadwal" ) == "yes" ) {
										if ( hasil == "diluar" ) {
											if ( cek_jarak_guru_siswa == 1 ) {
												if ( marker.attr( "baterai" ) <= 15 ) {
													ubah_status = "2 jadi 4";
												}
											} else if ( cek_jarak_guru_siswa == 0 ) {
												if ( marker.attr( "baterai" ) <= 15 ) {
													ubah_status = "2 jadi 3";
												} else if ( marker.attr( "baterai" ) > 15 ) {
													ubah_status = "2 jadi 0";
												}
											}
										} else if ( hasil == "didalam" ) {
											if ( marker.attr( "baterai" ) <= 15 ) {
												ubah_status = "2 jadi 3";
											} else if ( marker.attr( "baterai" ) > 15 ) {
												ubah_status = "2 jadi 0";
											}
										}
									}
								} else if ( marker.attr( "status" ) == 3 ) {
									if ( marker.attr( "cek_jadwal" ) == "yes" ) {
										if ( hasil == "diluar" ) {
											if ( cek_jarak_guru_siswa == 1 ) {
												if ( marker.attr( "baterai" ) <= 15 ) {
													ubah_status = "3 jadi 4";
												} else if ( marker.attr( "baterai" ) > 15 ) {
													ubah_status = "3 jadi 2";
												}
											} else if ( cek_jarak_guru_siswa == 0 ) {
												if ( marker.attr( "baterai" ) > 15 ) {
													ubah_status = "3 jadi 0";
												}
											}
										} else if ( hasil == "didalam" ) {
											if ( marker.attr( "baterai" ) > 15 ) {
												ubah_status = "3 jadi 0";
											}
										}
									} else if ( marker.attr( "cek_jadwal" ) == "no" ) {
										ubah_status = "3 jadi 5";
									}
								} else if ( marker.attr( "status" ) == 4 ) {
									if ( marker.attr( "cek_jadwal" ) == "yes" ) {
										if ( hasil == "diluar" ) {
											if ( cek_jarak_guru_siswa == 1 ) {
												if ( marker.attr( "baterai" ) > 15 ) {
													ubah_status = "4 jadi 2";
												}
											} else if ( cek_jarak_guru_siswa == 0 ) {
												if ( marker.attr( "baterai" ) <= 15 ) {
													ubah_status = "4 jadi 3";
												} else if ( marker.attr( "baterai" ) > 15 ) {
													ubah_status = "4 jadi 0";
												}
											}
										} else if ( hasil == "didalam" ) {
											if ( marker.attr( "baterai" ) <= 15 ) {
												ubah_status = "4 jadi 3";
											} else if ( marker.attr( "baterai" ) > 15 ) {
												ubah_status = "4 jadi 0";
											}
										}
									}
								} else if ( marker.attr( "status" ) == 5 ) {
									if ( marker.attr( "cek_jadwal" ) == "yes" ) {
										if ( hasil == "didalam" ) {
											if ( marker.attr( "baterai" ) <= 15 ) {
												ubah_status = "5 jadi 3";
											} else if ( marker.attr( "baterai" ) > 15 ) {
												ubah_status = "5 jadi 0";
											}
										}
									}
								}

								if ( ubah_status != "tetap" ) {

									$.ajax( {
										type: 'post',
										url: 'ajax_update_status.php',
										async: false,
										data: {
											nis: marker.attr( "nis" ),
											kelas: marker.attr( "kelas" ),
											nama: marker.attr( "nama" ),
											perintah: ubah_status
										},
										success: function ( data ) {
											load_notification();
										}
									} );
								}
							} );
					} );

				}
			</script>
			<?php
			$query_ambil_data_guru = "SELECT `nuptk`,`nama`,foto FROM `tb_guru` where nuptk!='" . $_SESSION[ 's_nuptk' ] . "'";
			$result_ambil_data_guru = mysqli_query( $link, $query_ambil_data_guru );

			$query_ambil_data_orangtua = "SELECT `id_orangtua`,`nama`,foto FROM `tb_orangtua`";
			$result_ambil_data_orangtua = mysqli_query( $link, $query_ambil_data_orangtua );

			?>
			<div class="chat_box">
				<div class="chat_head">CHAT</div>
				<div class="chat_body">
					<div class="user_head_guru"><strong><span class="fa fa-caret-square-o-down"></span>&nbsp;&nbsp;&nbsp;Guru</strong>
					</div>
					<div class="user_guru">
						<?php
						while ( $data_guru = mysqli_fetch_array( $result_ambil_data_guru ) ) {
							?>
						<div class="user" data-tipe="guru" data-id="<?php echo $data_guru['nuptk']; ?>" data-nama="<?php echo $data_guru['nama']; ?>" data-foto="<?php echo $data_guru['foto']; ?>">
							<?php echo $data_guru['nama']; ?>
						</div>
						<?php
						}
						?>
					</div>
					<div class="user_head_orangtua"><strong><span class="fa fa-caret-square-o-down"></span>&nbsp;&nbsp;&nbsp;Orangtua</strong>
					</div>
					<div class="user_orangtua">
						<?php
						while ( $data_orangtua = mysqli_fetch_array( $result_ambil_data_orangtua ) ) {
							?>
						<div class="user" data-tipe="orangtua" data-id="<?php echo $data_orangtua['id_orangtua']; ?>" data-nama="<?php echo $data_orangtua['nama']; ?>" data-foto="<?php echo $data_orangtua['foto']; ?>">
							<?php echo $data_orangtua['nama']; ?>
						</div>
						<?php
						}
						?>
					</div>
				</div>
			</div>

			<script>
				var total_popups = 0;
				//arrays of popups ids
				var popups = [];

				$( document ).on( 'click', '.user', function ( e ) {
					//menampilkan jumlah status 1
					var idx = $( this ).data( 'id' );
					var nama = $( this ).data( 'nama' );
					var foto = $( this ).data( 'foto' );
					var tipe = $( this ).data( 'tipe' );
					register_popup( idx, nama, foto, tipe );
				} );

				$( document ).on( 'click', '.msg_head', function ( e ) {
					var idx = $( this ).data( 'id' );
					$( '#' + idx + ' .msg_wrap' ).slideToggle( 'slow' );
				} );

				$( document ).on( 'click', '.chat_head', function ( e ) {
					$( '.chat_body' ).slideToggle( 'slow' );
				} );

				$( document ).on( 'click', '.user_head_guru', function ( e ) {
					$( '.user_guru' ).slideToggle( 'slow' );
					var icon = this.querySelector( 'span' );

					if ( icon.classList.contains( 'fa-caret-square-o-down' ) ) {
						icon.classList.remove( 'fa-caret-square-o-down' );
						icon.classList.add( 'fa-caret-square-o-right' );
					} else {
						icon.classList.remove( 'fa-caret-square-o-right' );
						icon.classList.add( 'fa-caret-square-o-down' );
					}
				} );

				$( document ).on( 'click', '.user_head_orangtua', function ( e ) {
					$( '.user_orangtua' ).slideToggle( 'slow' );
					var icon = this.querySelector( 'span' );

					if ( icon.classList.contains( 'fa-caret-square-o-down' ) ) {
						icon.classList.remove( 'fa-caret-square-o-down' );
						icon.classList.add( 'fa-caret-square-o-right' );
					} else {
						icon.classList.remove( 'fa-caret-square-o-right' );
						icon.classList.add( 'fa-caret-square-o-down' );
					}
				} );

				$( document ).on( 'keydown', '.msg_input', function ( e ) {
					var idx = $( this ).data( 'id' );
					if ( e.keyCode == 13 ) {
						e.preventDefault();
						var msg = $( this ).val();
						$( this ).val( '' );
						$.ajax( {
							type: 'post',
							url: 'ajax_kirim_pesan.php',
							data: {
								pesan: msg,
								id: idx
							},
							dataType: "json",
							success: function ( data ) {
								refreshChat( idx );
							}
						} );

					}

				} );

				function refreshChat( id ) {
					$.ajax( {
						type: 'post',
						url: 'ajax_refresh_pesan.php',
						data: {
							id: id
						},
						success: function ( data ) {
							$( '.msg_body_' + id ).html( data );
						}
					} );
				}

				Array.remove = function ( array, from, to ) {
					var rest = array.slice( ( to || from ) + 1 || array.length );
					array.length = from < 0 ? array.length + from : from;
					return array.push.apply( array, rest );
				};

				function close_popup( id ) {
					for ( var iii = 0; iii < popups.length; iii++ ) {

						if ( id == popups[ iii ] ) {
							Array.remove( popups, iii );
							document.getElementById( id ).style.display = "none";

							calculate_popups();

							return;
						}
					}
				}

				function register_popup( id, name, foto, tipe ) {
					var foto_baru;

					for ( var iii = 0; iii < popups.length; iii++ ) {
						//already registered. Bring it to front.

						if ( id == popups[ iii ] ) {
							//membuang popup
							Array.remove( popups, iii );
							//menambahkan popup ke array
							popups.unshift( id );

							calculate_popups();
							return;
						}
					}

					if ( tipe == 'guru' ) {
						foto_baru = '../foto/guru/' + foto;
					} else {
						foto_baru = '../foto/orangtua/' + foto;
					}

					var element = '<div class="msg_box" id="' + id + '">';
					element = element + '<div class="msg_head" data-id="' + id + '"><img src="' + foto_baru + '" class="img-circle" width="40" height="35"></img>&nbsp;&nbsp;&nbsp;' + name;
					element = element + '<div class="close"><a href="javascript:close_popup(\'' + id + '\');"><span class="fa fa-times"></span></a></div></div>';
					element = element + '<div class="msg_wrap">';
					element = element + '<div class="msg_body msg_body_' + id + '">';

					$.ajax( {
						type: 'post',
						url: 'ajax_ambil_pesan.php',
						async: false,
						data: {
							id: id
						},
						dataType: "json",
						success: function ( data ) {
							var jumlah_pesan = data.length;
							for ( var i = 0; i < jumlah_pesan; i++ ) {

								if ( id == data[ i ][ "id_pengirim" ] ) {
									element = element + '<div class="msg_a">' + data[ i ][ "isi_pesan" ] + '</div>';
								} else {
									element = element + '<div class="msg_b">' + data[ i ][ "isi_pesan" ] + '</div>';
								}
							}
						}
					} );

					element = element + '</div>';
					element = element + '<div class="msg_footer"><textarea class="msg_input" rows="4" data-id="' + id + '"></textarea>';
					element = element + '</div>';
					element = element + '</div>';
					element = element + '</div>';

					$( "body" ).append( element );

					popups.unshift( id );

					calculate_popups();
					pesan_dibaca( id );

				}

				function calculate_popups() {
					var width = window.innerWidth;
					if ( width < 540 ) {
						total_popups = 0;
					} else {
						width = width - 200;
						//320 is width of a single popup box
						total_popups = parseInt( width / 320 );
					}
					display_popups();

				}

				function display_popups() {
					var right = 300;

					var iii = 0;
					for ( iii; iii < total_popups; iii++ ) {
						if ( popups[ iii ] != undefined ) {
							var element = document.getElementById( popups[ iii ] );
							element.style.right = right + "px";
							right = right + 280;
							element.style.display = "block";
						}
					}

					for ( var jjj = iii; jjj < popups.length; jjj++ ) {
						var element = document.getElementById( popups[ jjj ] );
						element.style.display = "none";
					}
				}

				window.addEventListener( "resize", calculate_popups );
				window.addEventListener( "load", calculate_popups );
				//				window.setInterval( updateChatPerDetik, 5000 );

				//				function updateChatPerDetik() {
				//					var jumlah_popup = popups.length;
				//					for ( var i = 0; i < jumlah_popup; i++ ) {
				//						refreshChat( popups[ i ] );
				//					}
				//				}
			</script>
			<!--		<div align="center"></div>-->
		</section>

		<?php
		require( 'guru_footer.php' );
	}
} else {
	echo( "<script> location.href ='../index.php';</script>" );
}

?>