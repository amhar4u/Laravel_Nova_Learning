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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // ── Text Fields ─────────────────────────────────────
            $table->string('title');                        // Nova: Text field
            $table->string('slug')->unique();               // Nova: Slug field
            $table->string('author')->nullable();           // Nova: Text field
            $table->string('meta_title')->nullable();       // Nova: Text field

            // ── Textarea / Rich-Text Fields ──────────────────────
            $table->longText('content')->nullable();        // Nova: Trix / Markdown field
            $table->text('excerpt')->nullable();            // Nova: Textarea field
            $table->text('meta_description')->nullable();   // Nova: Textarea field

            // ── Select Fields ────────────────────────────────────
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft'); // Nova: Select / Badge field
            $table->string('category')->nullable();         // Nova: Select field

            // ── Boolean Field ────────────────────────────────────
            $table->boolean('is_featured')->default(false); // Nova: Boolean field

            // ── Number Fields ────────────────────────────────────
            $table->unsignedInteger('views')->default(0);   // Nova: Number field
            $table->decimal('rating', 3, 1)->nullable();    // Nova: Number field (decimal)
            $table->unsignedInteger('reading_time')->nullable(); // Nova: Number field (minutes)

            // ── DateTime Field ───────────────────────────────────
            $table->timestamp('published_at')->nullable();  // Nova: DateTime field

            // ── Image / File Field ───────────────────────────────
            $table->string('featured_image')->nullable();   // Nova: Image / File field

            // ── Tag / KeyValue Field ─────────────────────────────
            $table->string('tags')->nullable();             // Nova: Tags field (comma-separated)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
