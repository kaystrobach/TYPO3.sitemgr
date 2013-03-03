<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_sitemgr_customer'] = array (
	'ctrl' => $TCA['tx_sitemgr_customer']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'title,main_be_user,admin_be_users,normal_be_users,be_groups'
	),
	'feInterface' => $TCA['tx_sitemgr_customer']['feInterface'],
	'columns' => array (
		't3ver_label' => array (		
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'max'  => '30',
			)
		),
		'title' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:sitemgr/Resources/Private/Language/locallang_db.xml:tx_sitemgr_customer.title',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',
				'eval'=>'alphanum_x,nospace,unique,required',
			)
		),
		'main_be_user' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:sitemgr/Resources/Private/Language/locallang_db.xml:tx_sitemgr_customer.main_be_user',		
			'config' => array (
				'type' => 'group',	
				'internal_type' => 'db',	
				'allowed' => 'be_users',	
				'size' => 1,	
				'minitems' => 1,
				'maxitems' => 1,
				'prepend_tname'=>false,
				'wizards' => array(
			        'suggest' => array(    
			            'type' => 'suggest',
			        ),
			    ),
			)
		),
		'admin_be_users' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:sitemgr/Resources/Private/Language/locallang_db.xml:tx_sitemgr_customer.admin_be_users',		
			'config' => array (
				'type' => 'group',	
				'internal_type' => 'db',
				'allowed' => 'be_users',	
				'size' => 3,
				'autoSizeMax'=>15,
				'minitems' => 0,
				'maxitems' => 100,
				'prepend_tname'=>false,
				'wizards' => array(
			        'suggest' => array(    
			            'type' => 'suggest',
			        ),
			    ),
			)
		),
		'normal_be_users' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:sitemgr/Resources/Private/Language/locallang_db.xml:tx_sitemgr_customer.normal_be_users',		
			'config' => array (
				'type' => 'group',	
				'internal_type' => 'db',	
				'allowed' => 'be_users',	
				'size' => 3,
				'autoSizeMax'=>15,	
				'minitems' => 0,
				'maxitems' => 100,
				'prepend_tname'=>false,
				'wizards' => array(
			        'suggest' => array(    
			            'type' => 'suggest',
			        ),
			    ),
			)
		),
		'be_groups' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:sitemgr/Resources/Private/Language/locallang_db.xml:tx_sitemgr_customer.be_groups',		
			'config' => array (
				'type' => 'group',	
				'internal_type' => 'db',	
				'allowed' => 'be_groups',	
				'size' => 3,
				'autoSizeMax'=>15,	
				'minitems' => 0,
				'maxitems' => 100,
				'prepend_tname'=>false,
				'wizards' => array(
			        'suggest' => array(    
			            'type' => 'suggest',
			        ),
			    ),
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => '--div--;LLL:EXT:sitemgr/Resources/Private/Language/locallang_db.xml:tx_sitemgr_customer.div_required,title;;;;2-2-2, main_be_user;;;;3-3-3,'.
		                           '--div--;LLL:EXT:sitemgr/Resources/Private/Language/locallang_db.xml:tx_sitemgr_customer.div_more, admin_be_users, normal_be_users,be_groups,'.
								   '--div--;LLL:EXT:sitemgr/Resources/Private/Language/locallang_db.xml:tx_sitemgr_customer.div_contact, tt_address_records')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);

if(t3lib_extMgm::isLoaded('tt_address', FALSE)) {
	$TCA['tx_sitemgr_customer']['columns']['tt_address_records'] = array(
		'exclude' => 0,
		'label' => 'LLL:EXT:sitemgr/Resources/Private/Language/locallang_db.xml:tx_sitemgr_customer.tt_address_records',
		'config' => array (
			'type'          => 'inline',
			'foreign_table' => 'tt_address',
			'maxitems'      => 10
		)
	);

}

?>