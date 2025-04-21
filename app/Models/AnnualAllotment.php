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
        'balance',
        'fund_source_id',
        'year',
        'status',
    ];

    public function requestingOffice()
    {
        return $this->belongsTo(RequestingOffice::class, 'requesting_office_id', 'requesting_office_id');
    }

    public function fundSource()
    {
        return $this->belongsTo(FundSource::class, 'fund_source_id', 'fund_source_id');
    }

    public function requests()
    {
        return $this->hasMany(Request::class, 'allotment_id', 'allotment_id');
    }
}
