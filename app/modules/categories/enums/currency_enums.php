<?php
class Currency extends AbstractEnum
{
	// typeId : 0 - no agreement (deleted); 1 perma agreement; 2 temp agreement; 10 - virtual agreement from mail; 11 - virtual agreement from radar;
	protected static $class = __CLASS__; // this must be added
	const RON = 0;
	const EURO = 1;
	const USD = 2;
}
?>