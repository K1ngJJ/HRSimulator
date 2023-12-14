<?php

namespace App\Models;

use App\Enums\RoomStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'description', 'image', 'guest_number', 'status'];

    protected $casts = [
        'status' => RoomStatus::class,
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_group');
    }
}