<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['image', 'is_active'])]
class CustomerFeedback extends Model
{
    protected $table = 'customer_feedbacks';

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true)->latest('id');
    }

    public function getImageUrlAttribute(): string
    {
        return asset($this->image);
    }
}
