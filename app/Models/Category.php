<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use /*SoftDeletes,*/ HasFactory;

    protected $table = 'categories';

    protected $primaryKey = 'id';

    protected $fillable = [
      'name',
      'color'
    ];

    public function categories(): BelongsToMany {
        return $this->belongsToMany(Category::class, 'note_category')->withTimestamps();
    }
}
