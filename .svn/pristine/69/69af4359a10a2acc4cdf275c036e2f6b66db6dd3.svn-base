<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
#require_once(t3lib_extMgm::extPath('sitemgr').'lib/class.tx_ks_sitemgr_tab.php');
#require_once(t3lib_extMgm::extPath('sitemgr').'lib/class.tx_ks_sitemgr_customer.php');
#require_once(t3lib_extMgm::extPath('sitemgr').'lib/class.tx_ks_sitemgr_div.php');


class Tx_Sitemgr_Controller_ExtDirectDispatcherController{
	public function test() {
		return 'test';
	}
	public function dispatch($module,$function,$args) {
		try {
			$this->initializeClassLoader();
			if (Tx_Sitemgr_Utilities_CustomerModuleUtilities::isModuleRegistered($module)) {
				$_classRef = Tx_Sitemgr_Utilities_CustomerModuleUtilities::getModuleConfig($module);
				$_classRef = $_classRef['class'];
				$_procObj   = new $_classRef();
				if(method_exists($_procObj, $function)) {
					return $_procObj->$function($args);
				} else {
					//unknown action');
					throw new Exception('Unknown function '.$function.' in module'.$module);
				}
			} else {
				throw new Exception('Unknown module '.$module);
			}
		} catch(Exception $e) {
			return array(
				'success' => false,
				'errorMessage' => $e->getMessage()
			);
		}
	}
	/**
	 * special function to allow paged display of grids
	 */	 	
	public function dispatchPaged($module, $function, $args, $start, $stop, $sort, $dir) {
		$args = array(
			'args'   => $args,
			'start'  => intval($start),
			'stop'   => intval($stop),
			'sort'   => $sort,
			'dir'    => $dir == 'DESC' ? 'DESC' : 'ASC'
		);
		return $this->dispatch($module,$function,$args);
	}
	/**
	 * handles the form
	 *	 	
	 * @formHandler
	 */	 	
	public function handleForm($arg) {
		return $this->dispatch($arg['module'],$arg['fn'],$arg);
	}
	public function getSubpages($uid=0) {
		$pages  = t3lib_BEfunc::getRecordsByField ('pages', 'pid', $uid);
		$buffer = array();
		foreach($pages as $page) {
			$buffer[]=array(
				'id'  => $page['uid'],
				'text' => $page['title'],
			);
		}
		return array_values($buffer);
	}
	/**
	 * Initializes the autoload mechanism of Extbase. This is supplement to the core autoloader.
	 *
	 * copied from EXT:extbase/Classes/Core/Bootstrap.php
	 *	 	 
	 * @return void
	 * @see initialize()
	 */
	protected function initializeClassLoader() {
		if (!class_exists('Tx_Extbase_Utility_ClassLoader', FALSE)) {
			include(t3lib_extmgm::extPath('extbase') . 'Classes/Utility/ClassLoader.php');
		}

		$classLoader = new Tx_Extbase_Utility_ClassLoader();
		spl_autoload_register(array($classLoader, 'loadClass'));
	}
}