<html>
	<head>
		<title>Laravel</title>
		
		<link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

		<style>
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-weight: 100;
				font-family: 'Lato';
			}

			.container {
				text-align: center;
				display: table-cell;
				vertical-align: middle;
			}

			.content {
				text-align: center;
				display: inline-block;
			}

			.title {
				font-size: 70px;
				margin-bottom: 0.2em;
				margin-top: 0.8em;
			}

			.quote {
				font-size: 24px;
			}
			#map,
			#map-canvas {
			     width:100%;
			     height:100%;
		     }
		     
		     .overlay {
		          background-color:white;
		          opacity: 0.5;
		          position: absolute;
		          top: 0px;
		          left: 0px;
		          width: 100%;
		          height: 100%;
		     }
		     .innerlay {
		          position: absolute;
		          left: 31%;
		          top: 30%;
		          z-index: 1000;
	          }
	          .searchtext {
	               font-size: 40px;
	          }
	          .searchbtn {
                background-color: #337ab7;
                border: none;
                padding: 1em;
                border-radius: 0.5em;
            }
		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	</head>
	<body>
		<div class="container">
			<div id="map">
                <div id="map-canvas"></div>
            </div>
            <div class="overlay">&nbsp;</div>
            <div class="innerlay">
                <div class="title">
                    Search
                </div>
                <input type="text" class="searchtext" />
                <input type="submit" value="go" class="searchbtn" />
            </div>
		</div>
		
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{$_ENV['MAPS_KEY']}}"></script>
        <script type="text/javascript">
            var map;
            
            function initialize() {
                bindHandlers();
                navigator.geolocation.getCurrentPosition(function GetLocation(data) {
                    makeMap(data.coords);
                });
            };

            function makeMap(coords) {
                var ll = {lat: coords.latitude, lng: coords.longitude};
                var mapOptions = {
                    center: ll,
                    zoom: 14
                };
                
                map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            }

            function bindHandlers() {
                $(".searchbtn").click(function() {
                    var params = {
                        name: $('.searchtext').val()
                    };
                    
                    $.post('/search', params, function(data) {
                        addMarker(data);
                        $(".overlay").remove();
                        $(".innerlay").remove();
                    });
                });
            }

            function addMarker(data) {
                console.log(data);
                console.log(data.device);
                console.log(data.device.lat);
                var ll = {
                        lat: parseFloat(data.device.lat), 
                        lng: parseFloat(data.device.lng)
                    };
                    var marker = new google.maps.Marker({
                        position: ll,
                        map: map,
                    });

                    google.maps.event.addListener(marker, 'click', function(event) {
                    //    infowindow.open(globals.map, marker);
                    });
            }

            google.maps.event.addDomListener(window, 'load', initialize);
        </script>
	</body>
</html>