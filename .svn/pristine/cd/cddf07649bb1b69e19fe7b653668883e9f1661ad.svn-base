<?php
/*******************************************************************************
 * load fe hooks
 */ 
	if(TYPO3_MODE === 'FE') {
		$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] = 'tx_Sitemgr_Fe_ContentPostProc->contentPostProc_output'; 
		$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][]    = 'tx_Sitemgr_Fe_ContentPostProc->contentPostProc_all'; 
	}
	if(TYPO3_MODE === 'BE') {
		$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sitemgr']['customerCreateRound3'][] = 'EXT:sitemgr/Classes/Hook/CustomerCreateHook.php:Tx_Sitemgr_Hook_CustomerCreateHook->round3';
	}