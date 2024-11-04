<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'technology',
        'start_date',
        'end_date',
        'additional_info',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
