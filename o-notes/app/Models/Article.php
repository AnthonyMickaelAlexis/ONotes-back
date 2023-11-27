<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasFactory;
    protected $table = "articles";
    public $timestamps = true;

    protected $fillable = [
        'title',
        'subtitle',
        'text_content',
        'file_content',
        'resume',
        'banner',
        'status',
        'user_id',
        'subcategory_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function tag(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
