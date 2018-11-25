function GoogleMapsWrapper()
{
	var map = null;
	var options;
	var mapOptions;
	
	var geocoder = null;
	var mapTooltip = null;
	
	var pathPicsPrefix = '';

	// for admin
	var lastmarker = null;

	// search support
	var autocomplete = null;
	var autocompleteInfowindow = null;
	var autocompleteMarker = null;

	this.SearchAddress = function(val, callback) { return codeAddress(val, callback); }
	
	this.Init = function(extendedMapOptions, extOptions) 
	{
		options = getDefaultOptions();
		$.extend(options, extOptions);

		mapOptions = extendedMapOptions;
		
		createMapTooltip();
	}
	
	function getMapDefaultOptions()
	{
		var stylesArray = [
			{
				featureType: 'poi',
				elementType: 'labels',
				stylers: [
					{ visibility: "off" }
				]
			}
		];
		
		var zoomControlStyle = 2; // google.maps.ZoomControlStyle.LARGE;
		
		var ret = { zoom: getZoomLevel(options.currentPage), 
			center: [45.71445023320951, 24.24165916338097],
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			streetViewControl:false,
			mapTypeControl:true,
			navigationControl:true,
			scaleControl:true,
			maxZoom:19,
			overviewMapControl:false,		
			scrollwheel:true,
			zoomControlOptions: {style:zoomControlStyle}, 
			panControl:true,
			styles: stylesArray
		};
		
		return ret;
	}
	
	function getDefaultOptions()
	{
		var ret = { pathMarkers: 'style/front/' };
		return ret;
	}
	
	this.showMapTextBeforeLoad = function()
	{
		document.getElementById(options.mapCanvasId).innerHTML = "Map loading...";
	}

	// callback function, called by google api after loading the api
	this.loadMap = function ()
	{
		defaultMapOptions = getMapDefaultOptions();
		$.extend(defaultMapOptions, mapOptions);
		
		map = new google.maps.Map(document.getElementById(options.mapCanvasId), mapOptions);
		initMapEvents();
		mapLoaded(options.currentPage);
	}


	this.ShowSearchResult = function()
	{
		showSearchResult();
	}

	function showSearchResult()
	{
		autocompleteInfowindow.close();
		var place = autocomplete.getPlace();
		
		var failedId = 'map_search_failed';
		var failedElement = document.getElementById(failedId);
		if (failedElement != null)
		{
			if (place == null || place.id == null)
			{
				document.getElementById(failedId).style.display = '';
				return;
			}
			else document.getElementById(failedId).style.display = 'none';
		}
		
		if (place.geometry.viewport) 
		{
			map.fitBounds(place.geometry.viewport);
		} 
		else 
		{
			map.setCenter(place.geometry.location);
			map.setZoom(15);  // Why 15? Because it looks good.
		}

		var image = new google.maps.MarkerImage(
			place.icon,
			new google.maps.Size(71, 71),
			new google.maps.Point(0, 0),
			new google.maps.Point(17, 34),
			new google.maps.Size(35, 35)
		);
	
		autocompleteMarker.setIcon(image);
		autocompleteMarker.setPosition(place.geometry.location);

		var address = '';
		if (place.address_components) 
		{
			address = [(place.address_components[0] &&
			place.address_components[0].short_name || ''),
				(place.address_components[1] &&
				place.address_components[1].short_name || ''),
				(place.address_components[2] &&
				place.address_components[2].short_name || '')
			].join(' ');
		}
	}

	function createMapTooltip()
	{
		mapTooltip = document.createElement("div");
		if (document.getElementById(options.mapHolderId) != null)
		{
			document.getElementById(options.mapHolderId).appendChild(mapTooltip);
			mapTooltip.className = 'marker_tooltip';
		}
	}

	function mapLoaded(pageId)
	{
		switch (pageId)
		{
			case 'admin_hotel': 
				mapLoadedAdmin(); 
				initGeocoder();
			break;
		}

	}

	function getZoomLevel(pageId)
	{
		switch (pageId)
		{
			case 'admin_hotel': zoomLevel = 10; break;
		}
		return zoomLevel;
	}

	function mapLoadedAdmin()
	{
		var selectedPoint = new google.maps.LatLng(options.centerLat, options.centerLong);
		// lastMarker = createMarker(selectedPoint);
		setCoordinates(selectedPoint);
		
		map.setCenter(selectedPoint);
		map.setZoom(16); // display map with highlighted hotel in center	
	}

	function setCoordinates(point)
	{
		var marker = new google.maps.Marker({  position: point, map: map });
		if (lastmarker)
			lastmarker.setMap(null); // remove marker
		lastmarker = marker;

		document.getElementById("hidLatitude").value = point.lat();
		document.getElementById("hidLongitude").value = point.lng();
	}

	// ====== This function displays the tooltip ======
	function showTooltip(marker, tipMaxWidth) {
		mapTooltip.innerHTML = marker.tooltip;
	   
		var scale = Math.pow(2, map.getZoom());
		var nw = new google.maps.LatLng(map.getBounds().getNorthEast().lat(), map.getBounds().getSouthWest().lng());
		var worldCoordinateNW = map.getProjection().fromLatLngToPoint(nw);
		var worldCoordinate = map.getProjection().fromLatLngToPoint(marker.getPosition());
		// get pixel offset relative to the map 
		var pixelOffset = new google.maps.Point(
			Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale),
			Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale)
		);
		var tipHeight = mapTooltip.clientHeight;
		var tipWidth = mapTooltip.clientWidth;
		var mapCanvas = document.getElementById(options.mapCanvasId);
		var mapSize = new google.maps.Size(mapCanvas.clientWidth, mapCanvas.clientHeight);
		var iconSize = marker.getIcon().size;
		
		if (tipWidth > tipMaxWidth) tipWidth = tipMaxWidth; // fix for first time, before calculating tip width
		
		// if tooltip goes over right margin, display it in the left side of the cursor, otherwise in the right side
		if (pixelOffset.x + tipWidth > mapSize.width)
			tipX = pixelOffset.x - iconSize.width - tipWidth;
		else
			tipX = pixelOffset.x + iconSize.width;
			
		var docH = $(window).height();
		var mapOffestTop = $('#'+options.mapHolderId).offset().top;
		
		// if tooltip doesnt get over the bottom, display the tooltip below the cursor, else display it above the cursor
		if (pixelOffset.y < tipHeight && mapOffestTop + pixelOffset.y + tipHeight < docH)
			tipY = pixelOffset.y;
		else
			tipY = pixelOffset.y - tipHeight - iconSize.height;
		
		mapTooltip.style.left = tipX + 'px';
		mapTooltip.style.top = tipY + 'px';
		mapTooltip.style.visibility = "visible";
	}


	function hideTooltip()
	{
		mapTooltip.style.visibility="hidden";
	}

	function codeAddress(address, callback) 
	{
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) 
			{
				map.setCenter(results[0].geometry.location);
				map.setZoom(17);
				setCoordinates(results[0].geometry.location);
				if (callback != null)
					callback(true);
			} 
			else 
			{
				if (callback != null)
					callback(false);
			}
		});
	}
  
	function initGeocoder()
	{
		geocoder = new google.maps.Geocoder();
	}
	
	function initMapEvents()
	{
		google.maps.event.addListener(map,"click", function(event) {
			setCoordinates(event.latLng);
		});
	}
	
	function createMarker(point)
	{
		var iconImage = options.pathMarkers + 'marker-hotel-blue.png';
		var iconSize = new google.maps.Size(17, 20);
		var icon = new google.maps.MarkerImage(iconImage, iconSize, new google.maps.Point(0, 0),  new google.maps.Point(9, 34));
		
		var iconShadow = options.pathMarkers + 'marker_shadow50.png';
		var shadowSize = new google.maps.Size(37, 34);
		var shadow = new google.maps.MarkerImage(iconShadow, shadowSize, new google.maps.Point(0, 0), new google.maps.Point(9, 34));
		
		var marker = new google.maps.Marker({
		  position: point,
		  map: map,
		  icon:icon,
		  shadow: shadow
		});		
		
		return marker;
	}
}