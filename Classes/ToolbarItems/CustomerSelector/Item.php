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

		//@todo check
		$GLOBALS['TBE_TEMPLATE']->getPageRenderer()->addInlineLanguageLabelFile('EXT:' . $this->EXTKEY . '/Resources/Private/Language/Modules/BeUser/locallang.xml');
		$GLOBALS['TBE_TEMPLATE']->getPageRenderer()->addInlineLanguageLabelFile('EXT:' . $this->EXTKEY . '/Resources/Private/Language/Modules/Customer/locallang.xml');

		$buffer = '<a href="#" class="toolbar-item"><img src="'.t3lib_extMgm::extRelPath('sitemgr').'icon_tx_sitemgr_customer.gif" class="t3-icon" style="background-image:none;"></a>';
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
		$this->backendReference->addJavascriptFile(t3lib_extMgm::extRelPath($this->EXTKEY) . 'Resources/Public/JavaScripts/Modules/BeUser/beuserStore.js');
		$this->backendReference->addJavascriptFile(t3lib_extMgm::extRelPath($this->EXTKEY) . 'Resources/Public/JavaScripts/Modules/Customer/customerStore.js');
		$this->backendReference->addJavascriptFile(t3lib_extMgm::extRelPath($this->EXTKEY) . 'Resources/Public/JavaScripts/ToolbarItems/CustomerSelector.js');
	}

	/**
	 * adds the neccessary CSS to the backend
	 *
	 * @return	void
	 */
	protected function addCssToBackend() {
		$this->backendReference->addCssFile('sitemgr', t3lib_extMgm::extRelPath($this->EXTKEY) . 'Resources/Public/Stylesheets/BackendMod1/main.css');
		$this->backendReference->addCssFile('sitemgr', t3lib_extMgm::extRelPath($this->EXTKEY) . 'Resources/Public/Stylesheets/ToolbarItems/CustomerSelector.css');
	}
}