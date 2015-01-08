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
		var userForm = new Ext.Window({
			title:TYPO3.lang.SitemgrBeUser_action_editUser,
			modal:true,
			layout:'form',
			id:'newUserForm',
			closeAction :'hide',
			border:false,
			width:400,
			height:400,
			tbar:[
				{
					tooltip:TYPO3.lang.SitemgrBeUser_action_saveUser,
					iconCls:'t3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-save-close',
					handler:function() {
						form = Ext.getCmp('newUserForm').get(0).getForm();
						form.submit({
							waitMsg: TYPO3.lang.SitemgrBeUser_action_newUser,
							params: {
								module:'sitemgr_beuser',
								fn    :'addOrUpdateUser',
								args  :{
									uid:TYPO3.settings.sitemgr.uid,
									cid:TYPO3.settings.sitemgr.customerId
								}
							},
							success: function(f,a){
								Ext.getCmp('newUserForm').hide();
								//Ext.Msg.alert('Success', 'It worked');
								Ext.getCmp('userGrid').getStore().reload();
							}
						});
					}
				}
			],
			items:[
				{
					xtype:'form',
					border:false,
					api:{
						load:TYPO3.sitemgr.tabs.dispatch,
						submit:TYPO3.sitemgr.tabs.handleForm
					},
					defaults:{
						style:'margin:5px;'
					},
					paramOrder: 'module,fn,args',
					items:[
						{
							xtype:'fieldset',
							title:TYPO3.lang.SitemgrBeUser_field_userData,
							width:350,
							defaults: {
								width:200,
								msgTarget: 'side'
							},
							items:[
								{
									xtype:'hidden',
									value:TYPO3.settings.sitemgr.uid,
									name:'uid'
								},{
									xtype:'hidden',
									value:TYPO3.settings.sitemgr.customerId,
									name:'cid'
								},{
									fieldLabel: TYPO3.lang.SitemgrBeUser_field_userName,
									xtype:'textfield',
									name:'username',
									allowBlank:false
								},{
									fieldLabel: TYPO3.lang.SitemgrBeUser_field_password,
									xtype:'textfield',
									name:'password',
									emptyText:'******'
								},{
									fieldLabel: TYPO3.lang.SitemgrBeUser_field_disable,
									xtype:'checkbox',
									name:'disable'
								}
							]
						},{
							xtype:'fieldset',
							title:TYPO3.lang.SitemgrBeUser_field_userAdditionalData,
							width:350,
							defaults: {
								width:200,
								msgTarget: 'side'
							},
							items:[
								{
									fieldLabel: TYPO3.lang.SitemgrBeUser_field_userRealName,
									xtype:'textfield',
									name:'realName',
									allowBlank:false
								},{
									fieldLabel: TYPO3.lang.SitemgrBeUser_field_userEmail,
									xtype:'textfield',
									name:'email',
									vtype:'email',
									allowBlank:false
								}
							]
						},{
							xtype:'displayfield',
							html:TYPO3.lang.SitemgrBeUser_field_rightsHint,
							hideLabel:true,
							width:350,
							cls:'typo3-message message-information'
						}
					],
					success:function() {
						Ext.getCmp('newUserForm').hide();
					}
				}
			]
		});
	});

	Ext.onReady(function (){
		Ext.getCmp('Sitemgr_App_Tabs').add({
			title:TYPO3.lang.SitemgrBeUser_title,
			iconCls: 'beuser-tab-icon',
			layout:'vbox',
			disabled:!TYPO3.settings.sitemgr.user.isCustomerAdmin,
			layoutConfig: {
				padding:'0',
				align:'stretch'
			},
			defaults: {
				flex:1
			},
			items: [
				{
					//title:'TYPO3.lang.SitemgrBeUser_userGrid.title###',
					xtype:'pagedgrid',
					loadMask:true,
					flex:1.5,
					id:'userGrid',
					store:TYPO3.Sitemgr.CustomerApp.beuserStore,
					tbar:[
						{
							//text:'TYPO3.lang.SitemgrBeUser_action.newUser###',
							tooltip:TYPO3.lang.SitemgrBeUser_title+' '+TYPO3.lang.SitemgrBeUser_action_newUser,
							iconCls:'t3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-new',
							disabled:!TYPO3.settings.sitemgr.customerSelected || !TYPO3.settings.sitemgr.user.isCustomerAdmin,
							handler:function() {
								Ext.getCmp('newUserForm').get(0).getForm().load({
									waitMsg:TYPO3.lang.SitemgrBeUser_action_newUser,
									params:{
										module:'sitemgr_beuser',
										fn:    'getUser',
										args  :{
											uid:'0',
											cid:TYPO3.settings.sitemgr.customerId
										}
									},
									success:function() {
										Ext.getCmp('newUserForm').show();
									}
								});
							}
						},{
							//text:'TYPO3.lang.SitemgrBeUser_action.editUser###',
							tooltip:TYPO3.lang.SitemgrBeUser_title+' '+TYPO3.lang.SitemgrBeUser_action_editUser,
							iconCls:'t3-icon-actions t3-icon-actions-document t3-icon-document-open',
							disabled:!TYPO3.settings.sitemgr.user.isCustomerAdmin,
							handler:function() {
								var sm  = Ext.getCmp('userGrid').getSelectionModel();
								var sel = sm.getSelected();
								if(sm.hasSelection()) {
									if(sel.data.uid!='') {
										Ext.getCmp('newUserForm').get(0).getForm().load({
											waitMsg:TYPO3.lang.SitemgrBeUser_action_editUser,
											params:{
												module:'sitemgr_beuser',
												fn:    'getUser',
												args  :{
													uid:sel.data.uid,
													cid:TYPO3.settings.sitemgr.customerId
												}
											},
											success:function() {
												Ext.getCmp('newUserForm').show();
											}
										});
									}
								}
							}
						},{
							//text:'TYPO3.lang.SitemgrBeUser_action.deleteUser###',
							tooltip:TYPO3.lang.SitemgrBeUser_title+' '+TYPO3.lang.SitemgrBeUser_action_deleteUser,
							iconCls:'t3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-delete',
							disabled:!TYPO3.settings.sitemgr.user.isCustomerAdmin,
							handler:function() {
								var sm  = Ext.getCmp('userGrid').getSelectionModel();
								var sel = sm.getSelected();
								if(sm.hasSelection()) {
									if(sel.data.uid!='') {
										Ext.Msg.show({
											title:TYPO3.lang.SitemgrBeUser_action_deleteUser+'?',
											msg:  TYPO3.lang.SitemgrBeUser_action_deleteUser+'? <br> - '+sel.data.username,
											buttons: Ext.Msg.YESNO,
											fn: function(btn) {
												if(btn=='yes') {
													TYPO3.sitemgr.tabs.dispatch(
														'sitemgr_beuser',
														'deleteUser',
														sel.data.uid+':'+TYPO3.settings.sitemgr.customerId,
														function() {
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
						},{
							//text:'TYPO3.lang.SitemgrBeUser_action.usersRightsOverview###',
							tooltip:TYPO3.lang.SitemgrBeUser_action_usersRightsOverview,
							iconCls:'t3-icon t3-icon-actions t3-icon-actions-document t3-icon-pagetree-backend-user',
							disabled:!TYPO3.settings.sitemgr.user.isCustomerAdmin,
							handler:function() {
								if(!Ext.getCmp('beUserRightsWindow')) {
									cols = new Array(
										{
											id: 'uid',
											header: TYPO3.lang.SitemgrBeUser_field_pid,
											dataIndex: 'uid',
											width:50,
											hidden:true
										},{
											id: 'title',
											header: TYPO3.lang.SitemgrBeUser_field_title,
											dataIndex:'title',
											width:400
										}
									);
									fields  = new Array(
										'uid',
										'title'
									);
									for(i=0;i<Ext.getCmp('userGrid').getStore().getCount();i++) {
										fields.push(
											Ext.getCmp('userGrid').getStore().getAt(i).data.username
										);
										cols.push({
											header       : '<span style="height:100px;display:block;"><span style="filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);-webkit-transform:rotate(90deg);-moz-transform:rotate(90deg);display:block;">'+Ext.getCmp('userGrid').getStore().getAt(i).data.username+'</span></span>',
											dataIndex    : Ext.getCmp('userGrid').getStore().getAt(i).data.username,
											tooltip      : Ext.getCmp('userGrid').getStore().getAt(i).data.username+' - '+Ext.getCmp('userGrid').getStore().getAt(i).data.realName,
											fixed        : true,
											renderer     :  function(value,metaData) {
												if(value) {
													metaData.css = 't3-icon message-ok';
												}
												return '';
											}
										});
									}
									win = new Ext.Window({
										id        : 'beUserRightsWindow',
										title     : TYPO3.lang.SitemgrBeUser_action_usersRightsOverview,
										modal     : true,
										border    : false,
										closeAction : 'close', 
										maximized:true,
										layout:'fit',
										layoutConfig: {
											margin: 5
										},
										margin:10,
										items:[
											{
												id:'userrightsGrid',
												xtype:'grid',
												layout:'fit',
												loadMask:true,
												columnLines:true,
												stripeRows:true,
												border:false,
												store:new Ext.data.DirectStore({
													storeId:'beuserRightsStore',
													autoLoad:false,
													directFn:TYPO3.sitemgr.tabs.dispatch,
													paramsAsHash: false,
													paramOrder:'module,fn,args',
													baseParams:{
														module:'sitemgr_beuser',
														fn    :'getUsersRights',
														args  :TYPO3.settings.sitemgr.customerId
													},
													root:'rows',
													idProperty: 'uid',
													fields: fields
												}),
												cm   :new Ext.grid.ColumnModel({
													defaults: {
														width   : 28,
														sortable: false,
														menuDisabled :true
													},
													columns: cols
												}),
												sm:new Ext.grid.RowSelectionModel({
													singleSelect:true
												}),
												viewConfig: {
											    	//forceFit: true
											    }
											}
										]
									});
								} else {
									win = Ext.getCmp('beUserRightsWindow');
								}
								win.show();
								Ext.getCmp('userrightsGrid').getStore().load();
								
							}
						},{
							//text:'TYPO3.lang.SitemgrBeUser_action.switchUser###',
							tooltip:TYPO3.lang.SitemgrBeUser_action_switchUser,
							iconCls:'t3-icon t3-icon-actions t3-icon-actions-system t3-icon-system-backend-user-emulate',
							disabled:!TYPO3.settings.sitemgr.user.isGlobalAdmin,
							handler:function() {
								var sm  = Ext.getCmp('userGrid').getSelectionModel();
								var sel = sm.getSelected();
								if(sm.hasSelection() && sm.hasSelection()) {
									goToModule('system_BeuserTxBeuser','','SwitchUser=' + sel.data.uid + '&switchBackUser=1');
									window.setTimeout(
										function(){
											window.location.reload()
										},
										1000
									);
								}
							}
						}
					],
					sm: new Ext.grid.RowSelectionModel({
						singleSelect:true,
						listeners: {
							rowselect: function(sm, rowIndex, record){
								if(record.data.admin==1) {
									Ext.getCmp('userGrantsGrid').getStore().removeAll();
									Ext.getCmp('userGrantsGrid').disable();
								} else {
									Ext.getCmp('userGrantsGrid').getStore().load({
										params:{
											args:record.data.uid
										}
									});
									Ext.getCmp('userGrantsGrid').enable();
								}
							}
						}
					}),
					autoExpandColumn:'realName',
					colModel: new Ext.grid.ColumnModel({
							defaults: {
							sortable: true
						},
						columns: [
							{id: 'uid', header: 'ID', width: 200, sortable: true, dataIndex: 'uid',hidden:true},
							{header: TYPO3.lang.SitemgrBeUser_grid_admin, dataIndex: 'admin', width:30, fixed:true, renderer:function(val){
								if(val != 1) {
									return '<span class="t3-icon t3-icon-status t3-icon-status-user t3-icon-user-backend"></span>';
								} else {
									return '<span class="t3-icon t3-icon-status t3-icon-status-user t3-icon-user-admin"></span>'
								}
							}},
							{header: TYPO3.lang.SitemgrBeUser_grid_username    , dataIndex: 'username',width:150},
							{header: TYPO3.lang.SitemgrBeUser_grid_realname    , dataIndex: 'realName'},
							{header: TYPO3.lang.SitemgrBeUser_grid_email       , dataIndex: 'email'},
							{header: TYPO3.lang.SitemgrBeUser_grid_customerName, dataIndex: 'customerName'}
							
						]
					}),
					viewConfig: {
						forceFit: true
					}
				},{
					xtype:'grid',
					title:TYPO3.lang.SitemgrBeUser_userAccessGrid_title,
					loadMask:true,
					id:'userGrantsGrid',
					disabled:true,
					store:new Ext.data.DirectStore({
						storeId:'beUserAccessStore',
						autoLoad:false,
						directFn:TYPO3.sitemgr.tabs.dispatch,
						paramsAsHash: false,
						paramOrder:'module,fn,args',
						baseParams:{
							module:'sitemgr_beuser',
							fn    :'getAccessForUser',
							args  :TYPO3.settings.sitemgr.uid
						},
						idProperty: 'uid',
						fields: [{
					        name: 'uid',
					        type: 'int'
					    },
					        'username',
					        'realName',
					        'admin',
					        'path',
					        'right',
					        'pid'
					    ]
					}),
					tbar:[
						{
							//text:'TYPO3.lang.SitemgrBeUser_action.addRight###',
							tooltip:TYPO3.lang.SitemgrBeUser_userAccessGrid_title+' '+TYPO3.lang.SitemgrBeUser_action_addRight,
							iconCls:'t3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-new',
							disabled:!TYPO3.settings.sitemgr.user.isCustomerAdmin,
							handler:function() {
								var sm  = Ext.getCmp('userGrid').getSelectionModel();
								var sel = sm.getSelected();
								win = new Ext.Window({
									title:TYPO3.lang.SitemgrBeUser_action_addRight,
									modal:true,
									layout:'form',
									id:'addRightForm',
									width    : 400,
									tbar:[
										{
											tooltip:TYPO3.lang.SitemgrBeUser_action_saveRight,
											iconCls:'t3-icon t3-icon-actions t3-icon-actions-document t3-icon-document-save-close',
											handler:function() {
												form = Ext.getCmp('addRightForm').get(0).getForm();
												form.submit({
													waitMsg: TYPO3.lang.SitemgrBeUser_action_addRight,
													params: {
														module:'sitemgr_beuser',
														fn    :'addGrant',
														args  :TYPO3.settings.sitemgr.uid
													},
													success: function(f,a){
														Ext.getCmp('addRightForm').hide();
														Ext.getCmp('addRightForm').destroy();
														Ext.getCmp('userGrantsGrid').getStore().reload();
													}
												});
											}
										}
									],
									items:[
										{
											xtype:'form',
											border:false,
											api:{
												submit:TYPO3.sitemgr.tabs.handleForm
											},
											padding:5,
											defaults: {
												msgTarget: 'side'
											},
											items:[
												{
													xtype:'fieldset',
													title:TYPO3.lang.SitemgrBeUser_action_addRight,
													items:[
														{
															xtype:'hidden',
															value:TYPO3.settings.sitemgr.uid,
															name:'uid',
															fieldLabel :'uid'
														},{
															xtype:'hidden',
															value:sel.data.uid,
															fieldLabel :'userId',
															name:'userID'
														},{
															xtype:'hidden',
															fieldLabel: TYPO3.lang.SitemgrBeUser_action_addRight,
															name:'grantPid',
															id:'grantPid',
															width: 250
														},{
															xtype:'treepanel',
															fieldLabel:TYPO3.lang.SitemgrBeUser_action_addRight,
															width: 250,
															height:300,
															autoScroll:true,
															loader: new Ext.tree.TreeLoader({
																directFn:TYPO3.sitemgr.tabs.getSubpages
															}),
															root: new Ext.tree.AsyncTreeNode({
													            expanded: true,
													            id  :TYPO3.settings.sitemgr.customerRootPid,
													            text:TYPO3.settings.sitemgr.customerRootName,
													            leaf:false,
													            expandable:true
													        }),
													        listeners: {
													            click: function(n) {
																	Ext.getCmp('grantPid').setValue(n.attributes.id);
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
							//text:'TYPO3.lang.SitemgrBeUser_action.deleteRight###',
							tooltip:TYPO3.lang.SitemgrBeUser_userAccessGrid_title+' '+TYPO3.lang.SitemgrBeUser_action_deleteRight,
							iconCls:'t3-icon t3-icon-actions t3-icon-actions-edit t3-icon-edit-delete',
							disabled:!TYPO3.settings.sitemgr.user.isCustomerAdmin,
							handler:function() {
								var sm  = Ext.getCmp('userGrantsGrid').getSelectionModel();
								var sel = sm.getSelected();
								if(sm.hasSelection()) {
									if(sel.data.uid!='') {
										Ext.Msg.show({
											title: TYPO3.lang.SitemgrBeUser_action_deleteRight+'?',
											msg:   TYPO3.lang.SitemgrBeUser_action_deleteRight+'?',
											buttons: Ext.Msg.YESNO,
											fn: function(btn) {
												if(btn=='yes') {
												TYPO3.sitemgr.tabs.dispatch(
														'sitemgr_beuser',
														'deleteGrant',
														{
															pid :sel.data.pid,
															user:Ext.getCmp('userGrid').getSelectionModel().getSelected().data.uid,
															uid: TYPO3.settings.sitemgr.uid
														},
														function() {
															Ext.getCmp('userGrantsGrid').getStore().reload();
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
					sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
					autoExpandColumn:'path',
					colModel: new Ext.grid.ColumnModel({
						defaults: {
							sortable: true
						},
						columns: [
							{
								id: 'uid',
								header: 'ID',
								width: 200,
								sortable: true,
								dataIndex: 'uid',
								hidden:true
							},{
								header: TYPO3.lang.SitemgrBeUser_grid_admin,
								dataIndex: 'admin',
								width:30,
								fixed:true,
								renderer:function(val){
									if(val != 1) {
										return '<span class="t3-icon t3-icon-status t3-icon-status-user t3-icon-user-backend"></span>';
									} else {
										return '<span class="t3-icon t3-icon-status t3-icon-status-user t3-icon-user-admin"></span>'
									}
								}
							},{
								header: TYPO3.lang.SitemgrBeUser_grid_username,
								dataIndex: 'username',
								width: 250,
								fixed:true
							},{
								header: TYPO3.lang.SitemgrBeUser_grid_path,
								dataIndex: 'path',
								renderer: function(val, metaData, record, rowIndex, colIndex, store) {
									metaData.attr = 'ext:qtip="' + TYPO3.lang.SitemgrBeUser_grid_path + ':' + val + '"';
									return val;
								}
							},{
								header: TYPO3.lang.SitemgrBeUser_grid_right,
								dataIndex: 'right',
								width: 75,
								fixed:true
							}
						]
					}),
					viewConfig: {
						forceFit: true
					}
				}
			]
		});
	});