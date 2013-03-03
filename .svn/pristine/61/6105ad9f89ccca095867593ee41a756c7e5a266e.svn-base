<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
/*******************************************************************************
 * ExtDirectComponent
 */
	if (TYPO3_MODE == 'BE') {
		t3lib_extMgm::registerExtDirectComponent(
			'TYPO3.sitemgr.tabs',
			'Tx_Sitemgr_Controller_ExtDirectDispatcherController'
		);
	}

/*******************************************************************************
 * Modules
 */ 
	if (TYPO3_MODE == 'BE') {
			//load customer module
		Tx_Sitemgr_Utilities_CustomerModuleUtilities::registerModule(
			'sitemgr_customer',
			'Tx_Sitemgr_Modules_Customer_CustomerController'
		);

			//load user module
		if(t3lib_extMgm::isLoaded('be_acl')) {
			Tx_Sitemgr_Utilities_CustomerModuleUtilities::registerModule(
				'sitemgr_beuser',
				'Tx_Sitemgr_Modules_BeUser_BeUserController'
			);
		}

			//load help module
		Tx_Sitemgr_Utilities_CustomerModuleUtilities::registerModule(
			'sitemgr_help',
			'Tx_Sitemgr_Modules_Help_HelpController'
		);
	}

/*******************************************************************************
 * Toolbar item
 */ 
	if (TYPO3_MODE == 'BE') {
		$GLOBALS['TYPO3_CONF_VARS']['BE']['AJAX']['tx_sitemgr::searchCustomer']    
			= t3lib_extMgm::extPath($_EXTKEY).'Classes/ToolbarItems/CustomerSelector/Item.php:Tx_Sitemgr_ToolbarItems_CustomerSelector_Item->searchCustomer';
		$GLOBALS['TYPO3_CONF_VARS']['BE']['AJAX']['tx_sitemgr::openPageOfCustomer']
			= t3lib_extMgm::extPath($_EXTKEY).'Classes/ToolbarItems/CustomerSelector/Item.php:Tx_Sitemgr_ToolbarItems_CustomerSelector_Item->openPageOfCustomer';
		$GLOBALS['TYPO3_CONF_VARS']['typo3/backend.php']['additionalBackendItems'][]
			= t3lib_extMgm::extPath($_EXTKEY).'Classes/ToolbarItems/CustomerSelector/Hook.php';
	}

/*******************************************************************************
 * tx_sitemgr_customer
 */ 
	t3lib_extMgm::allowTableOnStandardPages('tx_sitemgr_customer');
	$TCA['tx_sitemgr_customer'] = array (
		'ctrl' => array (
			'title'     => 'LLL:EXT:sitemgr/Resources/Private/Language/locallang_db.xml:tx_sitemgr_customer',		
			'label'     => 'title',	
			'tstamp'    => 'tstamp',
			'crdate'    => 'crdate',
			'cruser_id' => 'cruser_id',
			'versioningWS' => TRUE, 
			'origUid' => 't3_origuid',
			'default_sortby' => 'ORDER BY title',	
			'delete' => 'deleted',
			'dividers2tabs'=>1,
			'adminOnly'=>1,
			'canNotCollapse'=>1,
			'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'Configuration/TCA/tx_sitemgr_customer.php',
			'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_sitemgr_customer.gif',
		),
	);

/*******************************************************************************
 * Add Type icon for customer: 
 */ 
	if (TYPO3_MODE == 'BE') {
		t3lib_div::loadTCA('pages');
		$TCA['pages']['columns']['module']['config']['items'][] = array(
			'LLL:EXT:sitemgr/Resources/Private/Language/locallang_db.xml:tx_sitemgr_customer',
			'sitemgr'
		);
		t3lib_SpriteManager::addTcaTypeIcon('pages', 'contains-sitemgr', t3lib_extMgm::extRelPath($_EXTKEY).'ext_icon.gif');
		#$ICON_TYPES['sitemgr'] = array('icon' => t3lib_extMgm::extRelPath($_EXTKEY).'ext_icon.gif');
	}

/*******************************************************************************
 * Add extbase Module 
 */ 
	if (TYPO3_MODE == 'BE') {
		Tx_Extbase_Utility_Extension::registerModule(
			$_EXTKEY,
			'web',		 			//Mainmodule
			'tx_sitemgr_mod1',		//Name
			'',						//Position
			array(					//Controller
				'SiteManager' => 'index' 
			),
			array(		//additional config
				'access' => 'user,group',
				'icon'   => 'EXT:sitemgr/Resources/Public/Images/Backend/mod1/moduleicon.gif', 
				'labels' => 'LLL:EXT:sitemgr/Resources/Private/Language/locallang_mod1.xml',
				'navigationComponentId' => 'typo3-pagetree',
			)
		);
	}

/*******************************************************************************
 * add context sensitive help
 */ 
 
	t3lib_extMgm::addLLrefForTCAdescr(
	   'tx_sitemgr_customer',
	   'EXT:sitemgr/Resources/Private/Language/locallang_csh_tx_sitemgr_customer.xml'
	);
?>