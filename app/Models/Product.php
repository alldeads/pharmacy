<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'parent_id',
        'generic_id',
        'name',
        'description',
        'status',
        'cost',
        'price',
        'sku',
        'expired_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'parent_id', 'id');
    }

    public function generic()
    {
        return $this->belongsTo(Generic::class);
    }
}
