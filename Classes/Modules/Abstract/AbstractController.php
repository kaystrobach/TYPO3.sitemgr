<?php 
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

class Tx_Sitemgr_Modules_Abstract_AbstractController{
	/**
	 * @var array
	 */
	protected $jsFiles = array();
	/**
	 * @var array
	 */
	protected $cssFiles = array();
	/**
	 * @var null
	 */
	protected $settings = null;
	/**
	 * @throws Exception
	 * @return void
	 */
	protected function calculatePathToModule() {
		throw new Exception('deprecated function');
	}
	/**
	 * @return string
	 */
	protected function getModuleJsPath() {
		return str_replace('Classes/Modules', 'Resources/Public/JavaScripts/Modules', $this->getModuleGenericPath()).'/';
	}
	/**
	 * @return string
	 */
	protected function getModuleCssPath() {
		return str_replace('Classes/Modules', 'Resources/Public/Stylesheets/Modules', $this->getModuleGenericPath()).'/';
	}
	/**
	 * @return string
	 */
	protected function getModuleLangPath() {
		return str_replace('Classes/Modules', 'Resources/Private/Language/Modules', $this->getModuleGenericPath()).'/';
	}
	/**
	 * @throws Exception
	 * @return string
	 */
	protected function getModuleGenericPath() {
		$className = get_class($this);
		$classNameParts = explode('_', $className, 3);
		$extensionKey = t3lib_div::camelCaseToLowerCaseUnderscored($classNameParts[1]);
		if (t3lib_extMgm::isLoaded($extensionKey)) {
			$classFilePathAndName = t3lib_extMgm::extRelPath($extensionKey) . 'Classes/' . strtr($classNameParts[2], '_', '/') . '.php';
		} else {
			throw new Exception('Extension not loaded: '.$extensionKey);
		}
		return dirname($classFilePathAndName);
	}
	/**
	 * @return array
	 */
	function getModuleJsFile() {
		$return   = $this->jsFiles;
		$return[] = $this->getModuleJsPath().'main.js';
		return $return;
	}
	/**
	 * @return string
	 */
	function getModuleCssFile() {
		$return   = $this->cssFiles;
		$return[] = $this->getModuleCssPath().'main.css';
		return $return;
	}
	/**
	 * @return string
	 */
	function getModuleLLFile() {
		$file = $this->getModuleLangPath().'locallang.xml';
		$file = substr($file,17);
		return 'EXT:'.$file;
	}
	/**
	 * @return array
	 */
	function getModuleSettings() {
		return $this->settings;
	}
	/**
	 * inits the form
	 */
	protected function initForm() {
		if($this->form === null) {
			$this->form = array(
				'success' => 'true',
			);
		}
	}
	/**
	 * @param $field
	 * @param $message
	 * @param null $value
	 * @return array
	 */
	protected function addErrorForForm($field,$message,$value=NULL) {
			// field related error
		$this->initForm();
		$this->form['errors']  = array();
		$this->form['success'] = false;
		if($field !== null) {
			$this->form['errors'][$field] = $message;
			if($value) {
				$this->form['data'][$field] = $value;
			}
		} else {
			if($field === null) {
				$this->form['errorMessage'].= $message;
			}
		}
		return $this->form;
	}
	/**
	 * @return array
	 */
	protected function getReturnForForm() {
		$this->initForm();
		return $this->form;
	}
	protected function addSuccessMessage($message) {
		$this->initForm();
		$this->form['successMessage'].= $message;
	}
}