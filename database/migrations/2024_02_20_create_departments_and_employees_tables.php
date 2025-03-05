<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display_name_ar');
            $table->string('display_name_en');
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('role_ar');
            $table->string('role_en');
            $table->text('bio_ar');
            $table->text('bio_en');
            $table->string('email')->unique();
            $table->string('image')->nullable();
            $table->json('social_links')->nullable();
            $table->foreignId('department_id')->constrained();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
        Schema::dropIfExists('departments');
    }
}; 