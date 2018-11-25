// set the sorting variables for a grid column, into a variable named "hidSortColumn_{Table}";
function SortGridColumnClass()
{
	var columnPrefix = 'hidSortColumn_';
	var tableId = 'hidSortTable';
	
	this.SortColumn = function(table, col)
	{
		var currentSortColumn = $('#'+columnPrefix+table).val();
		var sortColumn = '';
		var	sortDir = '';

		if (currentSortColumn.indexOf('|') > 0)
		{
			var arrSort = currentSortColumn.split('|');
			sortColumn = arrSort[0];
			sortDir = arrSort[1];
		}
		
		if (sortColumn == col) // if same column
		{
			// reverse sort
			if (sortDir == 'ASC') sortDir = 'DESC';
			else sortDir = 'ASC';
		}
		else
		{
			sortColumn = col;
			sortDir = 'ASC'
		}
		
		$('#'+columnPrefix+table).val(sortColumn+'|'+sortDir);
		$('#'+tableId).val(table);
	}
}