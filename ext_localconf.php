<?php
/*******************************************************************************
 * load fe hooks
 */ 
	if(TYPO3_MODE === 'FE') {
		$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] = 'tx_Sitemgr_Fe_ContentPostProc->contentPostProc_output'; 
		$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][]    = 'tx_Sitemgr_Fe_ContentPostProc->contentPostProc_all'; 
	}