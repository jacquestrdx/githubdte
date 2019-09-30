<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eventlog extends Model
{
    public static function createEvent($data){
        $eventlog = new Eventlog();
        $eventlog->remote_table_id = $data['remote_table_id'];
        $eventlog->remote_table = $data['remote_table'];
        $eventlog->current_value = $data['current_value'];
        $eventlog->previous_value = $data['previous_value'];
        $eventlog->event_type = $data['event_type'];
        $eventlog->severity = $data['severity'];
        $eventlog->status = 1;
        $eventlog->save();
    }
    public static function updateEvent($data){
        $eventlog = Eventlog::find($data['id']);
        $eventlog->remote_table_id = $data['remote_table_id'];
        $eventlog->remote_table = $data['remote_table'];
        $eventlog->current_value = $data['current_value'];
        $eventlog->previous_value = $data['previous_value'];
        $eventlog->event_type = $data['event_type'];
        $eventlog->severity = $data['severity'];
        $eventlog->status = $data['status'];
        $eventlog->save();
    }

}
