<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "sitemgr".
 *
 * Auto generated 11-03-2013 20:25
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Sitemanager and Customer Management',
	'description' => 'Sitemanager and Customer Management made easy. With this extension you can create ´small admins´.',
	'category' => 'be',
	'author' => 'Kay Strobach',
	'author_email' => 'kay.strobach@typo3.org',
	'shy' => '',
	'dependencies' => 'be_acl,cms,extbase,fluid',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author_company' => '',
	'version' => '2.4.2',
	'constraints' => 
	array (
		'depends' => 
		array (
			'be_acl' => '1.4.4',
			'typo3' => '4.5.0-6.0.99',
			'extbase' => '',
			'fluid' => '',
		),
		'suggests' => 
		array (
			'fluid' => 'sitemgr_template',
		),
		'conflicts' => 
		array (
		),
	),
	'_md5_values_when_last_written' => 'a:53:{s:13:"CHANGELOG.TXT";s:4:"b1f2";s:10:"README.txt";s:4:"d6d8";s:20:"class.ext_update.php";s:4:"ed5a";s:16:"ext_autoload.php";s:4:"2499";s:21:"ext_conf_template.txt";s:4:"f013";s:12:"ext_icon.gif";s:4:"6759";s:17:"ext_localconf.php";s:4:"17e8";s:14:"ext_tables.php";s:4:"7caa";s:14:"ext_tables.sql";s:4:"9b53";s:28:"icon_tx_sitemgr_customer.gif";s:4:"4f5b";s:52:"Classes/Controller/ExtDirectDispatcherController.php";s:4:"e461";s:46:"Classes/Controller/ExtMgmUpdaterController.php";s:4:"6dd3";s:44:"Classes/Controller/SiteManagerController.php";s:4:"a5d6";s:53:"Classes/Controller/Abstract/ExtMgmUpdaterAbstract.php";s:4:"3cdf";s:30:"Classes/Fe/ContentPostProc.php";s:4:"eb33";s:47:"Classes/Modules/Abstract/AbstractController.php";s:4:"cc05";s:43:"Classes/Modules/BeUser/BeUserController.php";s:4:"1c62";s:47:"Classes/Modules/Customer/CustomerController.php";s:4:"fcf4";s:39:"Classes/Modules/Help/HelpController.php";s:4:"ed13";s:46:"Classes/ToolbarItems/CustomerSelector/Hook.php";s:4:"48ca";s:46:"Classes/ToolbarItems/CustomerSelector/Item.php";s:4:"24ad";s:45:"Classes/Utilities/CustomerModuleUtilities.php";s:4:"84c3";s:39:"Classes/Utilities/CustomerUtilities.php";s:4:"f8dd";s:30:"Classes/Utilities/Registry.php";s:4:"2b70";s:50:"Classes/ViewHelper/Be/Doc/AddCssFileViewHelper.php";s:4:"e961";s:66:"Classes/ViewHelper/Be/Doc/AddInlineLanguageLabelFileViewHelper.php";s:4:"91ec";s:61:"Classes/ViewHelper/Be/Doc/AddInlineSettingArrayViewHelper.php";s:4:"bd06";s:49:"Classes/ViewHelper/Be/Doc/AddJsFileViewHelper.php";s:4:"c4fc";s:55:"Classes/ViewHelper/Be/Doc/AddJsFooterFileViewHelper.php";s:4:"1a9a";s:41:"Configuration/TCA/tx_sitemgr_customer.php";s:4:"8d62";s:45:"Documentation/Manual/OpenOffice/en/manual.sxw";s:4:"33e9";s:64:"Resources/Private/Language/locallang_csh_tx_sitemgr_customer.xml";s:4:"86c7";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"cc42";s:47:"Resources/Private/Language/locallang_extmgm.xml";s:4:"0b8f";s:45:"Resources/Private/Language/locallang_mod1.xml";s:4:"62f1";s:55:"Resources/Private/Language/Modules/BeUser/locallang.xml";s:4:"dab8";s:57:"Resources/Private/Language/Modules/Customer/locallang.xml";s:4:"384f";s:53:"Resources/Private/Language/Modules/Help/locallang.xml";s:4:"a15e";s:50:"Resources/Private/Templates/SiteManager/index.html";s:4:"eca5";s:51:"Resources/Public/Images/Backend/mod1/moduleicon.gif";s:4:"dc56";s:50:"Resources/Public/Images/Modules/BeUser/tabIcon.gif";s:4:"8ab9";s:52:"Resources/Public/Images/Modules/Customer/tabIcon.gif";s:4:"4f5b";s:48:"Resources/Public/Images/Modules/Help/tabIcon.gif";s:4:"7e7e";s:48:"Resources/Public/JavaScripts/BackendMod1/main.js";s:4:"637d";s:51:"Resources/Public/JavaScripts/Modules/BeUser/main.js";s:4:"51a8";s:53:"Resources/Public/JavaScripts/Modules/Customer/main.js";s:4:"4b51";s:49:"Resources/Public/JavaScripts/Modules/Help/main.js";s:4:"ac5b";s:61:"Resources/Public/JavaScripts/ToolbarItems/CustomerSelector.js";s:4:"083d";s:49:"Resources/Public/Stylesheets/BackendMod1/main.css";s:4:"e1eb";s:52:"Resources/Public/Stylesheets/Modules/BeUser/main.css";s:4:"3bd5";s:54:"Resources/Public/Stylesheets/Modules/Customer/main.css";s:4:"8b31";s:50:"Resources/Public/Stylesheets/Modules/Help/main.css";s:4:"5886";s:62:"Resources/Public/Stylesheets/ToolbarItems/CustomerSelector.css";s:4:"fa74";}',
	'suggests' => 
	array (
	),
);

?>
