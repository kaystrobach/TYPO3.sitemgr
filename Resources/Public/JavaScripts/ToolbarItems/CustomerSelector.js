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
Ext.ns('TYPO3.settings.sitemgr');
TYPO3.settings.sitemgr.uid = 0;


var sitemgrCustomerSelector;
sitemgrCustomerSelector = Class.create({
	ajaxScript:'ajax.php',
	menu:null,
	toolbarItemIcon:null,

	/**
	 * registers for resize event listener and executes on DOM ready
	 */
	initialize:function () {
		Event.observe(window, 'resize', this.positionMenu);

		Ext.onReady(function () {
			this.toolbarItemIcon = $$('#tx-sitemgr-menu .toolbar-item img.t3-icon')[0];
			this.origToolbarItemIcon = this.toolbarItemIcon.src;
			this.ajaxScript = top.TS.PATH_typo3 + this.ajaxScript; // can't be initialized earlier

			Event.observe($$('#tx-sitemgr-menu .toolbar-item')[0], 'click', this.toggleMenu);


		}, this);
	},

	/**
	 * toggles the visibility of the menu and places it under the toolbar icon
	 */
	toggleMenu:function (event) {
		TYPO3.Sitemgr.CustomerApp.customerStore.clearFilter();
		TYPO3.Sitemgr.CustomerApp.beuserStore.clearFilter();
		win = new Ext.Window(
				{
					modal:true,
					layout:'border',
					width:800,
					height:400,
					closeAction:'close',
					listeners:{
						afterrender:function (cmp) {
							window.setTimeout(function () {
								Ext.getCmp('customerAndUserSearchField').focus();
							}, 400);
						}
					},
					items:[
						{
							region:'north',
							layout:'fit',
							height:30,
							items:[
								{
									xtype:'textfield',
									id:'customerAndUserSearchField',
									fieldLabel:'Test',
									enableKeyEvents:true,
									listeners:{
										afterrender: function(cmp) {
											cmp.on(
												'keyup',
												function() {
													TYPO3.Sitemgr.CustomerApp.customerStore.reload(
														{
															params:{
																args: {
																	filterField: 'title',
																	filterValue: cmp.getValue()
																}
															}
														}
													);
													TYPO3.Sitemgr.CustomerApp.beuserStore.reload(
														{
															params:{
																args: {
																	filterField: 'username',
																	filterValue: cmp.getValue()
																}
															}
														}
													);
												},
												cmp,
												{buffer: 500}
											);
										}
									}
								}
							]
						},
						{
							region:'center',
							xtype:'grid',
							loadMask:true,
							id:'customerGrid',
							store:TYPO3.Sitemgr.CustomerApp.customerStore,
							flex:1,
							colModel:new Ext.grid.ColumnModel(
									{
										defaults:{
											sortable:true
										},
										columns:[
											{id:'uid', header:'ID', width:200, sortable:true, dataIndex:'uid', hidden:true},
											{header:TYPO3.lang.SitemgrCustomer_grid_type, width:30, fixed:true, sortable:false, renderer:function (val) {
												return '<span class="t3-icon t3-icon-tcarecords t3-icon-tcarecords-tx_sitemgr_customer t3-icon-tx_sitemgr_customer-default"></span>';
											}},
											{header:TYPO3.lang.SitemgrCustomer_grid_customer, dataIndex:'title'},
											{header:TYPO3.lang.SitemgrCustomer_grid_users, dataIndex:'users', sortable:false}
										]
									}
							),
							listeners:{
								rowclick:function (grid, rowIndex, e) {
									var sm = grid.getSelectionModel();
									var sel = sm.getSelected();
									if (sm.hasSelection() && sel.data.uid != '') {
										TYPO3.Sitemgr.CustomerApp.beuserStore.reload(
											{
												params:{
													args: {
														filterField: 'customerPid',
														filterValue: sel.data.pid
													}
												}
											}
										);
									}
								}
							},
							viewConfig:{
								forceFit:true
							},
							bbar:[
								{
									//text:'###LANG.action.editCustomer###',
									tooltip:TYPO3.lang.SitemgrCustomer_title + ' ' + TYPO3.lang.SitemgrCustomer_action_editCustomer,
									iconCls:'t3-icon t3-icon-tcarecords t3-icon-tcarecords-tx_sitemgr_customer t3-icon-tx_sitemgr_customer-default',
									handler:function () {
										var sm = Ext.getCmp('customerGrid').getSelectionModel();
										var sel = sm.getSelected();
										if (sm.hasSelection() && sel.data.uid != '') {
											window.open('alt_doc.php?returnUrl=close.html&edit[tx_sitemgr_customer][' + sel.data.uid + ']=edit', '', 'width=800,height=600');
										}
									}
								},
								{
									//text:'###LANG.action.editCustomer###',
									tooltip:TYPO3.lang.SitemgrCustomer_title + ' ' + TYPO3.lang.SitemgrCustomer_action_preview_page,
									iconCls:'t3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-pagemodule-open',
									handler:function () {
										var sm = Ext.getCmp('customerGrid').getSelectionModel();
										var sel = sm.getSelected();
										if (sm.hasSelection()) {
											if (sel.data.uid != '') {
												window.open('../index.php?id=' + sel.data.pid + '', '', 'maximized=yes');
											}
										}
									}
								},
								{
									//text:'###LANG.action.editCustomer###',
									tooltip:TYPO3.lang.SitemgrCustomer_title + ' ' + TYPO3.lang.SitemgrCustomer_action_open_management,
									iconCls:'t3-icon t3-icon-extensions t3-icon-extensions-sitemgr t3-icon-sitemgr-moduleicon',
									handler:function () {
										var sm = Ext.getCmp('customerGrid').getSelectionModel();
										var sel = sm.getSelected();
										if (sm.hasSelection() && sel.data.uid != '') {
											top.fsMod.recentIds.web = sel.data.pid;
											top.fsMod.navFrameHighlightedID.web = "pages" + sel.data.pid + "_0";		// For highlighting

											if (top.content && top.content.nav_frame && top.content.nav_frame.refresh_nav) {
												top.content.nav_frame.refresh_nav();
											}
											goToModule('web_SitemgrTxSitemgrMod1', 0, '');
										}
									}
								},
								{
									//text:'###LANG.action.editCustomer###',
									tooltip:TYPO3.lang.SitemgrCustomer_title + ' ' + TYPO3.lang.SitemgrCustomer_action_open_page_module,
									iconCls:'t3-icon t3-icon-extensions t3-icon-extensions-templavoila t3-icon-templavoila-type-fce',
									handler:function () {
										var sm = Ext.getCmp('customerGrid').getSelectionModel();
										var sel = sm.getSelected();
										if (sm.hasSelection() && sel.data.uid != '') {
											loadEditId(sel.data.pid);
										}
									}
								}

							]
						},
						{
							width:400,
							region:'east',
							xtype:'grid',
							loadMask:true,
							id:'userGrid',
							store:TYPO3.Sitemgr.CustomerApp.beuserStore,
							flex:1,
							colModel:new Ext.grid.ColumnModel(
									{
										defaults:{
											sortable:true
										},
										columns:[
											{id:'uid', header:'ID', width:200, sortable:true, dataIndex:'uid', hidden:true},
											{header:TYPO3.lang.SitemgrBeUser_grid_admin, dataIndex:'admin', width:30, fixed:true, renderer:function (val) {
												if (val != 1) {
													return '<span class="t3-icon t3-icon-status t3-icon-status-user t3-icon-user-backend"></span>';
												} else {
													return '<span class="t3-icon t3-icon-status t3-icon-status-user t3-icon-user-admin"></span>'
												}
											}},
											{header:TYPO3.lang.SitemgrBeUser_grid_username, dataIndex:'username'},
											{header:TYPO3.lang.SitemgrBeUser_grid_realname, dataIndex:'realname'},
											{header:TYPO3.lang.SitemgrBeUser_grid_customerName, dataIndex:'customerName'}
										]
									}
							),
							viewConfig:{
								forceFit:true
							},
							bbar:[
								{
									//text:'###LANG.action.editCustomer###',
									tooltip:TYPO3.lang.SitemgrBeUser_action_editUser,
									iconCls:'t3-icon t3-icon-status t3-icon-status-user t3-icon-user-backend',
									handler:function () {
										var sm = Ext.getCmp('userGrid').getSelectionModel();
										var sel = sm.getSelected();
										if (sm.hasSelection()) {
											if (sel.data.uid != '') {
												window.open('alt_doc.php?returnUrl=close.html&edit[be_users][' + sel.data.uid + ']=edit', '', 'width=800,height=600');
											}
										}
									}
								},
								{
									//text:'TYPO3.lang.SitemgrBeUser_action.switchUser###',
									tooltip:TYPO3.lang.SitemgrBeUser_action_switchUser,
									iconCls:'t3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-backend-user-emulate',
									handler:function () {
										var sm = Ext.getCmp('userGrid').getSelectionModel();
										var sel = sm.getSelected();
										if (sm.hasSelection()) {
											if (sel.data.uid != '') {
												goToModule('system_BeuserTxBeuser','','SwitchUser=' + sel.data.uid + '&switchBackUser=1')
												window.setTimeout(
													function(){
														window.location.reload()
													},
													1000
												);
											}
										}
										Ext.get(win.getEl()).mask(TYPO3.LLL.core.loadingIndicator);
									}
								}
							]
						}
					]
				}
		).show();
	},
	/**
	 * displays the menu and does the AJAX call to the TYPO3 backend
	 */
	updateMenu:function () {
		this.toolbarItemIcon.src = 'gfx/spinner.gif';
		new Ajax.Updater(
				this.menu,
				this.ajaxScript, {
					parameters:{
						ajaxID:'tx_sitemgr::searchCustomer',
						customer:Ext.getCmp('sitemgr_form_customer').getValue()
					},
					onComplete:function (xhr) {
						this.toolbarItemIcon.src = this.origToolbarItemIcon;
					}.bind(this)
				}
		);
	},
	openSite:function (uid) {
		if (!top.Ext.getCmp('typo3-pagetree-tree')) {
			jump('../typo3conf/ext/templavoila/mod1/index.php?id=' + uid, 'web_txtemplavoilaM1', 'web');
			new Ext.util.DelayedTask(function () {
				if (top.content.nav_frame) {
					top.content.nav_frame.location.href = 'alt_db_navframe.php?setTempDBmount=' + uid;
				}
			}).delay(500);
		} else {
			TYPO3.Backend.ModuleMenu.App.showModule('tx_templavoila_cm1');
			TYPO3.Backend.NavigationContainer.PageTree.select(uid);
			TYPO3.Backend.NavigationContainer.PageTree.getTree().getSelectionModel().getSelectedNode().fireEvent('click');
		}
	},
	openManagement:function (uid) {
		jump('mod.php?M=tx_sitemgr_mod1&id=' + uid, 'tx_sitemgr_mod1', 'web');
		if (!Ext.getCmp('typo3-pagetree-tree')) {
			new Ext.util.DelayedTask(function () {
				if (top.content.nav_frame) {
					top.content.nav_frame.location.href = 'alt_db_navframe.php?setTempDBmount=' + uid;
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
