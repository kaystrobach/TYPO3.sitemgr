<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}


class Tx_Sitemgr_Modules_Customer_CustomerController extends Tx_Sitemgr_Modules_Abstract_AbstractController{
	protected $file = __FILE__;
	function __construct() {
		$this->jsFiles = array(
			t3lib_extMgm::extRelPath('sitemgr').'Resources/Public/JavaScripts/Modules/Customer/customerStore.js',
		);
	}
	function getModuleJavaScript(&$js,$uid) {
		$extConfig       = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sitemgr']);
		$customerPidPage = $GLOBALS["BE_USER"]->getTSConfig(
		  	'mod.web_txsitemgrM1.customerPidPage',
			t3lib_BEfunc::getPagesTSconfig($uid)
		);
		if((!$extConfig['customerPidPageTS']) || ($extConfig['customerPidPageTS'] && $uid == $customerPidPage['value'] && $uid!=0))
		$js.= $this->getModuleJavaScriptHelper(
			array(),
			$uid
		);
		$tgroup = $GLOBALS["BE_USER"]->getTSConfig(
			  	'mod.web_txsitemgr.customer.createUser.group',
				t3lib_BEfunc::getPagesTSconfig($arg['uid'])
			);
	}
	function getCustomers($args) {
		if(intval($args['start'])<0) {
			$args['start'] = 0;
		}
		if(intval($args['stop'])<1) {
			$args['stop'] = 1;
		}
		if(($args['dir'] !== 'ASC') && ($args['dir']!== 'DESC')) {
			$args['dir'] = 'ASC';
		}
		if(($args['sort'] !== 'title') && ($args['sort']!== 'uid') && ($args['sort']!== 'pid')) {
			$args['sort'] = 'title';
		}
		if(($args['args']->filterField === 'title') && ($args['args']->filterValue !== '')) {
			$additionalCondition = ' AND ' . $GLOBALS['TYPO3_DB']->searchQuery(array($args['args']->filterValue), array($args['args']->filterField), 'tx_sitemgr_customer');
		} else {
			$additionalCondition = '';
		}
		$rows  =  array_values($GLOBALS['TYPO3_DB']->exec_SELECTgetRows (
				'uid,pid,title',
				'tx_sitemgr_customer',
				'deleted=0' . $additionalCondition,
				'',
				$args['sort'].' '.$args['dir'],
				intval($args['start']).','.intval($args['stop'])));
		$count =  $GLOBALS['TYPO3_DB']->exec_SELECTgetRows (
				'count(*) as count',
				'tx_sitemgr_customer',
				'deleted=0' . $additionalCondition
				);
		foreach($rows as $i=>$row) {
			try {
				$customer = new Tx_Sitemgr_Utilities_CustomerUtilities($rows[$i]['uid']);
				$customer->init();
				$users = array();
				foreach($customer->getAllUsers() as $user) {
					$users[] = $user['username'];
				}
				$rows[$i]['users'] = implode(', ',$users);
			} catch(Exception $e) {
				$rows[$i]['users'] = '-';
			}
		}				
		return array(
			'rows'  => $rows,
			'count' => $count[0]['count']
		);
	}
	function addCustomer($arg) {
		#$this->loadLangFileIntoArray();
		/**
		 * check if customer already exists
		 */		 
			$t = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows (
				'*',
				'tx_sitemgr_customer',
				'title='.$GLOBALS['TYPO3_DB']->fullQuoteStr($arg['customerName'], 'tx_sitemgr_customer').' AND deleted=0',
				'',
				'');
			if(count($t)>0) {
				$this->addErrorForForm(
					'customerName',
					$GLOBALS['LANG']->getLL('error.customerName.alreadyTaken')
				);
			}
			$t = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows (
				'*',
				'be_users',
				'username='.$GLOBALS['TYPO3_DB']->fullQuoteStr($arg['customerName'], 'be_users').' AND deleted=0',
				'',
				'');
				
			if(count($t)>0) {
				$this->addErrorForForm(
					'customerName',
					$GLOBALS['LANG']->getLL('error.customerName.conflictBeUser')
				);
			}
		/**
		 * check email
		 */		 
			/*$t = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows (
				'*',
				'be_users',
				'email='.$GLOBALS['TYPO3_DB']->fullQuoteStr($arg['customerEmail']).' AND deleted=0',
				'',
				'');
			if(count($t)>0) {
				$this->addErrorForForm(
					'customerEmail',
					$GLOBALS['LANG']->getLL('error.customerEmail.alreadyTaken')
				);
			}*/
		/**
		 * return form control 
		 */		 		
			$r = $this->getReturnForForm();
			if($r['success']) {
				$this->addCustomerCreate($arg);
			}
			return $this->getReturnForForm();;
	}
	function deleteCustomer($uid) {
		//pre
			$customer = new Tx_Sitemgr_Utilities_CustomerUtilities($uid);
			$pid      = $customer->getPage();
		//fetch related be users
			$users = array();
			foreach($customer->getAllUsersUids() as $user) {
				$users[$user] = array('delete' => 1);
			}
		//fetch releated groups
			$groups = array();
			$t_groups = $customer->getGroups();
			$t_groups = explode(',',$t_groups);
			foreach($t_groups as $group) {
				$groups[$group] = array('delete' => 1);
			}
		//process data	
			$tcemain = t3lib_div::makeInstance('t3lib_TCEmain');
			$tcemain->deleteTree = true;
			$cmd     = array(
				//drop page
				'pages' => array(
					$pid => array(
						'delete' => 1
					),
				),
				'be_users'  => $users,
				'be_groups' => $groups
				//drop *users*!!!
				//drop group
			);
			$data = array();
			$tcemain->start($data,$cmd);
			$tcemain->process_cmdmap();
		//drop file
	}
	protected function addCustomerCreate($arg) {
		/***********************************************************************
		 * fetch needed options		 
		 */
	 		$tgroup = $GLOBALS["BE_USER"]->getTSConfig(
			  	'mod.web_txsitemgr.customer.createUser.group',
				t3lib_BEfunc::getPagesTSconfig($arg['uid'])
			);
			if(!strlen(trim($tgroup['value']))) {
				$this->addErrorForForm(
					null,
					'Sry, but you need to define mod.web_txsitemgr.customer.createUser.group in PageTS to ensure proper user rights'
				);
				return;
			}
		/***********************************************************************
		 * create first step records
		 */		 		
			//------------------------------------------------------------------
			//be_groups & be_users
			$data = array(
				//create page
				'pages' => array(
					'NEW11' => array(
						'pid'                   => $arg['uid'],
						'doktype'               => 4,
						'title'                 => $arg['customerName'],
						'nav_title'             => $arg['customerName'],
						'description'           => $arg['description'],
						'hidden'                => 0,
						'shortcut_mode'         => 1,
						'alias'                 => $arg['customerName'],
						'editlock'              => 1,
					),
					//create dummy page
					'NEW13' => array(
						'pid'                   => 'NEW11',
						'hidden'                => '0',
						'title'                 => 'Start'
					),
				),
				//create group
				'be_groups' => array (
					'NEW41' => array (
						'pid'                    => 0,
						'title'                  => 'E: '.$arg['customerName'],
						'hidden'                 => 0,
						'subgroup'               => $tgroup['value'],
						'db_mountpoints'         => 'NEW11',
					),
				),
				//create user
				'be_users' => array (
					'NEW31' => array(
						'pid'                    => 0,
						'username'               => $arg['customerName'],
						'realName'               => $arg['customerName'].'-admin',
						'email'                  => $arg['customerEmail'],
						'password'               => $arg['password'],
						'usergroup'              => 'NEW41',
						'fileoper_perms'		 => 15,
						'lang'                   => $GLOBALS['BE_USER']->uc['lang'],  // set this user lang as default language for the new user 
						'options'                => 2,
						'db_mountpoints'         => 'NEW11',
					),
				),
			);

				//execute hook
			$this->executeDatabasePreprocessing(
				'customerCreateRound2',
				$data,
				array(
					'parentUid'           => $arg['uid'],
					'description'         => $arg['description'],
					'customerName'        => $arg['customerName'],
					'customerParentGroup' => $tgroup['value'],
					'customerEmail'       => $arg['customerEmail'],
					'customerPassword'    => $arg['password'],
					'defaultLang'         => $GLOBALS['BE_USER']->uc['lang'],
				)
			);


			$tcemain = t3lib_div::makeInstance('t3lib_TCEmain');
			$tcemain->start($data,array());
			$tcemain->process_datamap();
			$groupId = $tcemain->substNEWwithIDs['NEW41'];
			$userId = $tcemain->substNEWwithIDs['NEW31'];
			$pageId = $tcemain->substNEWwithIDs['NEW11'];

		/***********************************************************************
		 * create second step records
		 */	
			$data = array(
				//create template
				'sys_template' => array(
					'NEW21' => array(
						'pid'                    => $pageId,
						'constants'              => '######################################################################'."\n".
													'# EXT:ks_sitemgr'."\n".
													'# createdate: '.date('r')."\n".
													'# userfolder: '.$GLOBALS['$TYPO3_CONF_VARS']['BE']['userHomePath'].$userId."\n".
						                            '  usr_name                    = '.$userId."\n".
													'  usr_root                    = '.$pageId."\n".
													'  plugin.tx_sitemgr.username  = '.$arg['customerName']."\n".
													'  plugin.tx_sitemgr.useremail = '.$arg['customerEmail']."\n".
													'  plugin.tx_sitemgr.userId    = '.$userId."\n".
													'  plugin.tx_sitemgr.rootPage  = '.$pageId."\n".
													'######################################################################'."\n",
						'sitetitle'              => $arg['customerName'],
						'title'                  => 'template for ext:sitemgr, contains username const. only',
						'root'                   => 1,
					),
				),
				//create customer
				'tx_sitemgr_customer' => array(
					'NEW61' => array(
						'pid'                    => $pageId,
						'title'                  => $arg['customerName'],
						'main_be_user'           => $userId,
						'be_groups'              => $groupId,
					),
				),
				//create acl
				'tx_beacl_acl' => array(
					'NEW51' => array(
						'pid'                    => $pageId,
						'type'                   => 0,
						'object_id'              => $userId,
						'cruser_id'              => $userId,   //set creator to owner
						'permissions'            => 27,       //do not delete rootpage, but allow all other things
						'recursive'              => 0,
					),
					'NEW52' => array(
						'pid'                    => $pageId,
						'type'                   => 0,
						'object_id'              => $userId,   //allow all for subpages
						'cruser_id'              => $userId,   //set creator to owner
						'permissions'            => 31,
						'recursive'              => 1,
					),
				),
				//modify be user
				'be_users' => array (
					$userId => array(
						'db_mountpoints'         => $pageId,
						'password'               => $arg['password'],
					),
				),
			);

				//execute hook
			$this->executeDatabasePreprocessing(
				'customerCreateRound2',
				$data,
				array(
					'parentUid'           => $arg['uid'],
					'description'         => $arg['description'],
					'defaultLang'         => $GLOBALS['BE_USER']->uc['lang'],
					'customerName'        => $arg['customerName'],
					'customerParentGroup' => $tgroup['value'],
					'customerEmail'       => $arg['customerEmail'],
					'customerPassword'    => $arg['password'],
					'customerAdminUid'    => $userId,
					'customerGroupUid'    => $groupId,
					'customerRootPid'     => $pageId,
				)
			);

			$tcemain = t3lib_div::makeInstance('t3lib_TCEmain');
			$tcemain->start($data,array());
			$tcemain->process_datamap();
		/***********************************************************************
		 * Fix problem with updating password
		 */		 		
			$data['be_users'][$userId]['password'] = md5($arg['password']);
			$erg = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
				'be_users',
				'uid='.$userId,
				$data['be_users'][$userId]
			);
		/***********************************************************************
		 * create user and group folder
		 */		 		
			if(t3lib_div::isAllowedAbsPath($GLOBALS['TYPO3_CONF_VARS']['BE']['groupHomePath'])) {	
				t3lib_div::mkdir($GLOBALS['TYPO3_CONF_VARS']['BE']['groupHomePath'].$groupId);
			}
			// user folder
			if(t3lib_div::isAllowedAbsPath($GLOBALS['TYPO3_CONF_VARS']['BE']['userHomePath'])) {
				t3lib_div::mkdir($GLOBALS['TYPO3_CONF_VARS']['BE']['userHomePath'].$userId);
			}
		/***********************************************************************
		 * clear cache
		 */
			if($arg['copyCheck']=='on') {
				$tcemain = t3lib_div::makeInstance('t3lib_TCEmain');
				$tcemain->copyTree = 99;
				$tcemain->copyWhichTables = '*';
				$cmd     = array(
					'pages' => array(
						$arg['customerCopyFrom'] => array(
							'copy' => $pageId
						),
					),
				);
				$tcemain->start(array(),$cmd);
				$tcemain->process_cmdmap();
			}

		$this->executeDatabasePreprocessing(
			'customerCreateRound3',
			$data,
			array(
				'parentUid'           => $arg['uid'],
				'description'         => $arg['description'],
				'defaultLang'         => $GLOBALS['BE_USER']->uc['lang'],
				'customerName'        => $arg['customerName'],
				'customerParentGroup' => $tgroup['value'],
				'customerEmail'       => $arg['customerEmail'],
				'customerPassword'    => $arg['password'],
				'customerAdminUid'    => $userId,
				'customerGroupUid'    => $groupId,
				'customerRootPid'     => $pageId,
			)
		);

		/***********************************************************************
		 * clear cache
		 */
		 	$tcemain->clear_cacheCmd('pages');	
	}
	/**
	 * @param $hookname string name of the hook
	 * @param $fields   array of fields
	 * @param $params   known params
	 */
	private function executeDatabasePreprocessing($hookname, &$fields, $params) {
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sitemgr'][$hookname])) {
			foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sitemgr'][$hookname] as $userFunc) {
				t3lib_div::callUserFunction($userFunc, $fields, $params, $this);
			}
		}
	}
}