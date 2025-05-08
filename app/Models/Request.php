<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\RequestActivityLog;

class Request extends Model
{
    protected $primaryKey = 'request_id';

    protected $fillable = [
        'dts_date',
        'dts_tracker_number',
        'sgod_date_received',
        'requesting_office_id',
        'amount',
        'fund_source_id',
        'allotment_year', 
        'nature_of_request',
        'signed_chief_date',
        'date_transmitted',
        'remarks',
        'status',
        'transmitted_office_id', 
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


    public function transmittedOffice()
    {
        return $this->belongsTo(RequestingOffice::class, 'transmitted_office_id', 'requesting_office_id'); // Added transmitted office relationship
    }

    public function getRequestor()
    {
        return $this->requestingOffice ? $this->requestingOffice->requestor->name : null;
    }

    protected static function booted()
    {
        static::created(function ($request) {
            RequestActivityLog::create([
                'request_id' => $request->request_id,
                'user_id' => Auth::id(),
                'activity' => 'Received.',
                'created_at' => now(),
            ])->timestamps = false;
        });

        static::updating(function ($request) {
            if ($request->isDirty('status')) {
                $newStatus = ucfirst($request->status);

                RequestActivityLog::create([
                    'request_id' => $request->request_id,
                    'transmitted_office_id' => $request->transmitted_office_id,
                    'transmitted_date' => $request->date_transmitted,
                    'remarks' => $request->remarks,
                    'user_id' => Auth::id(),
                    'activity' => $newStatus, 
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->timestamps = false;
            }
        });

       
    }
}
