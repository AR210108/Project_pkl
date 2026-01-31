<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'order_no',
        'layanan',
        'kategori',
        'price',
        'price_formatted',
        'klien',
        'deposit',
        'paid',
        'status',
        'work_status',
        'invoice_id',
    ];

    protected $casts = [
        'price' => 'integer',
        'deposit' => 'integer',
        'paid' => 'integer',
        'invoice_id' => 'integer',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
