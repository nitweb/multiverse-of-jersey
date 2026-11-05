<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['slot', 'link', 'image'])]
class Promo extends Model
{
    public const SLOTS = [1 => 'Left promo', 2 => 'Right promo'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset($this->image) : null;
    }
}
