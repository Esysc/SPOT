/**
 * backbone model definitions for SPOT
 */

/**
 * Use emulated HTTP if the server doesn't support PUT/DELETE or application/json requests
 */
Backbone.emulateHTTP = false;
Backbone.emulateJSON = false;

var model = {};

/**
 * long polling duration in miliseconds.  (5000 = recommended, 0 = disabled)
 * warning: setting this to a low number will increase server load
 */
model.longPollDuration = 30000;

/**
 * whether to refresh the collection immediately after a model is updated
 */
model.reloadCollectionOnModelUpdate = true;


/**
 * a default sort method for sorting collection items.  this will sort the collection
 * based on the orderBy and orderDesc property that was used on the last fetch call
 * to the server. 
 */
model.AbstractCollection = Backbone.Collection.extend({
    totalResults: 0,
    totalPages: 0,
    currentPage: 0,
    pageSize: 0,
    orderBy: '',
    orderDesc: false,
    lastResponseText: null,
    lastRequestParams: null,
    collectionHasChanged: true,
    /**
     * fetch the collection from the server using the same options and 
     * parameters as the previous fetch
     */
    refetch: function() {
        this.fetch({data: this.lastRequestParams})
    },
    /* uncomment to debug fetch event triggers
     fetch: function(options) {
     this.constructor.__super__.fetch.apply(this, arguments);
     },
     // */

    /**
     * client-side sorting baesd on the orderBy and orderDesc parameters that
     * were used to fetch the data from the server.  Backbone ignores the
     * order of records coming from the server so we have to sort them ourselves
     */
    comparator: function(a, b) {

        var result = 0;
        var options = this.lastRequestParams;

        if (options && options.orderBy) {

            // lcase the first letter of the property name
            var propName = options.orderBy.charAt(0).toLowerCase() + options.orderBy.slice(1);
            var aVal = a.get(propName);
            var bVal = b.get(propName);

            if (isNaN(aVal) || isNaN(bVal)) {
                // treat comparison as case-insensitive strings
                aVal = aVal ? aVal.toLowerCase() : '';
                bVal = bVal ? bVal.toLowerCase() : '';
            } else {
                // treat comparision as a number
                aVal = Number(aVal);
                bVal = Number(bVal);
            }

            if (aVal < bVal) {
                result = options.orderDesc ? 1 : -1;
            } else if (aVal > bVal) {
                result = options.orderDesc ? -1 : 1;
            }
        }

        return result;

    },
    /**
     * override parse to track changes and handle pagination
     * if the server call has returned page data
     */
    parse: function(response, options) {

        // the response is already decoded into object form, but it's easier to
        // compary the stringified version.  some earlier versions of backbone did
        // not include the raw response so there is some legacy support here
        var responseText = options && options.xhr ? options.xhr.responseText : JSON.stringify(response);
        this.collectionHasChanged = (this.lastResponseText != responseText);
        this.lastRequestParams = options ? options.data : undefined;

        // if the collection has changed then we need to force a re-sort because backbone will
        // only resort the data if a property in the model has changed
        if (this.lastResponseText && this.collectionHasChanged)
            this.sort({silent: true});

        this.lastResponseText = responseText;

        var rows;

        if (response.currentPage) {
            rows = response.rows;
            this.totalResults = response.totalResults;
            this.totalPages = response.totalPages;
            this.currentPage = response.currentPage;
            this.pageSize = response.pageSize;
            this.orderBy = response.orderBy;
            this.orderDesc = response.orderDesc;
        } else {
            rows = response;
            this.totalResults = rows.length;
            this.totalPages = 1;
            this.currentPage = 1;
            this.pageSize = this.totalResults;
            this.orderBy = response.orderBy;
            this.orderDesc = response.orderDesc;
        }

        return rows;
    }
});
/**
 * 
 * @type @exp;Backbone@pro;Model@call;extend
 * Pendings Backbone Model
 * 
 */


/*
 * Get all new orders from productiondb
 */



model.TblstoredordersModel = Backbone.Model.extend({
    urlRoot: '../proddb/api/tblstoredorderses?Origin=SP',
    idAttribute: 'id',
    id: '',
    user: '',
    object: '',
    salesorder: '',
    creationdate: '',
    origin: '',
    version: '',
    status: '',
    message: '',
    sysprodactor: '',
    releasename: '',
    pstartdate: '',
    penddate: '',
    programmanager: '',
    customeracr: '',
    defaults: {
        'id': null,
        'user': '',
        'object': '',
        'salesorder': '',
        'creationdate': '',
        'origin': '',
        'version': '',
        'status': '',
        'message': '',
        sysprodactor: '',
        releasename: '',
        pstartdate: '',
        penddate: '',
        programmanager: '',
        customeracr: ''
    }
});

/**
 * Tblstoredorders Backbone Collection
 */
model.TblstoredordersCollection = model.AbstractCollection.extend({
    url: '../proddb/api/tblstoredorderses?Origin=SP',
    model: model.TblstoredordersModel
});


/**
 * Configtemplate Backbone Model
 */
model.ConfigtemplateModel = Backbone.Model.extend({
	urlRoot: 'api/configtemplate',
	idAttribute: 'versionId',
	versionId: '',
	configTarget: '',
	configTemplate: '',
        targetName: '',
        timeStamp: '',

	defaults: {
		'versionId': null,
		'configTarget': '',
		'configTemplate': '',
                timeStamp: '',

	}
});

/**
 * Configtemplate Backbone Collection
 */
model.ConfigtemplateCollection = model.AbstractCollection.extend({
	url: 'api/configtemplates',
	model: model.ConfigtemplateModel
});

/**
 * Customconfig Backbone Model
 */
model.CustomconfigModel = Backbone.Model.extend({
	urlRoot: 'api/customconfig',
	idAttribute: 'configId',
	configId: '',
	salesorder: '',
	configTarget: '',
	configContent: '',
	timeStamp: '',
	defaults: {
		'configId': null,
		'salesorder': '',
		'configTarget': '',
		'configContent': '',
		'timeStamp': ''
	}
});

/**
 * Customconfig Backbone Collection
 */
model.CustomconfigCollection = model.AbstractCollection.extend({
	url: 'api/customconfigs',
	model: model.CustomconfigModel
});


/**
 * Networkequipment Backbone Model
 */
model.NetworkequipmentModel = Backbone.Model.extend({
	urlRoot: 'api/networkequipment',
	idAttribute: 'equipId',
	equipId: '',
	equipModel: '',
	method: '',
        methodname: '',
	defaults: {
		'equipId': null,
		'equipModel': '',
		'method': ''
	}
});

/**
 * Networkequipment Backbone Collection
 */
model.NetworkequipmentCollection = model.AbstractCollection.extend({
	url: 'api/networkequipments',
	model: model.NetworkequipmentModel
});

/**
 * Dhcpbootpinv Backbone Model
 */
model.DhcpbootpinvModel = Backbone.Model.extend({
    urlRoot: 'api/dhcpbootpinv',
    idAttribute: 'salesorder',
    salesorder: '',
    data: '',
    status: '',
    timestamps: '',
    message: '',
    creator: '',
    dwprocessed: '',
    defaults: {
        'salesorder': null,
        'data': '',
        'status': '',
        'timestamps': '',
        'message': '',
        'creator': '',
        'dwprocessed': ''
    }
});

/**
 * Dhcpbootpinv Backbone Collection
 */
model.DhcpbootpinvCollection = model.AbstractCollection.extend({
    url: 'api/dhcpbootpinvs',
    model: model.DhcpbootpinvModel
});


/**
 * Provisionningos Backbone Model
 */
model.ProvisionningosModel = Backbone.Model.extend({
    urlRoot: 'api/provisionningos',
    idAttribute: 'sname',
    sname: '',
    defaults: {
        'sname': null
    }
});

/**
 * Provisionningos Backbone Collection
 */
model.ProvisionningosCollection = model.AbstractCollection.extend({
    url: 'api/provisionningoss',
    model: model.ProvisionningosModel
});


/**
 * Events Backbone Model
 */
model.EventsModel = Backbone.Model.extend({
    urlRoot: 'api/events',
    idAttribute: 'id',
    id: '',
    title: '',
    content: '',
    userid: '',
    date: '',
    defaults: {
        'id': null,
        'title': '',
        'content': '',
        'userid': '',
        'date': ''
    }
});

/**
 * Events Backbone Collection
 */
model.EventsCollection = model.AbstractCollection.extend({
    url: 'api/eventses',
    model: model.EventsModel
});


/**
 * Eventcategory Backbone Model
 */
model.EventcategoryModel = Backbone.Model.extend({
    urlRoot: 'api/eventcategory',
    idAttribute: 'category',
    category: '',
    description: '',
    defaults: {
        'category': null,
        'description': ''
    }
});

/**
 * Eventcategory Backbone Collection
 */
model.EventcategoryCollection = model.AbstractCollection.extend({
    url: 'api/eventcategories',
    model: model.EventcategoryModel
});

/**
 * Executionflagcodes Backbone Model
 */
model.ExecutionflagcodesModel = Backbone.Model.extend({
    urlRoot: 'api/executionflagcodes',
    idAttribute: 'executionflag',
    executionflag: '',
    description: '',
    defaults: {
        'executionflag': null,
        'description': ''
    }
});

/**
 * Executionflagcodes Backbone Collection
 */
model.ExecutionflagcodesCollection = model.AbstractCollection.extend({
    url: 'api/executionflagcodeses',
    model: model.ExecutionflagcodesModel
});

/**
 * Jobtostart Backbone Model
 */
model.JobtostartModel = Backbone.Model.extend({
    urlRoot: 'api/jobtostart',
    idAttribute: 'scriptid',
    scriptid: '',
    salesorder: '',
    rack: '',
    shelf: '',
    clientaddress: '',
    arguments: '',
    exesequence: '',
    scripttarget: '',
    scriptname: '',
    scriptcontent: '',
    interpreter: '',
    version: '',
    returncode: '',
    returnstdout: '',
    returnstderr: '',
    executionflag: '',
    exectime: '',
    defaults: {
        'scriptid': null,
        'salesorder': '',
        'rack': '',
        'shelf': '',
        'clientaddress': '',
        'arguments': '',
        'exesequence': '',
        'scripttarget': '',
        'scriptname': '',
        'scriptcontent': '',
        'interpreter': '',
        'version': '',
        'returncode': '',
        'returnstdout': '',
        'returnstderr': '',
        'executionflag': '',
        'exectime': ''
    }
});

/**
 * Jobtostart Backbone Collection
 */
model.JobtostartCollection = model.AbstractCollection.extend({
    url: 'api/jobtostarts',
    model: model.JobtostartModel
});

/**
 * Mediatype Backbone Model
 */
model.MediatypeModel = Backbone.Model.extend({
    urlRoot: 'api/mediatype',
    idAttribute: 'id',
    id: '',
    media: '',
    defaults: {
        'id': null,
        'media': ''
    }
});

/**
 * Mediatype Backbone Collection
 */
model.MediatypeCollection = model.AbstractCollection.extend({
    url: 'api/mediatypes',
    model: model.MediatypeModel
});

/**
 * Networks Backbone Model
 */
model.NetworksModel = Backbone.Model.extend({
    urlRoot: 'api/networks',
    idAttribute: 'salesorder',
    salesorder: '',
    name: '',
    ip: '',
    mask: '',
    vlanno: '',
    defaults: {
        'salesorder': null,
        'name': '',
        'ip': '',
        'mask': '',
        'vlanno': ''
    }
});

/**
 * Networks Backbone Collection
 */
model.NetworksCollection = model.AbstractCollection.extend({
    url: 'api/networkses',
    model: model.NetworksModel
});

/**
 * Notifications Backbone Model
 */
model.NotificationsModel = Backbone.Model.extend({
    urlRoot: 'api/notifications',
    idAttribute: 'id',
    id: '',
    date: '',
    eventcategory: '',
    title: '',
    content: '',
    attacheddata: '',
    userid: '',
    acknowledged: '',
    defaults: {
        'id': null,
        'date': '',
        'eventcategory': '',
        'title': '',
        'content': '',
        'attacheddata': '',
        'userid': '',
        'acknowledged': ''
    }
});

/**
 * Notifications Backbone Collection
 */
model.NotificationsCollection = model.AbstractCollection.extend({
    url: 'api/notificationses',
    model: model.NotificationsModel
});

/**
 * Orders Backbone Model
 */
model.OrdersModel = Backbone.Model.extend({
    urlRoot: 'api/orders',
    idAttribute: 'salesorder',
    salesorder: '',
    crmuid: '',
    pgm: '',
    ordertitle: '',
    heacronym: '',
    systemtype: '',
    snapavail: '',
    pstartdate: '',
    penddate: '',
    rstartdate: '',
    renddate: '',
    shippmentdate: '',
    status: '',
    polaroidexport: '',
    userid: '',
    commiteddate: '',
    moveorder: '',
    oracleorder: '',
    comments: '',
    defaults: {
        'salesorder': null,
        'crmuid': '',
        'pgm': '',
        'ordertitle': '',
        'heacronym': '',
        'systemtype': '',
        'snapavail': '',
        'pstartdate': new Date(),
        'penddate': new Date(),
        'rstartdate': new Date(),
        'renddate': new Date(),
        'shippmentdate': new Date(),
        'status': '',
        'polaroidexport': '',
        'userid': '',
        'commiteddate': '',
        'moveorder': '',
        'oracleorder': '',
        'comments': ''
    }
});

/**
 * Orders Backbone Collection
 */
model.OrdersCollection = model.AbstractCollection.extend({
    url: 'api/orderses',
    model: model.OrdersModel
});

/**
 * Orderslog Backbone Model
 */
model.OrderslogModel = Backbone.Model.extend({
    urlRoot: 'api/orderslog',
    idAttribute: 'id',
    id: '',
    salesorder: '',
    title: '',
    text: '',
    userid: '',
    date: '',
    defaults: {
        'id': null,
        'salesorder': '',
        'title': '',
        'text': '',
        'userid': '',
        'date': ''
    }
});

/**
 * Orderslog Backbone Collection
 */
model.OrderslogCollection = model.AbstractCollection.extend({
    url: 'api/orderslogs',
    model: model.OrderslogModel
});

/**
 * Provisioning Backbone Model
 */
model.ProvisioningModel = Backbone.Model.extend({
    urlRoot: 'api/provisioning',
    idAttribute: 'provisioningid',
    provisioningid: '',
    salesorder: '',
    rack: '',
    shelf: '',
    clientaddress: '',
    arguments: '',
    exesequence: '',
    scriptid: '',
    returncode: '',
    returnstdout: '',
    returnstderr: '',
    executionflag: '',
    logtime: '',
    exectime: '',
    scriptname: '',
    scriptcontent: '',
    remotecommandid: '',
    interpreter: '',
    version: '',
    defaults: {
        'provisioningid': null,
        'salesorder': '',
        'rack': '',
        'shelf': '',
        'clientaddress': '',
        'arguments': '',
        'exesequence': '',
        'scriptid': '',
        'returncode': '',
        'returnstdout': '',
        'returnstderr': '',
        'executionflag': '',
        'logtime': '',
        'exectime': '',
        'scriptname': '',
        'scriptcontent': '',
        'remotecommandid': '',
        'interpreter': '',
        'version': ''
    }
});

/**
 * Provisioning Backbone Collection
 */
model.ProvisioningCollection = model.AbstractCollection.extend({
    url: 'api/provisionings',
    model: model.ProvisioningModel
});

/**
 * Provisioningaction Backbone Model
 */
model.ProvisioningactionModel = Backbone.Model.extend({
    urlRoot: 'api/provisioningaction',
    idAttribute: 'actionid',
    actionid: '',
    salesorder: '',
    codeapc: '',
    rack: '',
    shelf: '',
    hostname: '',
    timezone: '',
    posixtz: '',
    wintz: '',
    dststartday: '',
    dststopday: '',
    dststarth: '',
    dststoph: '',
    os: '',
    image: '',
    boot: '',
    ip: '',
    netmask: '',
    gateway: '',
    iloip: '',
    ilonm: '',
    ilogw: '',
    workgroup: '',
    productkey: '',
    creationdate: '',
    defaults: {
        'actionid': null,
        'salesorder': '',
        'codeapc': '',
        'rack': '',
        'shelf': '',
        'hostname': '',
        'timezone': '',
        'posixtz': '',
        'wintz': '',
        'dststartday': '',
        'dststopday': '',
        'dststarth': '',
        'dststoph': '',
        'os': '',
        'image': '',
        'boot': '',
        'ip': '',
        'netmask': '',
        'gateway': '',
        'iloip': '',
        'ilonm': '',
        'ilogw': '',
        'workgroup': '',
        'productkey': '',
        'creationdate': ''
    }
});

/**
 * Provisioningaction Backbone Collection
 */
model.ProvisioningactionCollection = model.AbstractCollection.extend({
    url: 'api/provisioningactions',
    model: model.ProvisioningactionModel
});

/**
 * Provisioningimages Backbone Model
 */
model.ProvisioningimagesModel = Backbone.Model.extend({
    urlRoot: 'api/provisioningimages',
    idAttribute: 'imagename',
    imagetarget: '',
    targetname: '',
    imagename: '',
    ostarget: '',
    defaults: {
        'imagetarget': '',
        'imagename': null,
        'ostarget': ''
    }
});

/**
 * Provisioningimages Backbone Collection
 */
model.ProvisioningimagesCollection = model.AbstractCollection.extend({
    url: 'api/provisioningimageses',
    model: model.ProvisioningimagesModel
});

/**
 * Provisioningnotifications Backbone Model
 */
model.ProvisioningnotificationsModel = Backbone.Model.extend({
    urlRoot: 'api/provisioningnotifications',
    idAttribute: 'notifid',
    notifid: '',
    hostname: '',
    installationip: '',
    configuredip: '',
    startdate: '',
    status: '',
    progress: '',
    image: '',
    firmware: '',
    ram: '',
    cpu: '',
    diskscount: '',
    netintcount: '',
    model: '',
    serial: '',
    os: '',
    update: '',
    defaults: {
        'notifid': null,
        'hostname': '',
        'installationip': '',
        'configuredip': '',
        'startdate': '',
        'status': '',
        'progress': '',
        'image': '',
        'firmware': '',
        'ram': '',
        'cpu': '',
        'diskscount': '',
        'netintcount': '',
        'model': '',
        'serial': '',
        'os': '',
        'update': ''
    }
});

/**
 * Provisioningnotifications Backbone Collection
 */
model.ProvisioningnotificationsCollection = model.AbstractCollection.extend({
    url: 'api/provisioningnotificationses',
    model: model.ProvisioningnotificationsModel
});



/**
 * Provisioningscripts Backbone Model
 */
model.ProvisioningscriptsModel = Backbone.Model.extend({
    urlRoot: 'api/provisioningscripts',
    idAttribute: 'scriptid',
    scriptid: '',
    scripttarget: '',
    scripttargetname: '',
    scriptname: '',
    scriptdescription: '',
    scriptcontent: '',
    interpreter: '',
    version: '',
    defaults: {
        'scriptid': null,
        'scripttarget': '',
        'scriptname': '',
        'scriptdescription': '',
        'scriptcontent': '',
        'interpreter': '',
        'version': ''
    }
});

/**
 * Provisioningscripts Backbone Collection
 */
model.ProvisioningscriptsCollection = model.AbstractCollection.extend({
    url: 'api/provisioningscriptses',
    model: model.ProvisioningscriptsModel
});

/**
 * Remotecommands Backbone Model
 */
model.RemotecommandsModel = Backbone.Model.extend({
    urlRoot: 'api/remotecommands',
    idAttribute: 'remotecommandid',
    remotecommandid: '',
    salesorder: '',
    rack: '',
    shelf: '',
    clientaddress: '',
    arguments: '',
    exesequence: '',
    scriptid: '',
    returncode: '',
    returnstdout: '',
    returnstderr: '',
    executionflag: '',
    logtime: '',
    exectime: '',
    defaults: {
        'remotecommandid': null,
        'salesorder': '',
        'rack': '',
        'shelf': '',
        'clientaddress': '',
        'arguments': '',
        'exesequence': '',
        'scriptid': '',
        'returncode': '',
        'returnstdout': '',
        'returnstderr': '',
        'executionflag': '',
        'logtime': '',
        'exectime': ''
    }
});

/**
 * Remotecommands Backbone Collection
 */
model.RemotecommandsCollection = model.AbstractCollection.extend({
    url: 'api/remotecommandses',
    model: model.RemotecommandsModel
});

/**
 * Sysprodracks Backbone Model
 */
model.SysprodracksModel = Backbone.Model.extend({
    urlRoot: 'api/sysprodracks',
    idAttribute: 'idracks',
    idracks: '',
    reponse: '',
    defaults: {
        'idracks': null,
        'reponse': ''
    }
});

/**
 * Sysprodracks Backbone Collection
 */
model.SysprodracksCollection = model.AbstractCollection.extend({
    url: 'api/sysprodrackses',
    model: model.SysprodracksModel
});

/**
 * Sysprodracksmapping Backbone Model
 */
model.SysprodracksmappingModel = Backbone.Model.extend({
	urlRoot: 'api/sysprodracksmapping',
	idAttribute: 'clientid',
	rack: '',
	shelf: '',
	cycladesip: '',
	cycladesport: '',
	switchip: '',
	switchport: '',
	bootpip: '',
	clientid: '',
	defaults: {
		'rack': '',
		'shelf': '',
		'cycladesip': '',
		'cycladesport': '',
		'switchip': '',
		'switchport': '',
		'bootpip': '',
		'clientid': null
	}
});

/**
 * Sysprodracksmapping Backbone Collection
 */
model.SysprodracksmappingCollection = model.AbstractCollection.extend({
	url: 'api/sysprodracksmappings',
	model: model.SysprodracksmappingModel
});



/**
 * Tblprogress Backbone Model
 */
model.TblprogressModel = Backbone.Model.extend({
    urlRoot: 'api/tblprogress',
    idAttribute: 'id',
    id: '',
    user: '',
    data: '',
    salesorder: '',
    creationdate: '',
    defaults: {
        'id': null,
        'user': '',
        'data': '',
        'salesorder': '',
        'creationdate': ''
    }
});

/**
 * Tblprogress Backbone Collection
 */
model.TblprogressCollection = model.AbstractCollection.extend({
    url: 'api/tblprogresses',
    model: model.TblprogressModel
});


/**
 * TblPassword Backbone Model
 */
model.TblPasswordModel = Backbone.Model.extend({
    urlRoot: 'api/tblpassword',
    idAttribute: 'salesorder',
    salesorder: '',
    results: '',
    time: '',
    defaults: {
        'salesorder': null,
        'results': '',
        'time': ''
    }
});

/**
 * TblPassword Backbone Collection
 */
model.TblPasswordCollection = model.AbstractCollection.extend({
    url: 'api/tblpasswords',
    model: model.TblPasswordModel
});




/**
 * Tempdata Backbone Model
 */
model.TempdataModel = Backbone.Model.extend({
    urlRoot: 'api/tempdata',
    idAttribute: 'salesorder',
    salesorder: '',
    data: '',
    status: '',
    timestamps: '',
    message: '',
    creator: '',
    dwprocessed: '',
    defaults: {
        'salesorder': null,
        'data': '',
        'status': '',
        'timestamps': '',
        'message': '',
        'creator': '',
        'dwprocessed': ''
    }
});

/**
 * Tempdata Backbone Collection
 */
model.TempdataCollection = model.AbstractCollection.extend({
    url: 'api/tempdatas',
    model: model.TempdataModel
});

/**
 * Users Backbone Model
 */
model.UsersModel = Backbone.Model.extend({
    urlRoot: 'api/users',
    idAttribute: 'uId',
    uId: '',
    username: '',
    password: '',
    uRight: '',
    uAdUser: '',
    uAdPassword: '',
    uPhone: '',
    uFullName: '',
    uAdEmail: '',
    token: '',
    defaults: {
        'uId': null,
        'username': '',
        'password': '',
        'uRight': '',
        'uAdUser': '',
        'uAdPassword': '',
        'uPhone': '',
        'uFullName': '',
        'uAdEmail': '',
        'token': ''
    }
});

/**
 * Users Backbone Collection
 */
model.UsersCollection = model.AbstractCollection.extend({
    url: 'api/userses',
    model: model.UsersModel
});


/**
 * Customer_Ip_Inventory Backbone Model
 */
model.Customer_Ip_InventoryModel = Backbone.Model.extend({
    urlRoot: 'api/customer_ip_inventory',
    idAttribute: 'custipid',
    custipid: '',
    subnet: '',
    netmask: '',
    account: '',
    location: '',
    systemName: '',
    entt: '',
    remoteAccess: '',
    comments: '',
    valdate: '',
    validatedBy: '',
    lsmod: '',
    status: '',
    defaults: {
        'custipid': null,
        'subnet': '',
        'netmask': '',
        'account': '',
        'location': '',
        'systemName': '',
        'entt': '',
        'remoteAccess': '',
        'comments': '',
        'valdate': '',
        'validatedBy': '',
        'lsmod': '',
        'status': ''
    }
});

/**
 * Customer_Ip_Inventory Backbone Collection
 */
model.Customer_Ip_InventoryCollection = model.AbstractCollection.extend({
    url: 'api/adresses',
    model: model.Customer_Ip_InventoryModel
});

/**
 * HotlineSyncDate Backbone Model
 */
model.HotlineSyncDateModel = Backbone.Model.extend({
    urlRoot: 'api/hotlinesyncdate',
    idAttribute: 'id',
    id: '',
    lastSyncDate: '',
    defaults: {
        'id': null,
        'lastSyncDate': new Date()
    }
});

/**
 * HotlineSyncDate Backbone Collection
 */
model.HotlineSyncDateCollection = model.AbstractCollection.extend({
    url: 'api/hotline',
    model: model.HotlineSyncDateModel
});

/**
 * IP_valid_ranges Backbone Model
 */
model.IP_valid_rangesModel = Backbone.Model.extend({
    urlRoot: 'api/ip_valid_ranges',
    idAttribute: 'id',
    start: '',
    end: '',
    id: '',
    defaults: {
        'start': '',
        'end': '',
        'id': null
    }
});

/**
 * IP_valid_ranges Backbone Collection
 */
model.IP_valid_rangesCollection = model.AbstractCollection.extend({
    url: 'api/ranges',
    model: model.IP_valid_rangesModel
});

/**
 * Tblorders Backbone Model (productionDB)
 */
model.TblordersModel = Backbone.Model.extend({
        urlRoot: 'api/tblorders',
        idAttribute: 'salesorder',
        salesorder: '',
        programmanager: '',
        siteengineer: '',
        sysprodactor: '',
        release: '',
        comment: '',
        startdate: '',
        enddate: '',
        prodstartdate: '',
        prodenddate: '',
        customer: '',
        timezone: '',
        cctsnapshotpath: '',
        sid: '',
        customersigle: '',
        exported: '',
        defaults: {
                'salesorder': null,
                'programmanager': '',
                'siteengineer': '',
                'sysprodactor': '',
                'release': '',
                'comment': '',
                'startdate': new Date(),
                'enddate': new Date(),
                'prodstartdate': new Date(),
                'prodenddate': new Date(),
                'customer': '',
                'timezone': '',
                'cctsnapshotpath': '',
                'sid': '',
                'customersigle': '',
                'exported': ''
        }
});

/**
 * Tblorders Backbone Collection (productionDB)
 */
model.TblordersCollection = model.AbstractCollection.extend({
        url: 'api/tblorderses',
        model: model.TblordersModel
});
