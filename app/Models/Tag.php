<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public $timestamps = false;

    /** Deze tag is gekoppeld aan meerdere producten. */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
