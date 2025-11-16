<?php

namespace App\Models;

use App\Models\products;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\sections;
use App\Models\invoices_details;


class invoices extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'invoices';

    // الأعمدة القابلة للملء
    protected $fillable = [
        'invoice_number',
        'invoice_Date',
        'Due_date',
        'product',
        'section_id',
        'Amount_collection',
        'Amount_Commission',
        'Discount',
        'Value_VAT',
        'Rate_VAT',
        'Total',
        'Status',
        'Value_Status',
        'note'
    ];

    // علاقة مع الأقسام
    public function section()
    {
        return $this->belongsTo(sections::class, 'section_id');
    }

    // علاقة مع تفاصيل الفاتورة
    public function details()
    {
        return $this->hasMany(invoices_details::class, 'id_Invoice');
    }

    // علاقة مع المرفقات
    public function attachments()
    {
        return $this->hasMany(invoice_attachments::class, 'invoice_id');
    }

    public function productInfo()
    {
        return $this->belongsTo(Products::class, 'product');
    }
}


