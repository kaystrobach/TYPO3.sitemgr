/*******************************************************************************
 * Register Namespace
 * Initialize some vars
 ******************************************************************************/
	Ext.ns('TYPO3.Sitemgr.CustomerApp');

Ext.onReady(function (){

	TYPO3.Sitemgr.CustomerApp.customerStore = new Ext.data.DirectStore(
		{
			storeId      : 'customerStore',
			directFn     : TYPO3.sitemgr.tabs.dispatchPaged,
			paramsAsHash : false,
			autoLoad     : true,
			remoteSort   : true,
			paramOrder   : 'module,fn,args,start,limit,sort,dir',
			baseParams   : {
				module   : 'sitemgr_customer',
				fn       : 'getCustomers',
				args     : TYPO3.settings.sitemgr.uid,
				start    : 0,
				limit    : 25,
				sort     : 'title',
				dir      : 'ASC'
			},
			root         : 'rows',
			totalProperty: 'count',
			idProperty   : 'uid',
			fields       : [{
				name     : 'uid',
				type     : 'int'
			},
				'title',
				'pid',
				'users'
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
	TYPO3.Sitemgr.CustomerApp.customerStore.filter()
});