<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualAllotment extends Model
{
    use HasFactory;

    protected $primaryKey = 'allotment_id';

    protected $fillable = [
        'requesting_office_id',
        'amount',
        'year',
        'status',
    ];

    public function requestingOffice()
    {
        return $this->belongsTo(RequestingOffice::class, 'requesting_office_id', 'requesting_office_id');
    }
}

