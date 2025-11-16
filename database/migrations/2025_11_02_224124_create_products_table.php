<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
             // اسم المنتج بطول أقصى 255 (يمكن زيادته حسب الحاجة)
        $table->string('Product_name', 255);

        // الوصف يمكن أن يكون فارغًا
        $table->text('description')->nullable();

        // رقم القسم المرتبط بالمنتج
        $table->unsignedBigInteger('section_id');

        // مفتاح أجنبي يربط الجدول بجدول الأقسام
        $table->foreign('section_id')
              ->references('id')
              ->on('sections')
              ->onDelete('cascade'); // حذف المنتجات عند حذف القسم

        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
