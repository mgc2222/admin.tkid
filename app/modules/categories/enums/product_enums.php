<?php
class ProductStatus extends AbstractEnum
{
	// status : 0 - not active; 1 active; 
	protected static $class = __CLASS__; // this must be added
	const Active = 1;
	const Inactive = 0;
	
}

?>