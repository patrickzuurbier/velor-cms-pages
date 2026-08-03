<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('paragraphs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('page_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->json('title');
            $table->json('intro')->nullable();
            $table->json('content')->nullable();
            $table->json('anchor')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->autoIncrement();
            $table->timestamps();
        });
    }
};
