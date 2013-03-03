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
 * mod1/extjs.js
 *
 * help module
 * show jst some links to provide help for the users. 
 *
 * $Id$
 *
 * @author Kay Strobach <typo3@kay-strobach.de>
 */


	Ext.onReady(function (){
		Ext.getCmp('Sitemgr_App_Tabs').add({
			title:TYPO3.lang.SitemgrHelp_Title,
			//html :TYPO3.lang.SitemgrHelp_Description,
			layout: 'border',
			iconCls: 'help-tab-icon',
			id   :'SitemgrHelp',
			defaults: {
				padding: 10
			},
			items: [
				{
					region: 'west',
					width: 150,
					collapseMode: 'mini',
					split: true,
					titleCollapse: false,
					layout: 'fit',
					items: [
						{
							xtype: 'treepanel',
							useArrows: true,
							animate: true,
							root: new Ext.tree.AsyncTreeNode(TYPO3.settings.sitemgr_help.links),
							rootVisible: false,
							listeners: {
								click: function(node, event) {
									if(node.attributes.uri) {
										buffer = '<iframe width="100%" height="100%" frameborder="0" src="' + node.attributes.uri + '">';
									} else {
										buffer = '<div class="typo3-message message-information"><div class="message-body">' + TYPO3.lang.SitemgrHelp_selectHint + '</div></div>'
									}
									Ext.getCmp('SitemgrHelp').get(1).update(buffer);
								},
								afterlayout: function (container) {
									container.expandAll();
								},
								contextmenu: function(node) {
									if(node.attributes.uri) {
										node.select();
										menu = new Ext.menu.Menu(
											{
												node: node,
												items: [
													{
														iconCls: 't3-icon t3-icon-actions t3-icon-actions-window t3-icon-window-open',
														text: TYPO3.lang.SitemgrHelp_openInNewWin,
														handler: function() {
															window.open(this.ownerCt.node.attributes.uri);
														}
													}
												]
											}
										).show(node.ui.getAnchor());
									}
								}
							}
						}
					]
				}, {
					region: 'center',
					html: '<div class="typo3-message message-information"><div class="message-body">' + TYPO3.lang.SitemgrHelp_selectHint + '</div></div>'
				}
			]
		});
	}
);