<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;


use App\Device;
use App\DeviceController;
Use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Use Illuminate\Support\Facades\Input;
use App\Devicetype;



class DeviceUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */




}






//     public static function get_ubnt_stats($id)
// {

//     $device = Device::find($id);

//     $ip = $device->ip;

//     $cookie_file = tempnam('/tmp', 'freqin-cookie');
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, 'http://' . $ip);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//     curl_setopt($ch, CURLOPT_HEADER, 1);
//     curl_setopt($ch, CURLOPT_TIMEOUT, 2);
//     curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
//     curl_setopt($ch, CURLOPT_VERBOSE, true);
//     $result = curl_exec($ch);
//     if(curl_errno($ch))
//     {
//         echo 'error:' . curl_error($ch);
//     }
//     if (!strstr($result, 'AIROS_SESSIONID')) {
//         unlink($cookie_file);
//         return false;
//     }
    
//     $radio_data = 0;
    
//     foreach ($logins as $login) {
//         $login_post_data = array(
//             'uri' => '/status.cgi',
//             'username' => 'admin',
//             'password' => 'laroch007',
//             'Submit' => 'Login'
//         );
//         curl_setopt($ch, CURLOPT_HTTPHEADER, Array('Expect: '));
//         curl_setopt($ch, CURLOPT_HEADER, 0);
//         curl_setopt($ch, CURLOPT_URL, 'http://' . $ip . '/login.cgi');
//         curl_setopt($ch, CURLOPT_POST, 1);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $login_post_data);
//         curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
//         curl_setopt($ch, CURLOPT_VERBOSE, true);
//         $result = curl_exec($ch);
//         if(curl_errno($ch))
//         {
//             echo 'error:' . curl_error($ch);
//         }
// // ATTEMPTING SSH
//         $command = "cat /var/etc/board.info | grep board.name | cut -d= -f2";
//         $power = "cat /etc/sysinit/radio.conf | grep power | cut -d' ' -f4";
//         $port = 22;
//         $user = "admin";
//         $pass = "laroch007";

//         $connection = ssh2_connect($ip, $port);
//         ssh2_auth_password($connection, $user, $pass);
        
//         $model = ssh2_exec($connection, $command);
//             if($model == 0) {
//                 echo "An error has occured.";
//             }
//             stream_set_blocking($model, true);
            
//         $output = ssh2_exec($connection, $power);
//             if($output == 0) {
//                 echo "An error has occured.";
//             }
//             stream_set_blocking($output, true);
// // END ATTEMPT

//         if ($result) {
//             $data = json_decode($result);
// // IF STATEMENT FOR <= 5.3.5 DEVICES
//             if (array_key_exists('lan', $data)) {
//                 $radio_data = array(
//                     'name' => $data->host->hostname,
//                     'mode' => $data->wireless->mode == 'sta' ? 'Station' : 'Access Point',
//                     'fw' => $data->host->fwversion,
//                     'uptime' => sprintf('%.2f', $data->host->uptime / 86400) . ' days',
//                     'dfs' => 'N',
//                     'freq' => preg_replace('/[^0-9]+/', '', $data->wireless->frequency),
//                     'channel' => $data->wireless->channel,
//                     'width' => $data->wireless->chwidth,
//                     'signal' => $data->wireless->signal,
//                     'noise' => $data->wireless->noisef,
//                     'wds' => $data->wireless->wds,
//                     'ssid' => $data->wireless->essid,
//                     'security' => $data->wireless->security,
//                     'distance' => sprintf('%.2f', $data->wireless->distance * 0.000621371192) . 'mi',
//                     'connections' => $data->wireless->count,
//                     'ccq' => sprintf('%.1f', $data->wireless->ccq / 10) . '%',
//                     'ame' => $data->wireless->polling->enabled,
//                     'amq' => $data->wireless->polling->quality,
//                     'amc' => $data->wireless->polling->capacity,
//                     'lan' => isset($data->lan->status[0]->plugged) ? ($data->lan->status[0]->plugged ? $data->lan->status[0]->speed . "mbps-" . ($data->lan->status[0]->duplex ? 'Full' : 'Half') : 'Unplugged') : $data->lan->status[0],
//                     'lan_mac' => $data->lan->hwaddr,
//                     'wlan_mac' => $data->wlan->hwaddr,
//                     'tx' => $data->wireless->txrate,
//                     'rx' => $data->wireless->rxrate,
//                     'retries' => $data->wireless->stats->tx_retries,
//                     'err_other' => $data->wireless->stats->err_other,
//                     'chains' => $data->wireless->chains,
//                     'model' => trim(stream_get_contents($model)),
//                     'power' => trim(stream_get_contents($output)),
//                     'gps' => 0
//                     );

//             } else { // ELSE STATEMENT FOR > 5.3.5 DEVICES          
// // IF GPS...
//                     if(array_key_exists('gps', $data)) {
//                         $radio_data = array(
//                         'name' => $data->host->hostname,
//                         'mode' => $data->wireless->mode == 'sta' ? 'Station' : 'Access Point',
//                         'fw' => $data->host->fwversion,
//                         'uptime' => sprintf('%.2f', $data->host->uptime / 86400) . ' days',
//                         'dfs' => $data->wireless->dfs,
//                         'freq' => preg_replace('/[^0-9]+/', '', $data->wireless->frequency),
//                         'channel' => $data->wireless->channel,
//                         'width' => $data->wireless->chwidth,
//                         'signal' => $data->wireless->signal,
//                         'noise' => $data->wireless->noisef,
//                         'wds' => $data->wireless->wds,
//                         'ssid' => $data->wireless->essid,
//                         'security' => $data->wireless->security,
//                         'distance' => sprintf('%.2f', $data->wireless->distance * 0.000621371192) . 'mi',
//                         'connections' => $data->wireless->count,
//                         'ccq' => sprintf('%.1f', $data->wireless->ccq / 10) . '%',
//                         'ame' => $data->wireless->polling->enabled,
//                         'amq' => $data->wireless->polling->quality,
//                         'amc' => $data->wireless->polling->capacity,
//                         'lan' => $data->interfaces[1]->status->speed . "mbps-" . ($data->interfaces[1]->status->duplex ? 'Full' : 'Half'),
//                         'lan_mac' => $data->interfaces[1]->hwaddr,
//                         'wlan_mac' => $data->interfaces[3]->hwaddr,
//                         'tx' => $data->wireless->txrate,
//                         'rx' => $data->wireless->rxrate,
//                         'retries' => $data->wireless->stats->tx_retries,
//                         'err_other' => $data->wireless->stats->err_other,
//                         'chains' => $data->wireless->chains,
//                         'model' => trim(stream_get_contents($model)) . " GPS",
//                         'power' => trim(stream_get_contents($output)),
//                         'gps' => 1
//                     );
// // TWO ETHERNETS, NO GPS.  GPS WOULD'VE BEEN MATCHED BY ABOVE CONDITION                 
//                     } elseif (($data->interfaces[3]->ifname) == 'wifi0') {
//                         $radio_data = array(
//                         'name' => $data->host->hostname,
//                         'mode' => $data->wireless->mode == 'sta' ? 'Station' : 'Access Point',
//                         'fw' => $data->host->fwversion,
//                         'uptime' => sprintf('%.2f', $data->host->uptime / 86400) . ' days',
//                         'dfs' => $data->wireless->dfs,
//                         'freq' => preg_replace('/[^0-9]+/', '', $data->wireless->frequency),
//                         'channel' => $data->wireless->channel,
//                         'width' => $data->wireless->chwidth,
//                         'signal' => $data->wireless->signal,
//                         'noise' => $data->wireless->noisef,
//                         'wds' => $data->wireless->wds,
//                         'ssid' => $data->wireless->essid,
//                         'security' => $data->wireless->security,
//                         'distance' => sprintf('%.2f', $data->wireless->distance * 0.000621371192) . 'mi',
//                         'connections' => $data->wireless->count,
//                         'ccq' => sprintf('%.1f', $data->wireless->ccq / 10) . '%',
//                         'ame' => $data->wireless->polling->enabled,
//                         'amq' => $data->wireless->polling->quality,
//                         'amc' => $data->wireless->polling->capacity,
//                         'lan' => $data->interfaces[1]->status->speed . "mbps-" . ($data->interfaces[1]->status->duplex ? 'Full' : 'Half'),
//                         'lan_mac' => $data->interfaces[1]->hwaddr,
//                         'wlan_mac' => $data->interfaces[3]->hwaddr,
//                         'tx' => $data->wireless->txrate,
//                         'rx' => $data->wireless->rxrate,
//                         'retries' => $data->wireless->stats->tx_retries,
//                         'err_other' => $data->wireless->stats->err_other,
//                         'chains' => $data->wireless->chains,
//                         'model' => trim(stream_get_contents($model)),
//                         'power' => trim(stream_get_contents($output)),
//                         'gps' => 0
//                     );
//                     } else {
// // ELSE {DEVICE ONLY HAS ONE ETHERNET PORT & IS NOT GPS}
//                         $radio_data = array(
//                         'name' => $data->host->hostname,
//                         'mode' => $data->wireless->mode == 'sta' ? 'Station' : 'Access Point',
//                         'fw' => $data->host->fwversion,
//                         'uptime' => sprintf('%.2f', $data->host->uptime / 86400) . ' days',
//                         'dfs' => $data->wireless->dfs,
//                         'freq' => preg_replace('/[^0-9]+/', '', $data->wireless->frequency),
//                         'channel' => $data->wireless->channel,
//                         'width' => $data->wireless->chwidth,
//                         'signal' => $data->wireless->signal,
//                         'noise' => $data->wireless->noisef,
//                         'wds' => $data->wireless->wds,
//                         'ssid' => $data->wireless->essid,
//                         'security' => $data->wireless->security,
//                         'distance' => sprintf('%.2f', $data->wireless->distance * 0.000621371192) . 'mi',
//                         'connections' => $data->wireless->count,
//                         'ccq' => sprintf('%.1f', $data->wireless->ccq / 10) . '%',
//                         'ame' => $data->wireless->polling->enabled,
//                         'amq' => $data->wireless->polling->quality,
//                         'amc' => $data->wireless->polling->capacity,
//                         'lan' => $data->interfaces[1]->status->speed . "mbps-" . ($data->interfaces[1]->status->duplex ? 'Full' : 'Half'),
//                         'lan_mac' => $data->interfaces[1]->hwaddr,
//                         'wlan_mac' => $data->interfaces[2]->hwaddr,
//                         'tx' => $data->wireless->txrate,
//                         'rx' => $data->wireless->rxrate,
//                         'retries' => $data->wireless->stats->tx_retries,
//                         'err_other' => $data->wireless->stats->err_other,
//                         'chains' => $data->wireless->chains,
//                         'model' => trim(stream_get_contents($model)),
//                         'power' => trim(stream_get_contents($output)),
//                         'gps' => 0
//                     );

//                     }
//             }
//     }
    
//     unlink($cookie_file);
//     return dd($radio_data);
//     }
// }
