<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CastMember extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'type',
        'created_at',
    ];

    protected $casts = [
        'id' => 'string',
        'deleted_at' => 'datetime',
    ];

    public function videos()
    {
        return $this->belongsToMany(Video::class);
    }
}
