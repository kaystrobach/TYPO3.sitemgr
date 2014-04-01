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

	class Tx_Sitemgr_Utilities_CustomerUtilities {
		var $customerId = null;
		var $customer   = array();
		var $cache      = array();
		private $throwException = false;
		/**
		 * @param int $customerId
		 */
		function __construct($customerId=null) {
			$this->customerId = $customerId;
			if($this->customerId !==null) {
				try {
					$this->init();
				} catch(Exception $e) {
					// just hope for manual init ;)
				}
			}
		}
		/**
		 * @throws Exception
		 * @return array
		 */
		function init() {
			if($this->customerId === null) {
				throw new Exception ('no customer id set');
			} else {
				return $this->customer = t3lib_BEfunc::getRecord('tx_sitemgr_customer',$this->customerId);
			}
		}
		function enableExceptions() {
			$this->throwException = true;
		}
		function disableExceptions() {
			$this->throwException = false;
		}
		/**
		 * @throws Exception
		 * @param int $pageId
		 * @return int
		 */
		function getCustomerForPage($pageId) {
			if($this->customerId != null) {
				return $this->customerId;
			}
			$sys_page = t3lib_div::makeInstance("t3lib_pageSelect");
			$rootLine = $sys_page->getRootLine($pageId);
			$i=count($rootLine)-1;
			$uid = null;
			do {
				$erg = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'*',
					'tx_sitemgr_customer',
					'pid='.intval($rootLine[$i]['uid'])
				);
				if($GLOBALS['TYPO3_DB']->sql_num_rows($erg)==1) {
					$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($erg);
					$uid = $row['uid'];
					$this->customer = $row;
				}
    			$i--;
			} while ($uid == null && isset($rootLine[$i]));
			if($uid === null || $uid === 0) {
				throw new Exception('no customer found, object is not valid ...');
			}
			$this->customerId = $uid;
			return $uid;
		}
		function getCustomerForUser($uid) {
			$main_be_user = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'*',
				'tx_sitemgr_customer',
				'deleted = 0 AND '.$GLOBALS['TYPO3_DB']->listQuery('main_be_user',$uid,'tx_sitemgr_customer')
			);
			$admin_be_users = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'*',
				'tx_sitemgr_customer',
				'deleted = 0 AND '.$GLOBALS['TYPO3_DB']->listQuery('admin_be_users',$uid,'tx_sitemgr_customer')
			);
			$normal_be_users = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'*',
				'tx_sitemgr_customer',
				'deleted = 0 AND '.$GLOBALS['TYPO3_DB']->listQuery('normal_be_users',$uid,'tx_sitemgr_customer')
			);
			$customers = array();
			foreach($main_be_user as $customer) {
				$customers[$customer['uid']] = $customer;
			}
			foreach($admin_be_users as $customer) {
				$customers[$customer['uid']] = $customer;
			}
			foreach($normal_be_users as $customer) {
				$customers[$customer['uid']] = $customer;
			}
			return $customers;
		}
		function getCustomerForUserAsString($uid) {
			$buffer=array();
			foreach($this->getCustomerForUser($uid) as $customer) {
				$buffer[] = $customer['title'];
			} 
			return implode(',',$buffer);
		}
		function getPage() {
			$this->init();
			return $this->customer['pid'];
		}
		function getRootPage() {
			return $this->getPage();
		}
		function getName() {
			if($this->customerId == null) {
				throw new Exception('No Customer ID set, please call getCustomerPerPage before ...');
			}
			$customerId = $this->customerId;
			$erg = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'title',
					'tx_sitemgr_customer',
					'uid='.intval($customerId).' AND deleted=0'
				);
			if($GLOBALS['TYPO3_DB']->sql_num_rows($erg)!=1) {
				throw new Exception('0 or more than 1 customer found');
			}
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($erg);
			
			return $row['title']; 
		}
		function getFolder() {
			if(t3lib_div::isAllowedAbsPath($GLOBALS['TYPO3_CONF_VARS']['BE']['userHomePath'])) {
				return $GLOBALS['TYPO3_CONF_VARS']['BE']['userHomePath'].$this->getMainUserUid().'/';
			} else {
				throw new Exception('userHomePath is not allowed in localconf.php');
			}
		}
		//----------------------------------------------------------------------
		function getMainUserUid() {
			return $this->customer['main_be_user'];
		}
		function getMainUser() {
			$users[] = $this->getMainUserUid();
			return $this->getCompleteUserData($users,'getMainUser');
		}
		function getMainUserName() {
			$user = $this->getMainUser();
			$user = current($user);
			return $user['username'];
		}
		//----------------------------------------------------------------------
		function getAdminUsersUids() {
			$adminUsers[] = $this->getMainUserUid();
			$adminUsers   = array_merge($adminUsers,explode(',',$this->customer['admin_be_users']));
			return $this->cleanArray($adminUsers); 
		}
		function getAdminUsers() {
			$users = $this->getAdminUsersUids();
			return $this->getCompleteUserData($users,'getAdminUsers');
		}
		//----------------------------------------------------------------------
		function getNormalUsersUids() {
			$users = explode(',',$this->customer['normal_be_users']);
			return $this->cleanArray($users);
		}
		function getNormalUsers() {
			$users = $this->getNormalUsersUids();
			return $this->getCompleteUserData($users,'getNormalUsers');
		}
		//----------------------------------------------------------------------
		function getAllUsersUids() {
			$users = array_merge($this->getAdminUsersUids(),$this->getNormalUsersUids());
			return $this->cleanArray($users);
		}
		function getAllUsers() {
			$users = $this->getAllUsersUids();
			return $this->getCompleteUserData($users,'getAllUsers');
		}

		//----------------------------------------------------------------------
		function getGroups() {
			return $this->customer['be_groups'];
			#$groups = explode(',',$this->customer['be_groups']);
			#return $groups;
		}
		//----------------------------------------------------------------------
		protected function getCompleteUserData($uidsArray, $cacheParam) {
			if(isset($this->cache['user->'.$cacheParam])) {
				return $this->cache['user->'.$cacheParam];
			}
			if(count($uidsArray)>0) {
				$beFunc=t3lib_div::makeInstance('t3lib_BEfunc');
				$where = '';
				foreach($uidsArray as $uid) {
					$where.=' OR uid = '.intval($uid);
				}
				//remove trailing or)
				$where = substr($where, 3);
				//add and for full condition...
				$where = 'AND ('.$where.')';
				#$content = $where.$content;
				$users=$beFunc->getUserNames('realName,username,usergroup,usergroup_cached_list,uid,disable,admin,email',$where);
				$this->cache['user->'.$cacheParam] = $users;
				return $this->cache['user->'.$cacheParam]; 
			} else {
				return array();
			}
		}
		//----------------------------------------------------------------------
		protected function cleanArray($array) {
			$return = array();
			foreach($array as $entry) {
				if($entry) $return[] = $entry;
			}
			return $return;
		} 
		//----------------------------------------------------------------------
		function isCustomerUser($uid) {
			if(in_array($uid,$this->getAllUsersUids())) {
				return true;
			} else {
				return false;
			}
		}
		function isAllowedToModifyUser($uid) {
			//do not edit your self
			if($GLOBALS['BE_USER']->user['uid'] == $uid) {
				return false;
			}
			//global admin?
			if($GLOBALS['BE_USER']->isAdmin()) {
				return true;
			}
			//is customer admin and uid in getAllUsersUid
			#print_r($this->getAdminUsersUids());
			#print_r($this->$this->getAllUsersUids());
			#die();
			if(in_array($GLOBALS['BE_USER']->user['uid'],$this->getAdminUsersUids())) {
				if(in_array($uid,$this->getAllUsersUids())) {
					return true;
				}
			}
			return false;
		}
		/**
		 * @throws Exception
		 * @param integer|null $beUserId
		 * @return bool
		 */
		function isAdministratorForCustomer($beUserId = null) {
			if($beUserId === null) {
				$beUserId = $GLOBALS['BE_USER']->user['uid'];
				if($GLOBALS['BE_USER']->isAdmin()) {
					return true;
				}
			} else {
				$t = t3lib_BEfunc::getRecord('be_users',$beUserId);
				if(is_array($t)) {
					if($t['admin'] === 1) {
						return true;
					}
				}
			}
			if(in_array($beUserId,$this->getAdminUsersUids())) {
				return true;
			} else {
				if($this->throwException) {
					throw new Exception('Access Denied');
				}
				return false;
			}
		}
		//----------------------------------------------------------------------
		function addUserById($uid) {
			if($this->customerId == null) {
				throw new Exception('No Customer ID set, please call getCustomerPerPage before ...');
			}
			if($this->customer['normal_be_users']=='' || $this->customer['normal_be_users']=='NULL') {
				$this->customer['normal_be_users'] = $uid;
			} else {
				$this->customer['normal_be_users'].= ','.$uid;
			}
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
					'tx_sitemgr_customer',
					'uid = '.intval($this->customerId),
					$this->customer
			);
		}
	}
?>
