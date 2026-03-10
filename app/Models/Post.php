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
        'title',            
        'slug',             
        'author',           
        'meta_title',       
        'content',         
        'excerpt',          
        'meta_description', 
        'status',           
        'category',         
        'is_featured',      
        'views',             
        'rating',           
        'reading_time',     
        'published_at',     
        'featured_image',   
        'tags',             
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
