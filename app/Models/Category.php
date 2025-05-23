<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // ✅ penting!

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'user_id'];

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class); // pastikan 'Todo' diimport juga jika perlu
    }
}
