<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Step extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = "steps";
    protected $fillable = [
        'name',
        'description',
        'files',
        'report_id',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
