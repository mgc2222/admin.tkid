function AdminMap()
{
	var currentPage = 'admin_hotel';
	var mapClass = null;
	
	this.Init = function()
	{
		var pathMarkers = 'style/front/';
		var pathPicsPrefix = '';
	
		if (document.getElementById('map_holder') == null) return;
		
		var centerLat = $('#hidLatitude').val();
		var centerLong = $('#hidLongitude').val();
		
		mapClass = new GoogleMapsWrapper();
		var mapOptions = { };
		var options = { mapHolderId:'map_holder', mapCanvasId:'map_canvas', currentPage:currentPage, currentPropertyId:currentPropertyId, pathMarkers:pathMarkers, pathPicsPrefix:pathPicsPrefix, showMapTextBeforeLoad:true, centerLat: centerLat, centerLong:centerLong};
		mapClass.Init(mapOptions, options);
		
		$('#txtAddress').blur(function() {
			searchMap();
		});
		
		$('#txtCity').blur(function() {
			$('#txtAddress').val('');
			$('#hidLatitude').val('');
			$('#hidLongitude').val('');
			searchMap();
		});
	}
	
	function searchMap()
	{
		var countryId = $("#ddlCountry").val();
		var country = $("#ddlCountry option[value='"+countryId+"']").text();
		var city = $('#txtCity').val();
		var address = $('#txtAddress').val();
		var fullAddress = country+' '+city+' '+address;
		var res = mapClass.SearchAddress(fullAddress, function(result) { 
			if (!result)
				BootstrapDialog.alert('Nu a fost gasita locatia introdusa, va rugam sa o selectati dvs.');
		});
	}
	
	this.LoadMap = function()
	{
		if (mapClass == null) return;
		mapClass.loadMap();
	}

	this.TriggerMapSearch = function()
	{
		mapClass.ShowSearchResult();
	}
}

var objAdminMap = new AdminMap();
objAdminMap.Init();

function loadMap() // called by google once map is loaded
{
	objAdminMap.LoadMap();
}