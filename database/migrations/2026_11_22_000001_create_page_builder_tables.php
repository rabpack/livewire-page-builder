<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('builder_pages')) {
            Schema::create('builder_pages', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->json('document')->nullable();
                $table->json('settings')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('page_builder_form_submissions')) {
            Schema::create('page_builder_form_submissions', function (Blueprint $table) {
                $table->id();
                $table->string('form_id')->index();
                $table->json('payload');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Intentionally left blank — package tables stay available.
    }
};
