<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice_attachments extends Model
{
    use HasFactory;

    protected $table = 'invoice_attachments';

    protected $fillable = [
        'file_name',
        'invoice_number',
        'Created_by',
        'invoice_id'
    ];

    // علاقة مع الفاتورة
    public function invoice()
    {
        return $this->belongsTo(invoices::class, 'invoice_id');
    }
}
