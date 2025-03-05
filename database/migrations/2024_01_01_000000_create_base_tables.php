<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseTables extends Migration
{
    public function up()
    {
        // إنشاء جدول المستخدمين
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('user');
            $table->boolean('status')->default(true);
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });

        // إنشاء جدول الموظفين
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_id')->unique();
            $table->string('department');
            $table->string('position');
            $table->date('join_date');
            $table->decimal('salary', 10, 2);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // إنشاء جدول سجلات المزامنة
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();
            $table->string('service');
            $table->timestamp('last_sync');
            $table->string('status')->default('success');
            $table->text('details')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // إنشاء جدول النسخ الاحتياطية
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('size');
            $table->text('path');
            $table->string('type')->default('full');
            $table->string('status')->default('completed');
            $table->timestamp('created_at')->useCurrent();
        });

        // إنشاء جدول سجلات النظام
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->text('message');
            $table->json('context')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // إنشاء جدول الإعدادات
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group_name')->default('general');
            $table->timestamps();
        });

        // إنشاء جدول الحضور والانصراف
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->string('status')->default('present');
            $table->decimal('work_hours', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // إنشاء جدول الإجازات
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('pending');
            $table->text('reason')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leaves');
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('system_logs');
        Schema::dropIfExists('backups');
        Schema::dropIfExists('sync_logs');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('users');
    }
} 