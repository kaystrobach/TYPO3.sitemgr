<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Kay Strobach (typo3@kay-strobach.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * class.ext_update.php
 *
 * extmgm update script
 *
 * $Id: class.ext_update.php 42961 2011-02-02 08:25:05Z kaystrobach $
 *
 * @author Kay Strobach <typo3@kay-strobach.de>
 */

require_once('Abstract/ExtMgmUpdaterAbstract.php');
class Tx_Sitemgr_Controller_ExtMgmUpdaterController extends Tx_Sitemgr_Controller_Abstract_ExtMgmUpdaterAbstract{
	function main() {
		global $LANG;
		$LANG->includeLLFile('EXT:sitemgr/Resources/Private/Language/locallang_extmgm.xml');
		$func = trim(t3lib_div::_GP('func'));
		$buffer = '';
		if(t3lib_div::_GP('do_update')) {
			if (method_exists($this, $func)) {
				$flashMessage = t3lib_div::makeInstance(
					't3lib_FlashMessage',
					$this->$func(),
					'',
					t3lib_FlashMessage::OK
			    );
				$buffer.= $flashMessage->render();
			} else {
				$buffer.=$LANG->getLL('methodNotFound');
			}
		}
		$flashMessage = t3lib_div::makeInstance(
					't3lib_FlashMessage',
					$LANG->getLL('message'),
					'',
					t3lib_FlashMessage::INFO
			    );
		
		$buffer.= $flashMessage->render();
		$buffer.= $this->getHeader($LANG->getLL('header.installation'));
		$buffer.= $this->getFooter();

		$buffer.= $this->getHeader($LANG->getLL('header.configuration'));
		$buffer.= $this->getFooter();

		$buffer.= $this->getHeader($LANG->getLL('header.sitemgr'));
		$sitemgrEnabled = t3lib_extMgm::isLoaded('ks_sitemgr') || 1;
		$buffer.= $this->getButton('importFromKsSitemgr', $sitemgrEnabled);
		$buffer.= $this->getButton('cleanUpDatabase'    , $sitemgrEnabled);
		$buffer.= $this->getFooter();
		return $buffer;
	}
	function cleanUpDatabase() {
		//touch be_users
		$GLOBALS['TYPO3_DB']->admin_query('ALTER TABLE be_users DROP tx_sitemgr_manager_for_be_groups');
		//$GLOBALS['TYPO3_DB']->admin_query('ALTER TABLE be_users CHANGE COLUMN tx_sitemgr_manager_for_be_groups                        tx_kssitemgr_manager_for_be_groups');

		//touch tv table
		$GLOBALS['TYPO3_DB']->admin_query('ALTER TABLE tx_templavoila_tmplobj DROP tx_sitemgr_manager_allowed_for_customer');
		$GLOBALS['TYPO3_DB']->admin_query('ALTER TABLE tx_templavoila_tmplobj DROP tx_kssitemgr_manager_allowed_for_customer');

		//migrate table
		$GLOBALS['TYPO3_DB']->admin_query('DROP TABLE tx_sitemgr_customer');
		$GLOBALS['TYPO3_DB']->admin_query('RENAME TABLE tx_kssitemgr_customer TO tx_sitemgr_customer');

		return 'If no errors are displayed, everything worked fine.';
	}
	function importFromKsSitemgr() {
		$GLOBALS['TYPO3_DB']->debugOutput = TRUE;
		$GLOBALS['TYPO3_DB']->admin_query('TRUNCATE tx_sitemgr_customer');
		$GLOBALS['TYPO3_DB']->admin_query('
			INSERT IGNORE INTO tx_sitemgr_customer (uid, pid, tstamp, crdate, cruser_id, t3ver_oid, t3ver_id, t3ver_wsid, t3ver_label, t3ver_state, t3ver_stage, t3ver_count, t3ver_tstamp, t3_origuid, deleted, title, main_be_user, admin_be_users, normal_be_users, be_groups, tt_address_records)
			SELECT                                  uid, pid, tstamp, crdate, cruser_id, t3ver_oid, t3ver_id, t3ver_wsid, t3ver_label, t3ver_state, t3ver_stage, t3ver_count, t3ver_tstamp, t3_origuid, deleted, title, main_be_user, admin_be_users, normal_be_users, be_groups, 0 FROM tx_kssitemgr_customer
		');
		if(!$error = $GLOBALS['TYPO3_DB']->sql_error()) {
			return 'If no errors are displayed, everything worked fine.';
		} else {
			return $error;
		}
	}
}