<?php
class tx_Sitemgr_Fe_ContentPostProc{
	/**
	 * handler for non cached output processing
	 *
	 * @param	pointer    $$params: passed params from the hook
	 * @param	pointer    $reference: to the parent object
	 * @return	void       void
	 */
	function contentPostProc_output(&$params, &$reference){
		$this->extConf = $params['pObj']->tmpl->setup['plugin.']['tx_sitemgr.'];
		$this->extConf = $params['pObj'];
		#print_r($this->extConf);
		#die();
	}
    /**
	 * handler for cached output processing
	 *
	 * @param	pointer    $$params: passed params from the hook
	 * @param	pointer    $reference: to the parent object
	 * @return	void       void
	 */
	function contentPostProc_all(&$params, &$reference){
		
	}
}
?>