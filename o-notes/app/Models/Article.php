<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;
    protected $table = "articles";

    protected $fillable = [
        'title',
        'subtitle',
        'text_content',
        'file_content',
        'banner',
        'user_id',
        'subcategory_id',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
