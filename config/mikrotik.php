<?php

return [
//    dd(DB::table('systems')->find(1);
    'api_username' => env('MIKROTIK_API_USERNAME'),
    'api_password' => env('MIKROTIK_API_PASSWORD'),
    'backup_storage' => env('MIKROTIK_BACKUP_STORAGE'),

];
