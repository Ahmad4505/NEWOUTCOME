<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sections extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_name',
        'description',
        'created_by'
    ];

      // 🔗 القسم يحتوي على عدة فواتير
    public function invoices()
    {
        return $this->hasMany(invoices::class, 'section_id');
    }

    // 🔗 القسم يحتوي على عدة منتجات
    public function products()
    {
        return $this->hasMany(products::class, 'section_id');
    }

}
