/*******************************************************************************
 * Register Namespace
 * Initialize some vars
 ******************************************************************************/
	Ext.ns('TYPO3.Sitemgr.CustomerApp');

Ext.onReady(function (){

	TYPO3.Sitemgr.CustomerApp.beuserStore = new Ext.data.DirectStore(
		{
			storeId      : 'userStore',
			directFn     : TYPO3.sitemgr.tabs.dispatchPaged,
			paramsAsHash : false,
			autoLoad     : true,
			remoteSort   : true,
			paramOrder   : 'module,fn,args,start,limit,sort,dir',
			baseParams   : {
				module   : 'sitemgr_beuser',
				fn       : 'getUsers',
				args     : {
					uid: TYPO3.settings.sitemgr.uid
				},
				start    : 0,
				limit    : 25,
				sort     : 'username',
				dir      : 'ASC'
			},
			root         : 'rows',
			totalProperty: 'count',
			idProperty   : 'uid',
			fields: [
				{
					name: 'uid',
					type: 'int'
				},
				'username',
				'realName',
				'admin',
				'email',
				'customerName'
			],
			listeners    : {
				load     : function() {
					if(TYPO3.settings.sitemgr.customerId!=0) {
						if(Ext.getCmp('customerGrid')) {
							records = [Ext.getCmp('customerGrid').getStore().getById(TYPO3.settings.sitemgr.customerId)];
							Ext.getCmp('customerGrid').getSelectionModel().selectRecords(records,false)
						}
					}
				}
			}
		}
	);
	TYPO3.Sitemgr.CustomerApp.beuserStore.filter()
});