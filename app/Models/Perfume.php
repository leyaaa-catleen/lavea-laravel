<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfume extends Model
{
    protected $fillable = ['name', 'category', 'notes', 'price', 'stock', 'color'];
    public function getPesoAttribute(): string
    {
        return '₱' . number_format($this->price, fmod((float) $this->price, 1) ? 2 : 0);
    }

    public const CATEGORIES = ['Floral', 'Woody', 'Fresh', 'Oriental'];
}
