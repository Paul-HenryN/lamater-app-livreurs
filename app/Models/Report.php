<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model 
{
    use HasFactory;

    protected $table = "reports";
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function steps()
    {
        return $this->hasMany(Step::class);
    }
}
