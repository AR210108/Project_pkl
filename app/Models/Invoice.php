<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    // Use the actual database column names matching frontend form
    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'company_name',
        'company_address',
        'client_name',
        'description',
        'subtotal',
        'tax',
        'total',
        'payment_method',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'tax' => 'decimal:2',
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
