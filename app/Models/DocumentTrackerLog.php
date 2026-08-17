<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTrackerLog extends Model
{
    protected $table = 'document_tracker_logs';

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'document_tracker_id',
        'from_office_id',
        'to_office_id',
        'user_id',
        'action',
        'notes',
        'created_at',
    ];

    public function documentTracker()
    {
        return $this->belongsTo(DocumentTracker::class, 'document_tracker_id', 'id');
    }

    public function fromOffice()
    {
        return $this->belongsTo(RequestingOffice::class, 'from_office_id', 'requesting_office_id');
    }

    public function toOffice()
    {
        return $this->belongsTo(RequestingOffice::class, 'to_office_id', 'requesting_office_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
