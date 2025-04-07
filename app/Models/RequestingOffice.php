<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestingOffice extends Model
{
    protected $primaryKey = 'requesting_office_id';

    protected $fillable = [
        'name', 'type', 'requestor', 'status',
    ];

    public function requestor()
    {
        return $this->belongsTo(Requestor::class, 'requestor', 'requestor_id');
    }

    public function requests()
    {
        return $this->hasMany(Request::class, 'requesting_office_id', 'requesting_office_id');
    }
}
