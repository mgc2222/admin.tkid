function Select2PagingPlugin() {
	var _selector = null;
	var _dataSource = null;
	var _customData = null;
	var _pluginOptions = { pageSize: 50, callback: null};

	this.init = function(selector, dataSource, select2Options, pluginOptions) {
		_dataSource = dataSource;
		_selector = selector;
		_select2Options = select2Options;
		_pluginOptions = $.extend(_pluginOptions, pluginOptions);
	}
	
	this.customData = function() {
		return _customData;
	}
	
	
	$.fn.select2.amd.require(["select2/data/array", "select2/utils"],
		function (ArrayData, Utils) {
			function CustomData($element, options) {
				CustomData.__super__.constructor.call(this, $element, options);
			}
			Utils.Extend(CustomData, ArrayData);

			CustomData.prototype.query = function (params, callback) {
				if (!params.page) {
					params.page = 1;
				}
				var results = null;
				if (params.term == undefined) {
					results = _dataSource;
				}
				else {
					results = _.filter(_dataSource, function(item) { 
						return (item.text.toLowerCase().indexOf(params.term.toLowerCase()) >= 0);
					}); 
				}
				
				var data = {};
				data.results = results.slice((params.page - 1) * _pluginOptions.pageSize, params.page * _pluginOptions.pageSize);
				data.pagination = {};
				data.pagination.more = params.page * _pluginOptions.pageSize < _dataSource.length;
				callback(data);
			};

			var select2Options = $.extend( { ajax: {}, dataAdapter: CustomData }, _select2Options )
			$(document).ready(function () {
				$(_selector).select2(select2Options);
				_customData = CustomData;
				if (_pluginOptions.callback)
					_pluginOptions.callback();
			});
		});
	
}