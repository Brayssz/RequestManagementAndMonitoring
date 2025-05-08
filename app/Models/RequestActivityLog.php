<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestActivityLog extends Model
{
    use HasFactory;

    protected $table = 'request_activity_logs';

    protected $primaryKey = 'log_id';

    public $incrementing = true;

    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'request_id',
        'user_id',
        'transmitted_office_id',
        'transmitted_date',
        'remarks',
        'activity',
        'created_at',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id', 'request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function transmittedOffice()
    {
        return $this->belongsTo(RequestingOffice::class, 'transmitted_office_id', 'requesting_office_id');
    }
}