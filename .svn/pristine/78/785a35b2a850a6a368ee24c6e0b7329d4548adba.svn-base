<?php

require_once(PATH_typo3 . 'interfaces/interface.backend_toolbaritem.php');

class Tx_Sitemgr_ToolbarItems_CustomerSelector_Item  implements backend_toolbarItem  {
	/**
	 * reference back to the backend object
	 *
	 * @var	TYPO3backend
	 */
	protected $backendReference;
	protected $EXTKEY = 'sitemgr';
	/**
	 * constructor, loads the documents from the user control
	 *
	 * @param	TYPO3backend	TYPO3 backend object reference
	 */
	public function __construct(TYPO3backend &$backendReference = null) {
		$this->backendReference = $backendReference;
	}
	/**
	 * checks whether the user has access to this toolbar item
	 *
	 * @return  boolean  true if user has access, false if not
	 */
	public function checkAccess() {
		return $GLOBALS['BE_USER']->user['admin'];
	}
	/**
	 * renders the toolbar item and the initial menu
	 *
	 * @return	string		the toolbar item including the initial menu content as HTML
	 */
	public function render() {
		$this->addJavascriptToBackend();
		$this->addCssToBackend();
		$buffer = '<a href="#" class="toolbar-item"><img src="'.t3lib_extMgm::extRelPath('sitemgr').'icon_tx_sitemgr_customer.gif" class="t3-icon" style="background-image:none;"></a>';
		$buffer.= '<div class="toolbar-item-menu" style="display: none;">';
		$buffer.= '<div id="sitemgr_form"></div>';
		$buffer.= '<div id="toolbar-item-menu-dynamic">'.$this->renderMenu().'</div>';
		$buffer.= '</div>';
		return $buffer;
	}
	function renderMenu() {
		if(t3lib_div::_POST('customer')) {
			include_once(t3lib_extMgm::extPath('sitemgr').'lib/class.tx_sitemgr_customer.php');
			$entries = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'title,uid,pid',
				'tx_sitemgr_customer',
				$GLOBALS['TYPO3_DB']->searchQuery(
					array(t3lib_div::_POST('customer')),
					array('title'),
					'tx_sitemgr_customer').' AND deleted=0',
					'',
					'',
					'0,10'
			);
			$buffer.='<table width="100%">';
			$buffer.='<colgroup><col width="16"><col width="144"><col width="40"></colgroup>';
			foreach($entries as $entry) {
				//show customer
				$buffer.='<tr><td>';
				$buffer.='<img src="'.t3lib_extMgm::extRelPath('sitemgr').'icon_tx_sitemgr_customer.gif">';
				$buffer.='</td><td>';
				$buffer.=htmlspecialchars($entry['title']);
				$buffer.='</td><td>';
				$buffer.='<img src="'.t3lib_extMgm::extRelPath('templavoila').'mod1/moduleicon.gif" onClick="TYPO3BackendSitemgr.openSite('.$entry['pid'].');">';
				$buffer.='<img src="'.t3lib_extMgm::extRelPath('sitemgr').'mod1/moduleicon.gif" onClick="TYPO3BackendSitemgr.openManagement('.$entry['pid'].');">';
				$buffer.='</td></tr>';
				//show users
				$customer = new tx_ks_sitemgr_customer($entry['uid']);
				$customer->init();
				foreach($customer->getAllUsers() as $user) {
					$buffer.='<tr><td>';
					$buffer.='</td><td>';
					$buffer.='<img src="'.t3lib_extMgm::extRelPath('t3skin').'icons/gfx/i/be_users.gif" onClick="TYPO3BackendSitemgr.openSite('.$entry['pid'].');">';
					$buffer.=$user['username'];
					$buffer.='</td><td>';
					$buffer.='<a href="mod.php?M=tools_beuser&SwitchUser='.$user['uid'].'&switchBackUser=1">';
					$buffer.='<img src="'.t3lib_extMgm::extRelPath('t3skin').'icons/gfx/su_back.gif">';
					$buffer.='</a>';
					$buffer.='</td></tr>';
				}
			}
			$buffer.='</table>';
		} else {
			$buffer.='';
		}
		return $buffer;
	}
	/**
	 * returns additional attributes for the list item in the toolbar
	 *
	 * @return	string		list item HTML attibutes
	 */
	public function getAdditionalAttributes() {
		return ' id="tx-sitemgr-menu"';
	}
	/**
	 * adds the neccessary javascript to the backend
	 *
	 * @return	void
	 */
	protected function addJavascriptToBackend() {
		$this->backendReference->addJavascriptFile(t3lib_extMgm::extRelPath($this->EXTKEY) . 'Resources/Public/JavaScripts/ToolbarItems/CustomerSelector.js');
	}

	/**
	 * adds the neccessary CSS to the backend
	 *
	 * @return	void
	 */
	protected function addCssToBackend() {
		$this->backendReference->addCssFile('sitemgr', t3lib_extMgm::extRelPath($this->EXTKEY) . 'Resources/Public/Stylesheets/ToolbarItems/CustomerSelector.css');
	}
	
	//==========================================================================
	// AJAX
	//==========================================================================
	/**
	 * renders the menu so that it can be returned as response to an AJAX call
	 *
	 * @param	array		array of parameters from the AJAX interface, currently unused
	 * @param	TYPO3AJAX	object of type TYPO3AJAX
	 * @return	void
	 */
	public function renderAjax($params = array(), TYPO3AJAX &$ajaxObj = null) {
		$menuContent = $this->renderMenu();

		$ajaxObj->addContent('opendocsMenu', $menuContent);
	}	
	function searchCustomer($params = array(), TYPO3AJAX &$ajaxObj = null) {
		$params['JScode'] = '
			if (top && top.TYPO3BackendOpenDocs) {
				top.TYPO3BackendOpenDocs.updateNumberOfDocs(' . count($this->openDocs) . ', true);
			}
		';
		$this->renderAjax($params, $ajaxObj);
	}
}