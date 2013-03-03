<?php


/**
 * @todo move that to sitemgr_template (as well as the registration of the hook)
 *
 */
class Tx_Sitemgr_Hook_CustomerCreateHook {
	function round3($fields,$params, $parent) {
		$templateName = $GLOBALS["BE_USER"]->getTSConfig(
					  	'mod.web_txsitemgr.template.defaultTemplate',
						t3lib_BEfunc::getPagesTSconfig($params['customerRootPid'])
					);
		$templateName = $templateName['value'];
		if(strlen(trim($templateName))!==0) {
			$TemplateRepository = new Tx_SitemgrTemplate_Domain_Repository_TemplateRepository();
				// ensure defaults
			$TemplateRepository->get($templateName)->getEnvironmentOptions($params['customerRootPid']);
				//setup initial template
			$TemplateRepository->get($templateName)->setEnvironment($params['customerRootPid'], null);
		}
	}
}