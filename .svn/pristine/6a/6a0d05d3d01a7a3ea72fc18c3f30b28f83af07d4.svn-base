/***************************************************************
*  Copyright notice
*
*  (c) 2008-2010 Benjamin Mack <mack@xnos.org>
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
 * class to handle the open documents menu, loads the open documents dynamically
 *
 */
var sitemgrCustomerSelector = Class.create({
	ajaxScript: 'ajax.php',
	menu: null,
	toolbarItemIcon: null,

	/**
	 * registers for resize event listener and executes on DOM ready
	 */
	initialize: function() {
		Event.observe(window, 'resize', this.positionMenu);

		Ext.onReady(function() {
			this.positionMenu();
			this.toolbarItemIcon     = $$('#tx-sitemgr-menu .toolbar-item img.t3-icon')[0];
			this.origToolbarItemIcon = this.toolbarItemIcon.src;
			this.ajaxScript          = top.TS.PATH_typo3 + this.ajaxScript; // can't be initialized earlier

			Event.observe($$('#tx-sitemgr-menu .toolbar-item')[0], 'click', this.toggleMenu);
			this.menu = $$('#tx-sitemgr-menu #toolbar-item-menu-dynamic')[0];
			
			form = new Ext.form.TextField({
				renderTo:Ext.get('sitemgr_form'),
				id:'sitemgr_form_customer',
				xtype:'textfield',
				width:190,
				margin:10,
				enableKeyEvents:true,
				listeners:{
					valid:{
						buffer:1,
						fn:function() {
							top.TYPO3BackendSitemgr.updateMenu();
						}
					}
				}
			});
			
			
		}, this);
	},

	/**
	 * positions the menu below the toolbar icon, let's do some math!
	 */
	positionMenu: function() {
		var calculatedOffset = 0;
		var parentWidth      = $('tx-sitemgr-menu').getWidth();
		var currentToolbarItemLayer = $$('#tx-sitemgr-menu .toolbar-item-menu')[0];
		var ownWidth         = currentToolbarItemLayer.getWidth();
		var parentSiblings   = $('tx-sitemgr-menu').previousSiblings();

		parentSiblings.each(function(toolbarItem) {
			calculatedOffset += toolbarItem.getWidth() - 1;
			// -1 to compensate for the margin-right -1px of the list items,
			// which itself is necessary for overlaying the separator with the active state background

			if(toolbarItem.down().hasClassName('no-separator')) {
				calculatedOffset -= 1;
			}
		});
		calculatedOffset = calculatedOffset - ownWidth + parentWidth;

			// border correction
		if (currentToolbarItemLayer.getStyle('display') !== 'none') {
			calculatedOffset += 2;
		}

		$$('#tx-sitemgr-menu .toolbar-item-menu')[0].setStyle({
			left: calculatedOffset + 'px'
		});
	},

	/**
	 * toggles the visibility of the menu and places it under the toolbar icon
	 */
	toggleMenu: function(event) {
		var toolbarItem = $$('#tx-sitemgr-menu > a')[0];
		var menu        = $$('#tx-sitemgr-menu .toolbar-item-menu')[0];
		toolbarItem.blur();

		if(!toolbarItem.hasClassName('toolbar-item-active')) {
			toolbarItem.addClassName('toolbar-item-active');
			Effect.Appear(menu, {duration: 0.2});
			TYPO3BackendToolbarManager.hideOthers(toolbarItem);
			new Ext.util.DelayedTask(function() {
				Ext.getCmp('sitemgr_form_customer').focus(true,true);
			}).delay(100);
		} else {
			toolbarItem.removeClassName('toolbar-item-active');
			Effect.Fade(menu, {duration: 0.1});
		}

		if(event) {
			Event.stop(event);
		}
	},

	/**
	 * displays the menu and does the AJAX call to the TYPO3 backend
	 */
	updateMenu: function() {
		this.toolbarItemIcon.src = 'gfx/spinner.gif';
		new Ajax.Updater(
			this.menu,
			this.ajaxScript, {
				parameters: {
					ajaxID:  'tx_sitemgr::searchCustomer',
					customer:Ext.getCmp('sitemgr_form_customer').getValue()
				},
				onComplete: function(xhr) {
					this.toolbarItemIcon.src = this.origToolbarItemIcon;
				}.bind(this)
			}
		);
	},
	openSite:function(uid) {
		if(!top.Ext.getCmp('typo3-pagetree-tree')) {
			jump('../typo3conf/ext/templavoila/mod1/index.php?id='+uid,'web_txtemplavoilaM1','web');
			new Ext.util.DelayedTask(function() {
				if (top.content.nav_frame) {
					top.content.nav_frame.location.href = 'alt_db_navframe.php?setTempDBmount='+uid;
				}
			}).delay(500);
		} else {
			TYPO3.Backend.ModuleMenu.App.showModule('tx_templavoila_cm1');
			TYPO3.Backend.NavigationContainer.PageTree.select(uid);
			TYPO3.Backend.NavigationContainer.PageTree.getTree().getSelectionModel().getSelectedNode().fireEvent('click');
		}
	},
	openManagement:function(uid) {
		jump('mod.php?M=tx_sitemgr_mod1&id='+uid,'tx_sitemgr_mod1','web');
		if(!Ext.getCmp('typo3-pagetree-tree')) {
			new Ext.util.DelayedTask(function() {
				if (top.content.nav_frame) {
					top.content.nav_frame.location.href = 'alt_db_navframe.php?setTempDBmount='+uid;
				}
			}).delay(500);
		} else {
			TYPO3.Backend.ModuleMenu.App.showModule('tx_sitemgr_mod1');
			TYPO3.Backend.NavigationContainer.PageTree.select(uid);
			//TYPO3.Backend.NavigationContainer.PageTree.getTree().getSelectionModel().getSelectedNode().fireEvent('click');
		}
	}
});

var TYPO3BackendSitemgr = new sitemgrCustomerSelector();
