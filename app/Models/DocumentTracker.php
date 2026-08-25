<?php

namespace App\Models;

use App\Models\Concerns\SerializesDatesInDisplayTimezone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTracker extends Model
{
    use HasFactory;
    use SerializesDatesInDisplayTimezone;

    protected $casts = [
        'received_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    protected $fillable = [
        'tracking_number',
        'requestor_name',
        'requestor_email',
        'requesting_office_id',
        'current_office_id',
        'document_type',
        'details',
        'status',
        'received_by_user_id',
        'released_by_user_id',
        'received_at',
        'released_at',
    ];

    public function requestingOffice()
    {
        return $this->belongsTo(RequestingOffice::class, 'requesting_office_id', 'requesting_office_id');
    }

    public function currentOffice()
    {
        return $this->belongsTo(RequestingOffice::class, 'current_office_id', 'requesting_office_id');
    }

    public function receivedByUser()
    {
        return $this->belongsTo(User::class, 'received_by_user_id', 'id');
    }

    public function releasedByUser()
    {
        return $this->belongsTo(User::class, 'released_by_user_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(DocumentTrackerLog::class, 'document_tracker_id', 'id');
    }
}