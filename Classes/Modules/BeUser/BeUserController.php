<?php 
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

class Tx_Sitemgr_Modules_BeUser_BeUserController extends Tx_Sitemgr_Modules_Abstract_AbstractController{
	protected $file = __FILE__;
	protected $access = array(
		'general' => 'customerAdmin'
	);
	function __construct() {
		$this->jsFiles = array(
			t3lib_extMgm::extRelPath('sitemgr').'Resources/Public/JavaScripts/Modules/BeUser/beuserStore.js',
		);
	}
	function getModuleJavaScript(&$js,$uid) {
		$js.= $this->getModuleJavaScriptHelper(
			array(
				'title' => 'User',
			),
			$uid
		);
	}
	function getUsers($args) {
		if(($args['args']->filterField === 'username') && ($args['args']->filterValue !== '')) {
			$additionalCondition = ' AND ' . $GLOBALS['TYPO3_DB']->searchQuery(array($args['args']->filterValue), array($args['args']->filterField), 'be_users');
		} else {
			$additionalCondition = '';
		}
		if(($args['args']->filterField === 'customerPid') && ($args['args']->filterValue !== '')) {
			$args['args']->uid = $args['args']->filterValue;
			$additionalCondition = '';
		}
		try{
			$customer = new Tx_Sitemgr_Utilities_CustomerUtilities();
			$customer->getCustomerForPage(intval($args['args']->uid));
			$uids     = $customer->getAllUsersUids();
			$name     = $customer->getName();
			
			$rows  =  array_values($GLOBALS['TYPO3_DB']->exec_SELECTgetRows (
					'uid,username,realname,email,admin',
					'be_users',
					'deleted=0 AND uid IN ('.implode(',', $uids).')' . $additionalCondition,
					'',
					$args['sort'].' '.$args['dir'],
					$args['start'].','.$args['stop']));
			$count =  $GLOBALS['TYPO3_DB']->exec_SELECTgetRows (
					'count(*) as count',
					'be_users',
					'deleted=0 AND uid IN ('.implode(',',$uids).')' . $additionalCondition
					);

			foreach($rows as $k=>$val) {
				$rows[$k]['customerName']=$name;
			}
		} catch(Exception $e) {
			if($GLOBALS['BE_USER']->user['admin']==1) {
				$rows  =  array_values($GLOBALS['TYPO3_DB']->exec_SELECTgetRows (
						'uid,username,realname,email,admin',
						'be_users',
						'deleted=0' . $additionalCondition,
						'',
						$args['sort'].' '.$args['dir'],
						$args['start'].','.$args['stop']));
				$count =  $GLOBALS['TYPO3_DB']->exec_SELECTgetRows (
						'count(*) as count',
						'be_users',
						'deleted=0' . $additionalCondition
						); 
			} else {
				return array(
					'count' => 0
				);
			}
		}
		foreach($rows as $k=>$val) {
			$customer = new Tx_Sitemgr_Utilities_CustomerUtilities();
			$rows[$k]['customerName']=$customer->getCustomerForUserAsString($val['uid']);
		}
		return array(
				'count' => $count[0]['count'],
				'rows'  => array_values($rows),
			);
	}
	function getUser($arg) {
		if($arg->uid == 0) {
			return array(
				'success' => true,
				'data'    => array(
					'uid' => '0',
					'username' => '',
					'password' => '',
					'realName' => '',
					'email'    => '',
				),
			);
		} elseif($GLOBALS['BE_USER']->user['admin']) {
			$user =  array(
				'success' =>true,
				'data'    => t3lib_BEfunc::getRecord(
					'be_users',
					$arg->uid
				),
			);
			$user['data']['password'] = '';
			return $user;
		} else {
			$customer = new Tx_Sitemgr_Utilities_CustomerUtilities($arg->cid);
			$customer->init();
			if($customer->isAllowedToModifyUser($arg->uid)) {
				$user = array(
					'success' =>true,
					'data'    => t3lib_BEfunc::getRecord(
						'be_users',
						$arg->uid
					),
				);
				$user['data']['password'] = '';
				return $user;
			} else {
				if($arg->uid == $GLOBALS['BE_USER']->user['uid']) {
					return array(
						'success' => false,
						'errorMessage' => $GLOBALS['LANG']->getLL('error.accessDeniedToYourself'),
					);
				} else {
					return array(
						'success' => false,
						'errorMessage' => $GLOBALS['LANG']->getLL('error.accessDenied'),
					);
				}
			}
		}
		return array(
			'success' => true,
			'data'    => array(
				'realName' => 'test',
				'username' => 'test'
			),
		);
	}
	/**
	 * delete an existing user
	 *
	 * @param $arg
	 * @return array
	 */
	function deleteUser($arg) {
		list($uid,$cid) = explode(':',$arg);
		$customer = new Tx_Sitemgr_Utilities_CustomerUtilities($cid);
		$customer->init();
		if(!$customer->isAllowedToModifyUser($uid)) {
			return array(
				'success' => false,
				'errorMessage' => 'Access denied uid'.$uid.' cid'.$cid
			);
		} else {
			$erg = $GLOBALS['TYPO3_DB']->exec_DELETEquery(
				'be_users',
				'uid='.intval($uid)
			);
			$erg = $GLOBALS['TYPO3_DB']->exec_DELETEquery(
				'tx_beacl_acl',
				'type=0 AND object_id='.intval($uid)
			);
			return array(
				'success' => false,
				'errorMessage' => 'Success'
			);
		}
		return array('errorMessage' => $arg);
	}
	/**
	 * add or update an new or existing user
	 *
	 * @param $arg
	 * @return array
	 */
	function addOrUpdateUser($arg) {
		//check access
			if($arg['cid']) {
				$customer = new Tx_Sitemgr_Utilities_CustomerUtilities($arg['cid']);
				$customer->init();
				if($arg['uid']) {
					if(!$customer->isAllowedToModifyUser($arg['uid'])) {
						return array(
							'success' => false,
							'errorMessage' => 'Access denied uid'.$arg['uid'].' cid'.$arg['cid']
						);
					}
				}
			} else {
				return array(
					'success' => false,
					'errorMessage' => 'Need to select customer'
				);
			}
		//check duplicates
			$users = t3lib_BEfunc::getRecordsByField('be_users','username',$arg['username']);
			if(count($users)!=0) {
				if($users[0]['uid']!=$arg['uid']) {
					$this->addErrorForForm(
						'username',
						$GLOBALS['LANG']->getLL('error.username.duplicate')
					);
					return $this->getReturnForForm();
				}
			}
		//check wether prefix should be forced
			$extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['ks_sitemgr']);
			if($extConfig['forceBeUserPrefix']){
				if((strlen($arg['username'])<strlen($customer->getName())) && !(substr($arg['username'],0,strlen($customer->getName()))==$customer->getName())) {
					$this->addErrorForForm(
							'username',
							$GLOBALS['LANG']->getLL('error.username.prefixmissing'),
							$customer->getName().'-'.$arg['username']
						);
					return $this->getReturnForForm();
				}
			}
		// create user
			$user = new Tx_Sitemgr_Utilities_CustomerUtilities(null);
			$customer->getCustomerForPage($this->id);
			$dbFields = array(
					'username'       => $arg['username'],
					'realName'       => $arg['realName'],
					'email'          => $arg['email'],
					'password'       => md5($arg['password']),
					'disable'        => $arg['disable'] ? 1 : 0,
					'lang'           => $GLOBALS['BE_USER']->uc['lang'],  // set this user lang as default language for the new user
					'options'        => 2,
					'fileoper_perms' => 15,
			);
			if($arg['password']=='') {
				unset($dbFields['password']);
			}
			if($arg['uid']==0) {
				debug('try to add user');
				//create user
				$customer->init();
				$dbFields['usergroup'] = $customer->getGroups();
				$erg = $GLOBALS['TYPO3_DB']->exec_INSERTquery(
					'be_users',
					$dbFields
				);
				if($arg['password']=='') {
					$this->addErrorForForm(
						'password',
						$GLOBALS['LANG']->getLL('error.password.required')
					);
					return $this->getReturnForForm();
				} else {
					$arg['uid'] = $GLOBALS['TYPO3_DB']->sql_insert_id();
					$customer->addUserById($GLOBALS['TYPO3_DB']->sql_insert_id());
				}
			} else {
				debug('try to update user');
				//update user
				$erg = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
					'be_users',
					'uid='.$arg['uid'],
					$dbFields
				);
			}
			if(trim($GLOBALS['TYPO3_CONF_VARS']['BE']['userHomePath']) !== '') {
				/** @var Tx_Sitemgr_Utilities_FileSystemUtility $fileSystemUtility */
				debug(print_r($dbFields));
				debug(print_r($arg));
				$fileSystemUtility = t3lib_div::makeInstance('Tx_Sitemgr_Utilities_FileSystemUtility');
				$fileSystemUtility->ensureFolderExists($GLOBALS['TYPO3_CONF_VARS']['BE']['userHomePath'].intval($arg['uid']));
			}
		// return form
			return $this->getReturnForForm();
	}
	/**
	 * returns access rights for a user
	 *
	 * @param $uid
	 * @return array
	 */
	function getAccessForUser($uid) {
		$user = t3lib_BEfunc::getRecord(
			'be_users',
			intval($uid)
		); 
		$grants   = t3lib_BEfunc::getRecordsByField(
			'tx_beacl_acl',
			'object_id',
			$uid,
			'AND type="0"',
			'pid'
		);
		$return = array();
		foreach($grants as $grant) {
			$path     = t3lib_BEfunc::getRecordPath(
				$grant['pid'],
				'',
				100
			);
			$return[] = array(
				'username' => $user['username'],
				'path'     => $path,
				'uid'      => $grant['uid'],
				'right'    => 'R/W',
				'pid'      => $grant['pid'],
			);
		}
		return $return;
	}
	/**
	 * root node of a customer admin should not be removed!
	 * @todo
	 * @return array
	 */
	function deleteGrant($args) {
		$customer = new Tx_Sitemgr_Utilities_CustomerUtilities();
		$customer->getCustomerForPage($args->uid);
		if($customer->isAllowedToModifyUser($args->user)) {
				//check uid
			if($GLOBALS['BE_USER']->user['uid'] == $args->user) {
				return array(
					'errorMessage' => 'Access denied',
				);
			}
				//drop acls
			$GLOBALS['TYPO3_DB']->exec_DELETEquery(
				'tx_beacl_acl',
				'pid='.intval($args->pid).' AND object_id='.intval($args->user).' AND type=0'
			);
				//drop mountpoints
			$user = t3lib_BEfunc::getRecord(
				'be_users',
				$args->user
			);
			$user['db_mountpoints'] = t3lib_div::rmFromList($args->pid,$user['db_mountpoints']);
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
				'be_users',
				'uid='.intval($args->user),
				$user
			);
		} else {
			throw new Exception('Permission denied');
		}
	}
	/**
	 * @todo
	 * add check, if pages is into the same customer	 
	 */	 	
	function addGrant($args) {
		$customer = new Tx_Sitemgr_Utilities_CustomerUtilities();
		$customer->getCustomerForPage($args['args']);
		if($customer->isAllowedToModifyUser($args['userID'])) {
				// check selection
			if(intval($args['grantPid'])!=$args['grantPid']) {
				return array(
					'success'      => false,
					'errorMessage' => 'No PID selected'
				);
			}
				//add mountpoint
			$user = t3lib_BEfunc::getRecord(
				'be_users',
				$args['userID']
			);
			$user['db_mountpoints'] = t3lib_div::uniqueList($user['db_mountpoints'].','.intval($args['grantPid']));
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
				'be_users',
				'uid='.intval($args['userID']),
				$user
			);
				//add grants
			$GLOBALS['TYPO3_DB']->exec_INSERTquery(
				'tx_beacl_acl',
				array(
					'pid'         => $args['grantPid'],
					'cruser_id'   => $GLOBALS['BE_USER']->user['uid'],
					'type'        => 0, //user
					'object_id'   => $args['userID'],
					'permissions' => 27,
					'recursive'   => 0
				)
			);
			$GLOBALS['TYPO3_DB']->exec_INSERTquery(
				'tx_beacl_acl',
				array(
					'pid'         => $args['grantPid'],
					'cruser_id'   => $GLOBALS['BE_USER']->user['uid'],
					'type'        => 0, //user
					'object_id'   => $args['userID'],
					'permissions' => 31,
					'recursive'   => 1
				)
			);
		} else {
			return array(
				'success'      => false,
				'errorMessage' => 'Permission denied'
			);
		}
		return array(
			'success' => true
		);
	}
	function getUsersRights($cid) {
		$customer     = new Tx_Sitemgr_Utilities_CustomerUtilities($cid);
		$customer->init();
		$users        = $customer->getAllUsers();
		$this->rights = array();
		foreach($users as $user) {
			$rights = $this->getAccessForUser($user['uid']);
			foreach($rights as $right) {
				$this->rights[$right['pid']][$user['username']] = 1;
			}
		}
		$return       = $this->getPages($customer->getPage(),$customer->getName().'/');
		return array(
			'success' =>true,
			'rows' => array_values($return)
		);
	}
	protected function getPages($uid,$prefix='') {
		$pages  = t3lib_BEfunc::getRecordsByField ('pages', 'pid', $uid);
		$buffer = array();
		foreach($pages as $page) {
			$buffer[$page['uid']] = array(
					'id'  => $page['uid'],
					'title' => $prefix.$page['title'],
				); 
			
			if($this->rights[$page['pid']]) {
				if(!$this->rights[$page['uid']]) {
					$this->rights[$page['uid']] = array();
				}
				$this->rights[$page['uid']] = array_merge(
					$this->rights[$page['pid']],
					$this->rights[$page['uid']]
				);
			}

			if($this->rights[$page['uid']]) {
				$buffer[$page['uid']] = array_merge(
					$buffer[$page['uid']],
					$this->rights[$page['uid']]
				);
			}
			
			
			$buffer = array_merge(
				$buffer,
				$this->getPages(
					$page['uid'],
					$prefix.$page['title'].'/'
				)
			);
			
			/*$buffer = array_merge(
				$buffer,
				$this->getPages(
					$page['uid'],
					$prefix.'&nbsp;&nbsp;'
				)
			);*/
		}
		return $buffer;
	}
}