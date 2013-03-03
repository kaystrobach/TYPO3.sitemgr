<?php

die('class not ready');

ini_set('display_errors' , 1);
error_reporting(E_ALL);

class Tx_Sitemgr_Utilities_Registry {
	/**
	 * stores module registry
	 */
	private $registry = null;
	/**
	 * @var Tx_Sitemgr_Utilities_Registry
	 */
	protected static $instance = null;

	private function __construct() {
		$this->registry = new stdClass();
	}
	public static function getInstance() {
		if(self::$instance === null) {
			self::$instance = new Tx_Sitemgr_Utilities_Registry();
		}
		return self::$instance;
	}
	public function get($key) {
		$keyArray = explode('.', $key);
		$obj      = $this->registry;
		$r        = $this->recursiveSearch($obj, $keyArray);
			// make sure, its an array
		return json_decode(json_encode($r), true);
	}
	public function set($key, $value) {
		$keyArray = explode('.', $key);
			// make sure, its an object
		$value = json_decode(json_encode($value), false);
			// return value
		$this->recursiveSearch($this->registry, $keyArray, $value);
	}
	private function recursiveSearch(stdClass &$object, $keys, $value =null) {
		$tObj = $object;
		$keys = array_values($keys);
		foreach($keys as $id => $key) {
			if(!property_exists($tObj, $key)) {
				$tObj->$key = new stdClass();
			}
			if($id === (count($keys)-1) && ($value !== null)) {
				$tObj->$key = $value;
			}
			$tObj   = $tObj->$key;
		}
		return $tObj;
	}
}
/*
	Tx_Sitemgr_Utilities_Registry::getInstance()->set('sitemgr.links.0.tab1.12.href', '45');
	Tx_Sitemgr_Utilities_Registry::getInstance()->set('sitemgr.links.0.tab1.15.href', '45');

	print_r(Tx_Sitemgr_Utilities_Registry::getInstance()->get('sitemgr.links'));

	print_r(Tx_Sitemgr_Utilities_Registry::getInstance());

	Tx_Sitemgr_Utilities_Registry::getInstance()->set('sitemgr.links.0.tab1.16', array('test'));

	print_r(Tx_Sitemgr_Utilities_Registry::getInstance());
*/