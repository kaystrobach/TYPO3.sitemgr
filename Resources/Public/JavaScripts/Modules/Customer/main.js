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
 * customer module
 * manage your customers 
 *
 * $Id$
 *
 * @author Kay Strobach <typo3@kay-strobach.de>
 */
	Ext.onReady(function (){
		Ext.getCmp('Sitemgr_App_Tabs').add({
			title   :TYPO3.lang.SitemgrCustomer_title,
			xtype   :'pagedgrid',
			iconCls : 'customer-tab-icon',
			hidden  : !TYPO3.settings.sitemgr.user.isGlobalAdmin,
			disabled: !TYPO3.settings.sitemgr.user.isGlobalAdmin,
			loadMask:true,
			id      :'customerGrid',
			store   :TYPO3.Sitemgr.CustomerApp.customerStore,
			tbar    :[
				{
					//text:'###LANG.action.newCustomer###',
					tooltip:TYPO3.lang.SitemgrCustomer_title+' '+TYPO3.lang.SitemgrCustomer_action_newCustomer,
					iconCls:'t3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-new',
					handler:function() {
						win = new Ext.Window({
							//title:'###LANG.action.newCustomer###',
							width:800,
							height:300,
							modal:true,
							layout:'form',
							id:'newCustomerForm',
							border:false,
							tbar:[
								{
									tooltip:TYPO3.lang.SitemgrCustomer_action_saveCustomer,
									iconCls:'t3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-save-close',
									handler:function() {
										form = Ext.getCmp('newCustomerForm').get(0).getForm();
										form.submit({
											waitMsg: TYPO3.lang.SitemgrCustomer_action_newCustomer,
											params: {
												module:'sitemgr_customer',
												fn    :'addCustomer',
												args  :TYPO3.settings.sitemgr.uid
											},
											success: function(f,a){
												Ext.getCmp('newCustomerForm').hide();
												Ext.getCmp('newCustomerForm').destroy();
												//Ext.Msg.alert('Success', 'It worked');
												Ext.getCmp('customerGrid').getStore().reload();
												Ext.getCmp('userGrid').getStore().reload();
											},
										});
									}
								}
							],
							items:[
								{
									xtype:'form',
									border:false,
									layout:'hbox',
									api:{
										submit:TYPO3.sitemgr.tabs.handleForm
									},
									defaults:{
										style:'margin:5px;'
									},
									items:[
										{
											xtype:'fieldset',
											title:TYPO3.lang.SitemgrCustomer_field_customerData,
											width:400,
											defaults: {
												msgTarget: 'side'
											},
											items:[
												{
													xtype:'hidden',
													value:TYPO3.settings.sitemgr.uid,
													name:'uid'
												},{
													fieldLabel: TYPO3.lang.SitemgrCustomer_field_customerName,
													xtype:'textfield',
													name:'customerName',
													width: 250
												},{
													fieldLabel: TYPO3.lang.SitemgrCustomer_field_customerEmail,
													xtype :'textfield',
													name  :'customerEmail',
													vtype :'email',
													width : 250
												},{
													fieldLabel: TYPO3.lang.SitemgrCustomer_field_password,
													xtype :'textfield',
													name  :'password',
													width : 250
												},{
													fieldLabel: TYPO3.lang.SitemgrCustomer_field_description,
													xtype :'textarea',
													name  :'description',
													width : 250,
													height: 50
												}
											]
										},{
											xtype:'fieldset',
											title:TYPO3.lang.SitemgrCustomer_field_customerSettings,
											items:[
												{
													fieldLabel: TYPO3.lang.SitemgrCustomer_field_createGroupFolder,
													xtype:'checkbox',
													name:'createGroupFolder',
													checked:true
												},{
													fieldLabel: TYPO3.lang.SitemgrCustomer_field_createUserFolder,
													xtype:'checkbox',
													name:'createUserFolder',
													checked:true
												},{
													fieldLabel: TYPO3.lang.SitemgrCustomer_field_copyCheck,
													xtype:'checkbox',
													name:'copyCheck',
													handler:function(field) {
														if(field.checked) {
															Ext.getCmp('customerCopyFrom').enable();
															Ext.getCmp('customerCopyFromTree').enable();
														} else {
															Ext.getCmp('customerCopyFrom').disable();
															Ext.getCmp('customerCopyFromTree').disable();
														}
													}
												},{
													xtype:'hidden',
													fieldLabel: TYPO3.lang.SitemgrCustomer_field_copyFrom,
													name:'customerCopyFrom',
													id:'customerCopyFrom',
													disabled:true,
													width: 250
												},{
													xtype:'treepanel',
													disabled:true,
													fieldLabel:TYPO3.lang.SitemgrCustomer_field_copyFrom,
													id:'customerCopyFromTree',
													width: 250,
													height:100,
													autoScroll:true,
													loader: new Ext.tree.TreeLoader({
														directFn:TYPO3.sitemgr.tabs.getSubpages
													}),
													root: new Ext.tree.AsyncTreeNode({
											            expanded: true,
											            id:'0',
											            text:'ROOT',
											            leaf:false,
											            expandable:true
											        }),
											        listeners: {
											            click: function(n) {
															Ext.getCmp('customerCopyFrom').setValue(n.attributes.id);
											            }
											        }
												}
											]
										}
									],
									success:function() {
										Ext.getCmp('newCustomerForm').close();
									}
								}
							]
						});
						win.show();
					}
				},{
					//text:'###LANG.action.editCustomer###',
					tooltip:TYPO3.lang.SitemgrCustomer_title+' '+TYPO3.lang.SitemgrCustomer_action_editCustomer,
					iconCls:'t3-icon-actions t3-icon-actions-document t3-icon-document-open',
					handler:function() {
						var sm  = Ext.getCmp('customerGrid').getSelectionModel();
						var sel = sm.getSelected();
						if(sm.hasSelection()) {
							if(sel.data.uid!='') {
								window.open('alt_doc.php?returnUrl=close.html&edit[tx_sitemgr_customer]['+sel.data.uid+']=edit','','width=600,height=600');
							}
						}
								
					}
				},{
					//text:'###LANG.action.deleteCustomer###',
					tooltip:TYPO3.lang.SitemgrCustomer_title+' '+TYPO3.lang.SitemgrCustomer_action_deleteCustomer,
					iconCls:'t3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-delete',
					handler:function() {
						var sm  = Ext.getCmp('customerGrid').getSelectionModel();
						var sel = sm.getSelected();
						if(sm.hasSelection()) {
							if(sel.data.uid!='') {
								Ext.Msg.show({
									title:TYPO3.lang.SitemgrCustomer_action_deleteCustomer+'?',
									msg: TYPO3.lang.SitemgrCustomer_action_deleteCustomer+'? <br> - '+sel.data.title,
									buttons: Ext.Msg.YESNO,
									fn: function(btn) {
										if(btn=='yes') {
											TYPO3.sitemgr.tabs.dispatch(
												'sitemgr_customer',
												'deleteCustomer',
												sel.data.uid,
												function() {
													Ext.getCmp('customerGrid').getStore().reload();
													Ext.getCmp('userGrid').getStore().reload();
												}
											);
										}
									},
									icon: Ext.MessageBox.QUESTION
								});
								
							}
						}
								
					}
				}
			],
			colModel: new Ext.grid.ColumnModel({
					defaults: {
					sortable: true
				},
				columns: [
					{id: 'uid', header: 'ID', width: 200, sortable: true, dataIndex: 'uid',hidden:true},
					{header: TYPO3.lang.SitemgrCustomer_grid_type, width:30,fixed:true,sortable:false,renderer:function(val){
						return '<span class="t3-icon t3-icon-tcarecords t3-icon-tcarecords-tx_sitemgr_customer t3-icon-tx_sitemgr_customer-default"></span>';
					}},
					{header: TYPO3.lang.SitemgrCustomer_grid_customer, dataIndex: 'title'},
					{header: TYPO3.lang.SitemgrCustomer_grid_users   , dataIndex: 'users',sortable:false}
				]
			}),
			viewConfig: {
				forceFit: true
			}
		});
	});