<?php

class tx_sitemgr_Tca_FeUserGroupHandler {
	function initialize(&$PA) {
		$this->confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sitemgr']);
		$this->PA = &$PA;
		$this->table = $PA['table'];
		$this->field = $PA['field'];
		$this->row = $PA['row'];
		$this->fieldConfig = $PA['fieldConf']['config'];
		$this->perms_clause = $GLOBALS['BE_USER']->getPagePermsClause(1);
	}
	function getFeUserGroups(&$PA, &$fobj) {
		$this->initialize($PA);

		//@todo search customerroot
		$pagesWithRecords = $this->getSubPages(0);

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows( 'uid,title', 'fe_groups', "pid IN(".join(',', $pagesWithRecords).") AND deleted='0' AND hidden='0'", '', 'title' );
		foreach ($res as $item) {
			$PA['items'][] = array($item['title'], $item['uid']);
		}
	}
}