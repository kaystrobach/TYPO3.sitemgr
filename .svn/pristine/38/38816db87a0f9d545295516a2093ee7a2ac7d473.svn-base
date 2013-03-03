<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

/***************************************************************
*  Copyright notice
*  
*  (c) 2008-2009 Kay Strobach (typo3@kay-strobach.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is 
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
* 
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license 
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/** 
 * This class holds some functions used by the TYPO3 backend 
 * 
 *
 * @Kay Strobach <typo3@kay-strobach.de>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 */
 
class Tx_Sitemgr_Utilities_CustomerModuleUtilities {
	/**
	 * stores module registry
	 */
	static private $registry = array();
	/**
	 * registers the module
	 * 
	 * @param $moduleName string class name of the module
	 */
	static function registerModule($moduleName, $class = NULL, $position = NULL) {
		if($class === NULL) {
			$class = $moduleName;
		}
		self::$registry[$moduleName] = array(
			'name'     => $moduleName,
			'class'    => $class,
			'position' => $position
		);
	}
	static function getRegistry() {
		return self::$registry;
	}
	static function getRegistryOrdered() {
			// Init Vars
		$positionedArray = array();
		$fieldList       = '';
			// Iterate over array and push all non positioned elements
		foreach(self::$registry as $module) {
			if($module['position'] === NULL) {
				$fieldList.= $module['name'] . ',';
			} else {
				$module['tries'] = 0;
				$positionedArray[] = $module;
			}
		}
			//position elements
		while(count($positionedArray) !== 0) {
			$module = array_shift($positionedArray);
			list($position, $modPosName) = explode(':', $module['position']);
			if(strpos($fieldList, $modPosName)) {
				switch ($position) {
					case 'after':
						$fieldList = str_replace($modPosName, $modPosName . ',' . $module['name'], $fieldList);
					break;
					case 'before':
						$fieldList = str_replace($modPosName, $module['name'] . ',' . $modPosName, $fieldList);
					break;
					case 'replace':
						$fieldList = str_replace($modPosName, $module['name'], $fieldList);
					break;
					default:
						$fieldList.= $module['name'] . ',';
					break;
				}
			} elseif($module['tries'] < 4) {
				$module['tries']++;
				$positionedArray[] = $module;
			} else {
				$fieldList.= $module['name'] . ',';
			}
		}
			// build array for output
		$keys = explode(',', $fieldList);
		$outputArray = array();
		foreach($keys as $key) {
			if(self::isModuleRegistered($key)) {
				$outputArray[$key] = self::$registry[$key];
			}
		}
		//t3lib_div::debug($outputArray);
		return $outputArray;
	}
	static function isModuleRegistered($module) {
		if(array_key_exists($module, self::$registry)) {
			return true;
		}
		return false;
	}
	static function getModuleConfig($module) {
		if(self::isModuleRegistered($module)) {
			return self::$registry[$module];
		} else {
			return NULL;
		}
	}
}