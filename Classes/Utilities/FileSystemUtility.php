<?php


class Tx_Sitemgr_Utilities_FileSystemUtility {
	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	protected $objectManager;

	/**
	 * prepare objects
	 */
	public function __construct() {
		$this->objectManager = t3lib_div::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
	}

	/**
	 * entry point
	 *
	 * @param $folder
	 */
	public function ensureFolderExists($folder) {
		if(strpos($folder, ':') === FALSE) {
			$this->createInRealFileSystem($folder);
		} else {
			list($storage, $path) = explode(':', $folder, 2);
			$this->createInFalFileSystem($storage, $path);
		}

	}

	/**
	 * tries to create a folder via pre 6.0 api
	 *
	 * @param string $folder
	 */
	protected function createInRealFileSystem($folder) {
		t3lib_div::mkdir($folder);
	}

	/**
	 * Tries to create a folder in FAL
	 *
	 * @param $storage
	 * @param $folder
	 * @throws Exception
	 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException
	 * @throws \TYPO3\CMS\Core\Resource\Exception\InsufficientFolderWritePermissionsException
	 */
	protected function createInFalFileSystem($storage, $folder) {
		/** @var \TYPO3\CMS\Core\Resource\StorageRepository $storageRepository */
		$storageRepository = $this->objectManager->get('TYPO3\CMS\Core\Resource\StorageRepository');
		/** @var \TYPO3\CMS\Core\Resource\ResourceStorage $storage */
		$storage = $storageRepository->findByUid($storage);
		$newFolder = basename($folder);
		$parentFolder = dirname($folder);
		$storage->createFolder($newFolder, $storage->getFolder($parentFolder));
	}
}