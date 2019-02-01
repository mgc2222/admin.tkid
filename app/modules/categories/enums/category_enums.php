<?php
class CategoryStatus extends AbstractEnum
{
	// status : 0 - not active; 1 active; 
	protected static $class = __CLASS__; // this must be added
	const Active = 1;
	const Inactive = 0;
	
}

class CategoryDisplayStatus extends AbstractEnum
{
	// status : 0 - not active; 1 active; 
	protected static $class = __CLASS__; // this must be added
	const Separated = 1;
	const United = 0;
	
}

?>