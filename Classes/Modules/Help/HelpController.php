<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

class Tx_Sitemgr_Modules_Help_HelpController extends Tx_Sitemgr_Modules_Abstract_AbstractController{
	protected $file = __FILE__;
	protected $access = array(
		'general' => 'all'
	);
	function getModuleJavaScript(&$js,$uid) {
		$js.= $this->getModuleJavaScriptHelper(
			dirname(__FILE__).'/extjs.js',
			$uid
		);
	}
	function __construct() {
		$this->settings = array(
			'links' => array(
				'text'=> 'Root',
				'children'=>array(
					array(
						'text'=> 'English',
						'iconCls' => 't3-icon t3-icon-flags t3-icon-flags-gb t3-icon-gb',
						'children'=> array(
							array(
								'text'=> 'typo3.org',
								'iconCls' => 't3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-extension-documentation',
								'children'=> array(
									array(
										'leaf'=> true,
										'text'=> 'Wiki',
										'uri'=>  'http://wiki.typo3.org/Main_Page'
									),
									array(
										'leaf'=> true,
										'text'=> 'Videos',
										'uri'=>  'http://typo3.org/documentation/videos/tutorials-v4-de/'
									),
									array(
										'leaf'=> true,
										'text'=> 'Reference',
										'uri'=>  'http://typo3.org/documentation/videos/quick-reference-v4-de/'
									),
									array(
										'leaf'=> true,
										'text'=> 'Sitemgr Manual',
										'uri'=>  'http://typo3.org/extensions/repository/view/sitemgr/current/'
									),
									array(
										'leaf'=> true,
										'text'=> 'Sitemgr Template Manual',
										'uri'=>  'http://typo3.org/extensions/repository/view/sitemgr_template/current/'
									),
									array(
										'leaf'=> true,
										'text'=> 'Sitemgr on Forge',
										'uri'=>  'http://forge.typo3.org/projects/extension-sitemgr'
									)
								)
							)
						),
					),
					array(
						'text'=> 'Deutsch',
						'iconCls' => 't3-icon t3-icon-flags t3-icon-flags-de t3-icon-de',
						'children'=> array(
							array(
								'text'=> 'SBS',
								'children'=> array(
									array(
										'leaf'=> true,
										'text'=> 'Hinweise',
										'uri'=>  'http://cms.sn.schule.de'
									),
									array(
										'leaf'=> true,
										'text'=> 'Handbuch',
										'uri'=>  'http://cms.sn.schule.de/admin/handbuch/'
									),
									array(
										'leaf'=> true,
										'text'=> 'Literatur',
										'uri'=>  'http://cms.sn.schule.de/admin/administrative-informationen/weitere-quellen/'
									)
								),
							),
							array(
								'text'=> 'Kay Strobach',
								'children'=> array(
									array(
										'leaf'=> true,
										'text'=> 'Erweiterungen',
										'uri'=>  'http://www.kay-strobach.de/projekte/typo3-extensions/'
									),
									array(
										'leaf'=> true,
										'text'=> 'Präsentationen',
										'uri'=>  'http://www.kay-strobach.de/projekte/typo3-praesentationen/fuer-redakteure/'
									),
								),
							),
							array(
								'text'=> 'Mittwald',
								'children'=> array(
									array(
										'leaf'=> true,
										'text'=> 'Handbuch',
										'uri'=>  'http://www.mittwald.de/typo3-dokumentation/'
									)
								)
							)
						)
					)
				)
			)
		);
	}
}
