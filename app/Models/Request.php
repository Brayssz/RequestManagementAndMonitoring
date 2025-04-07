<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $primaryKey = 'request_id';

    protected $fillable = [
        'dts_date',
        'dts_tracker_number',
        'sgod_date_received',
        'requesting_office_id',
        'amount',
        'fund_source',
        'nature_of_request',
        'signed_chief_date',
        'date_transmitted',
        'remarks',
        'status',
    ];

    public function requestingOffice()
    {
        return $this->belongsTo(RequestingOffice::class, 'requesting_office_id', 'requesting_office_id');
    }
}
