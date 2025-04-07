<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requestor extends Model
{
    protected $primaryKey = 'requestor_id';

    protected $fillable = [
        'name', 'email', 'position', 'status',
    ];

    public function requestingOffices()
    {
        return $this->hasMany(RequestingOffice::class, 'requestor', 'requestor_id');
    }
}
