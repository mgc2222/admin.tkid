<?php 
class AbstractEnum
{
	protected static $class = '';
	
	public static function GetName($value, &$trans, $transPrefix)
	{
		$class = new ReflectionClass(static::$class);
		$constants = array_flip($class->getConstants());
		$key = $constants[$value];
		return isset($trans[$transPrefix.$key])?$trans[$transPrefix.$key]:$key;
	}
	
	public static function GetValue($name)
	{
		$class = new ReflectionClass(static::$class);
		return $class->getConstant($name);
	}
	
	public static function GetDataForDropdown(&$trans, $transPrefix) {
		$class = new ReflectionClass(static::$class);
		$constants = array_flip($class->getConstants());
		
		$rows = array();
		foreach ($constants as $key => $val) {
			$row = new stdClass();
			$row->key = $key;
			$row->val = $val;
			array_push($rows, $row);
		}
		return $rows;
	}
}