<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function () {

    Route::get('/', function () {
        return view('welcome');
    });

});

View::composer('home', function($view)
{
    $view->with("locationstatus", App\Location::getStatusCheck());
});

View::composer('*', function($view){

    View::share('view_name', $view->getName());
//
});



Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::resource('/backhaul', 'BackhaulController');
    Route::resource('/customdash', 'DashboardController');
    Route::get("/dash/additem/{id}", 'DashboardController@addItem');
    Route::post("/dash/storeitem", 'DashboardController@storeItem');

    Route::get('/home', 'HomeController@newdashboard');
    Route::get('/report/top20', 'SlaReportController@showTopTwenty');
    Route::get('/report/top20ubnt', 'SlaReportController@showTopTwentyUBNT');
    Route::get('/report/top20cambium', 'SlaReportController@showTopTwentyCAMBIUM');
    Route::get('/report/sectors', 'SlaReportController@showSectors');
    Route::get('/newdashboard', 'HomeController@newdashboard');
    Route::get('/fizdashboard', 'HomeController@showFizDashboard');
    Route::get('/locations/backhauls/{id}', 'LocationController@getBackhaulsAjax');
    Route::get("/dashboard", 'HomeController@showDashboard')->name('home.dashboard');
    //Device routes
    Route::get("/device/showMinMaxInterfaces/{id}", 'DeviceController@showMinMaxInterfaces')->name('device.showMinMaxInterfaces');
    Route::get("/devices/import", 'DeviceController@import');
    Route::get("/devices/confirm/ajax", 'DeviceController@tobeImportedAjax');
    Route::get("/devices/confirm/post", 'DeviceController@confirmImport');
    Route::get("/blackboard/deleteinterfacewarning/{id}",'InterfaceWarningController@delete');
    Route::post("/devices/post/import", 'DeviceController@processImport')->name('devices.importcsv');
    Route::post("/devices/post/confirm", 'DeviceController@confirmImport');
    Route::get("/devicetypes/ajax/all","DevicetypeController@ajaxAll");
    Route::get("/devices/downloadtemplate",'DeviceController@downloadTemplate');
    Route::get("/device/voltages", 'DeviceController@showVoltges');
    Route::get("/mikrotik/ips", 'DeviceController@showIPs');
    Route::get("/backhaul/delete/{id}",'BackhaulController@destroy');
    Route::get("/mikrotik/ips/ajax", 'DeviceController@getIPsAJAX');
    Route::delete('/sipaccount/destroy/{id}', 'SipaccountController@destroy')->name('sipaccount.destroy');
    Route::resource('/device', 'DeviceController');
    Route::get('/bgppeer/delete/{id}', 'BGPPeerController@destroy')->name('bgppeer.destroy');
    Route::resource('/neighbor', 'NeighborController');
    Route::get('/neighbor/ajax/all', 'NeighborController@getAllAjax');
    Route::resource('/backhaul', 'BackhaulController');
    Route::get('/client/ajax/all', 'ClientController@ajaxAll');

    Route::resource('/client', 'ClientController');
    Route::resource('/deviceaudit', 'DeviceauditController');
    Route::resource('/deviceaudits/ajax', 'DeviceauditController@getAllAjax');
    Route::resource('/locationaudit', 'LocationauditController@index');
    Route::resource('/locationaudits/ajax', 'LocationauditController@getAllAjax');
    Route::resource('/interfacewarnings', 'InterfaceWarningController');
    Route::get('/interfacewarnings/ajax/all', 'InterfaceWarningController@getAllAjax');
    Route::get('interfacewarning/acknowledge/{id}', 'InterfaceWarningController@acknowledge');
    Route::get('inbox/acknowledgeinterfacewarning/{id}', 'UserController@acknowledgeInterfaceWarning');
    Route::get('inbox', 'UserController@showInbox');
    Route::get('inbox/ajax/{id}', 'UserController@ajaxInbox');


    Route::get('/backhaul/ajax/all', 'BackhaulController@getAllAjax');
    Route::get('/getLocationInterfaces/{id}', 'DInterfaceController@getLocationInterfaces');
    Route::get('/getdeviceinterfaces/{id}', 'DInterfaceController@getDeviceInterfaces');
    Route::get("/device/updatenow/{id}", 'DeviceController@updatedev')->name('updatedevice');
    Route::get("/devices/nosnmp", 'DeviceController@shownosnmp')->name('device.nosnmp');
    Route::get("/device/acknowledge/{id}", 'DeviceController@acknowledge')->name('device.acknowledge');
    Route::get("/location/acknowledge/{id}", 'LocationController@acknowledge')->name('location.acknowledge');
    Route::get("/device/nosnmp/ajax", 'DeviceController@shownosnmpAJAX')->name('device.nosnmp');
    Route::get("/statable/location/{id}", 'StatableController@showLocationAjax');
    Route::get("/statables/device/{id}", 'StatableController@showDeviceAjax');
    Route::get("/statables/graphs/{id}", 'DeviceController@showStatableGraphs');
    Route::get("/statables/pergraph/{id}", 'StatableController@showGraph');
    Route::get("/device/sortby/{id}", 'DeviceController@sortby')->name('device.sortby');
    Route::get("/interfaceslog/sfp", 'DeviceController@showSfpLog');
    Route::get("/interfaceslog/sfpAJAX", 'DeviceController@showSfpLogAJAX');
    Route::get("/devicemap", 'DeviceController@showDeviceMap')->name('device.devicemap');
    Route::get("/highsitemap", 'DeviceController@showHSDeviceMap');
    Route::get("/networkmap", 'DeviceController@showNetworkMap');
    Route::get("/systems/showrunning","SystemController@showRunning");
    Route::get("/dinterface/acknowledge/{id}", 'DInterfaceController@acknowledgeinterface');
    Route::get("/networkmap/getnodes", 'DeviceController@showNetworkMapNodes');
    Route::get("/scriptnotdeleted", 'DeviceController@scriptnotdeleted')->name('device.scriptnotdeleted');
    Route::get("/oldros", 'DeviceController@oldRos')->name('device.oldRos');
    Route::get("/rebooting/{id}", 'DeviceController@rebootDevice')->name('device.reboot');
    Route::get("/updatesoft/{id}", 'DeviceController@updateSoftware')->name('device.updatesoft');
    Route::get("/scheduleupdatesmt", 'DeviceController@scheduleSoftwareUpdates')->name('device.scheduleupdates');
    Route::get("/showfaulty", 'DeviceController@getFaultyDevices')->name('device.faulty');
    Route::get("/noLocations", 'DeviceController@noLocations')->name('device.noLocations');
    Route::get("/devicearray", 'DeviceController@getDeviceArray')->name('device.devicearray');
    Route::get("/interfacelogs/deviceajax/{id}", 'DeviceController@showDeviceAJAXLinkHistory');
    Route::get("/interfacelogs/device/{id}", 'DeviceController@showDeviceLinkHistory');
    Route::get("/interfacelogs/index", 'DeviceController@showAllLinkHistory');
    Route::get("/interfacelogs/indexAJAX", 'DeviceController@showAllLinkHistoryAJAX');
    Route::get("/interfacelogs/showAJAX/{id}", 'DeviceController@showAllLinkHistory');
    Route::get("/map", 'DeviceController@map')->name('device.map');
    Route::get("/report/location/clientsajax", 'ReportController@getLocationReportAJAX');
    Route::get('/interfaces/delete/{id}','DInterfaceController@delete');
    Route::resource('/stationspec', 'StationspecController');

    Route::get('/report/location/clients','ReportController@showLocationReport');
    Route::get('/report/updatebackuplocation/clientsajax','ReportController@getLocationReportAJAX');
    Route::get("/mapjson", 'DeviceController@mapjson')->name('device.map.json');
    Route::get("/mikrotik/index", 'DeviceController@IndexMikrotiks')->name('device.IndexMikrotiks');
    Route::get("/backupstatus", 'DeviceController@backupstatus')->name('device.backupstatus');
    Route::get("/updatebackup/{id}", 'DeviceController@backupdevice')->name('device.backupdevice');
    Route::get("/downloadbackup/{id}", 'DeviceController@downloadbackup')->name('device.downloadbackup');
    Route::get("/getbackup/{file}", 'DeviceController@getbackup')->name('device.getbackup');
    Route::get('/backup/compare/{file1}/{file2}','DeviceController@getDiff')->name('device.comparebackups');
    Route::get("/devices/notification_log", 'DeviceController@notification_log')->name('device.notification_log');
    Route::get("/clients/notification_log", 'ClientController@notification_log');
    Route::get("/hsformcomplete/{id}", 'HighsiteformController@complete')->name('hsform.complete');
    Route::get("/updateclient/{id}", 'ClientController@updateClient')->name('client.updateClient');
    Route::get("/getMikrotikInterfaces/{id}", 'DeviceController@showMikrotikInterfacesTable');
    Route::get("/frequencyreport/{id}", 'LocationController@frequencyreport')->name('location.frequencyreport');
    Route::get("/location/sectors/{id}", 'LocationController@showSectors')->name('location.sectors');
    Route::get("/mikrotik/show/interfaces/{id}", 'DeviceController@showMikrotikInterfaces')->name('device.mikrotik.interfaces');
    Route::get("/mikrotik/show/pppoegraphs", 'DeviceController@showMikrotikPPPOEGraphs');
    Route::get("/mikrotik/show/pppoetables", 'DeviceController@showMikrotikPPPOETable');
    Route::get("/users/verify/{id}",'UserController@verifyUser');
    Route::get("/users/deverify/{id}",'UserController@unVerifyUser');
    Route::get("/system/load","HomeController@getSystemLoadAJAX");
    Route::get("/system/polling","SystemController@PollingNr");
    Route::get("/mikrotik/show/pppoetablesAJAX", 'DeviceController@showMikrotikPPPOETableAJAX');
    Route::patch("/updatelist/{id}", 'DeviceController@updateList')->name('updatelist');
    Route::get("/mikrotik/graph/{id}",'DeviceController@graphMikrotik');
    Route::get("/smtp/list", 'DeviceController@showALLSMTP');
    Route::get('getusers/ajax','UserController@getAllAJAX');
    Route::get('/traceroute/{id}','DeviceController@tracerouteIP');
    Route::get('/voltages/showall','DeviceController@showVoltages');
    Route::get('/voltages/ajaxall','DeviceController@ajaxVoltages');
    Route::get('/voltages/ajaxdevice/{id}','DeviceController@ajaxVoltagesDevice');
    Route::get('/location/getdevicesipsajax/{id}','LocationController@getDevicesIPsAJAX');
    Route::get('/location/getdevices/{id}','LocationController@getDevicesAJAX');
    Route::get('/location/getdevicesinterfacesliveajax/{id}','LocationController@getDevicesInterfacesAJAX');
    Route::get('/pppoeclient/offline', 'PppoeclientController@showOffline')->name('pppoeclient.offline');
    Route::get('getDownPPPoeAllTime','PppoeclientController@getDownPPPoeAllTime');
    Route::get('getDownPPPoeThisMonth','PppoeclientController@getDownPPPoeThisMonth');
    Route::get('getDownPPPoeNoReason','PppoeclientController@getDownPPPoeNoReason');
    Route::get('queuestats/ajax','HomeController@getQueueStats');
    Route::get('queuestats','HomeController@showQueues');
    Route::get('/clients/vip/offline','ClientController@showOfflineVipClients');
    Route::get('/clients/vip/online','ClientController@showOnlineVipClients');
    Route::get('/clients/vip/all','ClientController@showAllVipClients');
    Route::get('/pppoeclient/report', 'PppoeclientController@report')->name('pppoeclient.report');
    Route::post("pppoeclient/storereason/{id}", 'PppoeclientController@storeReason');

    Route::get('/pppoeclient/addreason/{id}', 'PppoeclientController@addReason')->name('pppoeclient.addreason');
    Route::get('/pppoeclient/thismonth', 'PppoeclientController@showthismonth')->name('pppoeclient.thismonth');
    Route::get('/device/destroy/{id}', 'DeviceController@destroy')->name('devices.delete');
    Route::get('/devices/passwords/{id}', 'DeviceController@changePassword')->name('device.passwords');
    Route::post("/devices/updatepassword/{id}", 'DeviceController@updatePassword')->name('device.updatepassword');


    Route::get('/devices/secure', 'DeviceController@secureRouterForm');
    Route::post("/devices/securerouter", 'DeviceController@secureRouterPost');


    Route::get('/location/destroy/{id}', 'LocationController@destroy')->name('locations5.delete');
    Route::get('/reports/devices/month', 'SlaReportController@showDeviceReportMonth')->name('devices.monthreport');
    Route::get('/reports/devices/week', 'SlaReportController@showDeviceReportWeek')->name('devices.weekreport');
    Route::get('/reports/devices/day', 'SlaReportController@showDeviceReportDay')->name('devices.dayreport');
    Route::get('/reports/devices/24h', 'SlaReportController@showDeviceReport24h');
    Route::get('/reports/devices/7days', 'SlaReportController@showDeviceReport7days');
    Route::get('/reports/devices/30days', 'SlaReportController@showDeviceReport30days');

    Route::get('/reports/highsites/month', 'SlaReportController@showLocationReportMonth')->name('highsites.monthreport');
    Route::get('/reports/highsites/week', 'SlaReportController@showLocationReportWeek')->name('highsites.weekreport');
    Route::get('/reports/highsites/day', 'SlaReportController@showLocationReportDay')->name('highsites.dayreport');

    //Resource controllers
    Route::resource('/job', 'JobController');
    Route::resource('/pppoeclient', 'PppoeclientController');
    Route::resource('/dashhistory', 'DashHistoryController');
    Route::get('/customsnmpoids/create/{id}', 'CustomsnmpoidController@create');
    Route::get('/blackboard', 'HomeController@showBlackboard');
    Route::get("/blackboard/deleteinterfacewarning/{id}",'InterfaceWarningController@delete');
    Route::get('/devices/create/{id}', 'DeviceController@createfromlocations');
    Route::resource('/customsnmpoid', 'CustomsnmpoidController');
    Route::resource('/nmap', 'NmapController');
    Route::resource('/user', 'UserController');
    Route::resource('/bgppeer', 'BGPPeerController');
    Route::resource('/backhaultype', 'BackhaultypeController');
    Route::get('/backhauls/possible', 'BackhaulController@showPossibleBackhauls');
    Route::get('/backhauls/possible/{id}', 'BackhaulController@flagAsAdded');
    Route::resource('/highsiteform', 'HighsiteformController');
    Route::resource('/location', 'LocationController');
    Route::resource('/devicetype', 'DevicetypeController');
    Route::resource('/location/map', 'LocationController@map');
    Route::resource('/bwstaff', 'BwstaffController');
    Route::resource('/hscontact', 'HscontactController');
    Route::resource('/devicesdown', 'DeviceController@down');
    Route::resource('/deviceupdator', 'DeviceController@updateall');
    Route::resource('/devicepinger', 'DeviceController@pingall');
    Route::get('/faultreport', 'FaultController@faultreport')->name('faultreport');
    Route::get('/faultreport/nosnmp', 'FaultController@faultreportNoSnmp');
    Route::get('/faultreport/ajax', 'FaultController@faultreportAJAX');
    Route::get('/faultreport/ajaxnosnmp', 'FaultController@faultreportNoSnmpAJAX');
    Route::resource('/possiblesectors', 'DeviceController@showSectors');
    Route::get('/devices/latencies','DeviceController@showWarningLatencies');
    Route::get('/devices/latenciesAJAX','DeviceController@showWarningLatenciesAJAX');
    //Acknowledgement::
    Route::get("acknowledge/edit/{id}", 'AcknowledgementController@edit')->name('acknowledge.edit');
    Route::post("acknowledge/update/{id}", 'AcknowledgementController@update')->name('acknowledge.update');
    Route::post("acknowledgedevice/{id}", 'AcknowledgementController@addDeviceAcknowledgement')->name('acknowledge.addDeviceAcknowledgement');
    Route::post("acknowledgelacotion/{id}", 'AcknowledgementController@addLocationAcknowledgement')->name('acknowledge.addLocationAcknowledgement');
    Route::post("/deviceconfig/download", 'DeviceController@downloadConfig')->name('deviceconfig.download');
    Route::get("/deviceconfig/create", 'DeviceController@createConfig');
    Route::get("/blackboardalerts/acknowledge/{id}",'AcknowledgementController@addBlackboard');
    Route::post("/acknowledgeblackboard/{id}", 'AcknowledgementController@addBlackboardAcknowledgement')->name('acknowledge.addBlackboardAcknowledgement');

    Route::post("acknowledgebgppeer/{id}", 'AcknowledgementController@addBGPPeerAcknowledgement')->name('acknowledge.addBGPPeerAcknowledgement');
    Route::post("acknowledgefault/{id}", 'AcknowledgementController@addFaultAcknowledgement')->name('acknowledge.addFaultAcknowledgement');
    Route::get("/fault/acknowledge/{id}", 'FaultController@acknowledge')->name('fault.acknowledge');

    Route::get("/dashboard/backhauls",'HomeController@showBackhaulDashboard');
    Route::get("/dashboard/dials",'HomeController@showDialsDashboard');
    Route::get("/dashboard/power",'HomeController@showPowerDashboard');
    Route::get("/outages/log/ajax",'DeviceController@getNotificationsAllAjax');
    Route::get("/outages/log/csv",'DeviceController@getNotificationsAllCSV');
    Route::get("/dashboard/outages",'HomeController@showOuttageDashboard');
    Route::get("/dashboard/interfaces",'HomeController@showInterfaceDashboard');
    Route::get("/interface/monitor/{id}",'DInterfaceController@getAjaxDataPerInterface');

    //bgppeer routes
    Route::get("/bgppeers", 'DeviceController@showBgpPeers')->name('device.showBgpPeers');
    Route::get("/enabled/bgppeers", 'DeviceController@showEnabledBgpPeers')->name('device.showEnabledBgpPeers');
    Route::get("/bgppeersoffline", 'DeviceController@showDownBgpPeers')->name('device.showDownBgpPeers');
    Route::get("/bgppeer/acknowledge/{id}", 'BGPPeerController@acknowledge')->name('bgppeer.acknowledge');

    //location routes
    Route::get("/highsitefaultreport/{id}", 'LocationController@highsitefaultreport')->name('location.highsitefaultreport');
    Route::get("/highsitereport/stock/quick", 'LocationController@quickStockReport')->name('location.quickstock.report');
    Route::get("/highsitereport/stock/detailed", 'LocationController@detailedStockReport')->name('location.detailedstock.report');
    Route::get("/downloadhighsitereport",'LocationController@generateExcelReport');
    //Ajax Functions
    Route::get("/getDownDevicesCount", 'HomeController@getDownDevicesCount');
    Route::get("/getOnlineFizzes", 'HomeController@getOnlineFizzes');
    Route::get("/getOnlineHotspotUsers", 'HomeController@getActiveHotspotUsersAJAX');
    Route::get("/getOnlineHotspotRouters", 'HomeController@getActiveHotspotRoutersAJAX');
    Route::get("/getMaxHotspotRouters", 'HomeController@getMaxHotspotRoutersAJAX');
    Route::get("/getMaxHotspotUsers", 'HomeController@getMaxHotspotUsersAJAX');



    Route::get("/getOfflineFizzes", 'HomeController@getOfflineFizzes');
    Route::get("/getPartOfflineFizzes", 'HomeController@getPartOfflineFizzes');
    Route::get("/getOnlineFizUsers", 'HomeController@getOnlineFizUsers');
    Route::get("/isizwe/device/weekly", 'TshwanereportController@devicetableWeekly');
    Route::get("/isizwe/fiz/weekly", 'TshwanereportController@fiztableWeekly');
    Route::get("/isizwe/latency/weekly", 'TshwanereportController@latencytableWeekly');
    Route::get("/isizwe/device/monthly", 'TshwanereportController@devicetableMonthly');
    Route::get("/isizwe/fiz/monthly", 'TshwanereportController@fiztableMonthly');
    Route::get("/isizwe/latency/monthly", 'TshwanereportController@latencytableMonthly');
    Route::get('/jobs/all/ajax', 'JobController@showall');
    Route::get('/locations/all/ajax', 'LocationController@getAllAjax');
    Route::get('/threshhold/warnings', 'HomeController@warnings');
    Route::get('/threshhold/report', 'HomeController@report');

    Route::get('/device/all/ajax','DeviceController@getAllAjax');
    Route::get("/device/showMinMaxInterfacesAJAX/{id}", 'DeviceController@showMinMaxInterfacesAJAX')->name('device.showMinMaxInterfacesAJAX');
    Route::get("/getNotificationSounds/{id}", 'UserNotificationController@getnotificationsounds');
    Route::get("/get/highest/interfaces", 'DeviceController@getHighestInterfacesAJAX');
    Route::get("/get/all/interfaces",'DeviceController@getAllinterfaces');
    Route::get("/get/highlow/interfaces",'DeviceController@getHighestLowest');
    Route::get("/smtp/queue/{id}",'DeviceController@showSMTP')->name('smtp.queue');
    Route::get("/pppoe/all/ajax",'PppoeclientController@showallAJAX');
    Route::get("/pppoe/offline/ajax",'PppoeclientController@showofflineAJAX');
    Route::get("/pppoe/thismonth/ajax",'PppoeclientController@showthismonthAJAX');
    Route::get("/statable/all/ajax",'StatableController@showallAJAX');
    Route::get("/getnotificationbar",'UserNotificationController@getnotificationbar');
    Route::get("/getdownbgp", 'HomeController@getDownBGP');
    Route::get("/getWhatsappReport", 'HomeController@getWhatsappReport');
    Route::get('/speedtest/{id}',"DeviceController@testToCore");
    Route::get("/getFizWhatsappReport", 'HomeController@getFizWhatsappReport');
    Route::get("/getTotalPppoe", 'HomeController@getTotalPppoe');
    Route::get("/getMaxPppoe", 'HomeController@getMaxPppoe');
    Route::get("/device/getInterfaceStats/{id}",'DeviceController@getInterfaceStats');
    Route::get("/getProblemLocations", 'HomeController@getProblemLocations');
    Route::get("/getDownPowerMons", 'HomeController@getDownPowerMons');
    Route::get("/showallstations", 'DeviceController@showAllStations');
    Route::get("/sipextensions", 'SipextentionController@getStatusNew');
    Route::get("/pollhighsite/{id}", 'DeviceController@pollHighsite');
    Route::get("/sipextension", 'SipextentionController@index');
    Route::get("/active/calls", 'SipextentionController@getActiveCalls');
    Route::get("/locations/scan/{id}", 'LocationController@autoDiscoverForm');
    Route::post("/locations/doscan/{id}", 'LocationController@autoDiscover')->name('locations.scan');
    Route::post("/locations/addscan/{id}", 'LocationController@addFromScan');
    Route::get("/showallstationsajax", 'DeviceController@showAllStationsAJAX');
    Route::get("/getDashboardOutages", 'HomeController@getDashboardOutages');
    Route::get("/testInterface/{id}","DInterfaceController@testRRD");
    Route::get("/showDashboardOutages", 'HomeController@showDashboardOutages');
    Route::get("/showdevice/pings/{id}",'DeviceController@showDevicePings');
    Route::post("/updatedevice/pings/{id}",'DeviceController@showDevicePingsTime');
    Route::get("/getDevicePings/{id}",'DeviceController@getDevicePingsAJAX');
    Route::get("/getDeviceDayPings/{id}",'DeviceController@getDeviceDayPingsAJAX');
    Route::get("/getDeviceMonthPings/{id}",'DeviceController@getDeviceMonthPingsAJAX');
    Route::get("/getDeviceYearPings/{id}",'DeviceController@getDeviceYearPingsAJAX');
    Route::get("/getClientPings/{id}",'ClientController@getClientPingsAJAX');
    Route::get("/getClientTraffic/{id}",'ClientController@getClientTrafficAJAX');
    Route::get("/pppoeclients/sector/{id}",'PppoeclientController@SectorAJAX');
    Route::get("/test/device/{id}",'DeviceController@testDeviceClients');
    Route::get("/test/results/{id}",'DeviceController@inputSpeedResults');
    Route::post('/test/store/{id}', 'DeviceController@storeSpeedResults');
    Route::get('/signal/form', 'DeviceController@signalForm');
    Route::post('/signal/calculate', 'DeviceController@calculateSignal');
    Route::get("/device/showgraphs/{id}", 'DeviceController@getDeviceStatsAJAX')->name('device.graphs');
    //Route::get("/getDevicePingTimes/{id}",'DeviceController@getDevicePingTimes');
    Route::get('/addcomment/{id}', 'CommentController@createComment')->name('add.comment');
    Route::get('/task/complete/{id}', 'TaskController@complete')->name('task.complete');
    Route::get('/task/filter/unassigned/', 'TaskController@viewUnasigned')->name('task.unasigned');
    Route::get('/task/filter/mytasks/', 'TaskController@viewMyTasks')->name('task.mytasks');
    Route::get('/myproject/', 'ProjectController@myProjects');
    Route::get('/task/reasign/{id}', 'TaskController@reasign')->name('task.reasign');
    Route::post('/task/store/{id}', 'TaskController@storeReassignment')->name('task.storereasign');
    Route::resource('/task', 'TaskController');
    Route::get('/stock/add/{id}', 'StockController@create');

    Route::resource('/stock', 'StockController');
    Route::resource('/dinterface', 'DInterfaceController');
    Route::resource('/system', 'SystemController');
    Route::resource('/tshwanereport', 'TshwanereportController');
    Route::resource('/comment', 'CommentController');
    Route::resource('/project', 'ProjectController');
    Route::resource('statable', 'StatableController@index');
//commit force
    //VoipRoutes

    //user routes
    Route::get('/usernotification/{id}', 'UserNotificationController@markAsRead')->name('usernotification.read');
    Route::get('/notifications/all', 'UserNotificationController@markAllAsRead')->name('usernotification.all');

    Route::get('sipaccount/addAcknowledge/{user_id}/{sip_id}', 'SipaccountController@addAcknowledge');
    Route::get('sipaccount/onlinesips/{id}', 'SipaccountController@onlinesips');
    Route::get('sipaccount/offlinesips/{id}', 'SipaccountController@offlinesips');
    Route::get('sipaccount/acksips/{id}', 'SipaccountController@acksips');
    Route::get('pages/sipaccount/removeupstreamtrunk/{user_id}/{sip_id}', 'SipaccountController@removeupstreamtrunk');
    Route::get('pages/sipaccount/addupstreamtrunk/{user_id}/{sip_id}', 'SipaccountController@addupstreamtrunk');
    Route::get('sipaccount/configureupstream', 'SipaccountController@configureupstream');
    Route::resource('sipaccount', 'SipaccountController');

    Route::get('/sipserver', 'SipserverController@index')->middleware('auth');
    Route::get('/sipserver/create', 'SipserverController@create')->middleware('auth');
    Route::get('/sipserver/show/{id}', 'SipserverController@show')->middleware('auth');
    Route::get('sipaccount/removeupstreamtrunk/{user_id}/{sip_id}', 'SipaccountController@removeupstreamtrunk');
    Route::get('sipaccount/addupstreamtrunk/{user_id}/{sip_id}', 'SipaccountController@addupstreamtrunk');
    Route::resource('/clients', 'ClientController');

});


