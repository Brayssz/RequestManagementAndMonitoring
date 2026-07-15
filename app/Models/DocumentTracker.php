<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTracker extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_number',
        'requestor_name',
        'current_office_id',
        'document_type',
        'details',
        'status',
        'received_by_user_id',
        'released_by_user_id',
        'received_at',
        'released_at',
    ];

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
}