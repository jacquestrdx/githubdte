<?php


Route::group(['api' => ['web']], function () {

    Route::get("api/downdevices", 'HomeController@getDownDevicesCount');
    Route::get("api/totalpppoe", 'HomeController@getTotalPppoe');
    Route::get("api/problemlocations", 'HomeController@getProblemLocations');
    Route::get("api/getdownpowermons", 'HomeController@getDownPowerMons');
//    Route::get("api/getdashboardoutages", 'HomeController@getDashboardOutages');

});