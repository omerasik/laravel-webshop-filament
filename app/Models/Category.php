<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public $timestamps = false;

    /** In deze categorie zitten alle gekoppelde producten. */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
