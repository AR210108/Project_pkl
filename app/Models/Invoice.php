<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'company_name',
        'company_address',
        'client_name',
        'order_number',
        'payment_method',
        'description',
        'subtotal',
        'tax',
        'total',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'subtotal' => 'integer',
        'tax' => 'integer',
        'total' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}