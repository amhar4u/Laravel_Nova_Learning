<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * All columns that are mass-assignable.
     * These match exactly the Nova field types shown in the Post tool.
     */
    protected $fillable = [
        'title',            // Text field
        'slug',             // Slug field
        'author',           // Text field
        'meta_title',       // Text field
        'content',          // Trix / Markdown field
        'excerpt',          // Textarea field
        'meta_description', // Textarea field
        'status',           // Select / Badge field
        'category',         // Select field
        'is_featured',      // Boolean field
        'views',            // Number field
        'rating',           // Number field (decimal)
        'reading_time',     // Number field
        'published_at',     // DateTime field
        'featured_image',   // Image / File field
        'tags',             // Tags / KeyValue field
    ];

    /**
     * Cast columns to the correct PHP types automatically.
     */
    protected $casts = [
        'is_featured'  => 'boolean',
        'published_at' => 'datetime',
        'views'        => 'integer',
        'rating'       => 'float',
        'reading_time' => 'integer',
    ];
}
