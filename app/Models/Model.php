<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Query\Builder;

class Model extends EloquentModel
{
    public function scopeLatest(Builder $query)
    {
        $query->orderBy('created_at', 'desc');
    }
}
