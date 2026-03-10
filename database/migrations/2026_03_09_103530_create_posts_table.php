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
            $table->string('title');                        
            $table->string('slug')->unique();               
            $table->string('author')->nullable();           
            $table->string('meta_title')->nullable();
            $table->longText('content')->nullable();        
            $table->text('excerpt')->nullable();            
            $table->text('meta_description')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft'); 
            $table->string('category')->nullable();         
            $table->boolean('is_featured')->default(false); 
            $table->unsignedInteger('views')->default(0);   
            $table->decimal('rating', 3, 1)->nullable();    
            $table->unsignedInteger('reading_time')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('tags')->nullable(); 
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
