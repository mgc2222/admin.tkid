<?php
class Attributes extends AbstractEnum
{
	// typeId : 0 - no agreement (deleted); 1 perma agreement; 2 temp agreement; 10 - virtual agreement from mail; 11 - virtual agreement from radar;
	protected static $class = __CLASS__; // this must be added
	const Color = 1;
	const Size = 2;
}
?>