<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('identifier')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('city_gateway', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gateway_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('gateway_system_module', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gateway_id')->constrained()->cascadeOnDelete();
            $table->foreignId('system_module_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('gateway_system_module');
        Schema::dropIfExists('city_gateway');
        Schema::dropIfExists('gateways');
    }
};
