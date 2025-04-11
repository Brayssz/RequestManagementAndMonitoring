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
        'utilize_funds',
        'fund_source_id',
        'allotment_id', 
        'nature_of_request',
        'signed_chief_date',
        'date_transmitted',
        'remarks',
        'status',
    ];
    

    protected $attributes = [
        'status' => 'pending',
    ];

    public function requestingOffice()
    {
        return $this->belongsTo(RequestingOffice::class, 'requesting_office_id', 'requesting_office_id');
    }

    public function fundSource()
    {
        return $this->belongsTo(FundSource::class, 'fund_source_id', 'fund_source_id');
    }

    public function allotment()
    {
        return $this->belongsTo(AnnualAllotment::class, 'allotment_id', 'allotment_id'); // Added allotment relationship
    }
}
