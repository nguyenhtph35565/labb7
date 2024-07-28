<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'name',
        'image',
        'description',
        'price',
        'stock_qty',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function orders()
    {
        return $this
            ->belongsToMany(Order::class, 'order_details', 'product_id', 'order_id')
            ->withPivot('price', 'qty');
    }
}
