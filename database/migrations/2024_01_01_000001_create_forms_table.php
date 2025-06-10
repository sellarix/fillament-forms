<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index('forms_name_index');
            $table->string('slug')->unique();
            $table->string('status');
            $table->text('description')->nullable();
            $table->enum('mode', ['single', 'multi'])->default('single'); // behavioral
            $table->string('view')->nullable();
            $table->boolean('email_to_user')->default(false);
            $table->string('email_template')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
