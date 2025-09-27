<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['catid', 'display_name', 'parent_catid'];

    public function liveAccounts()
    {
        return $this->belongsToMany(LiveAccount::class, 'live_account_category');
    }
}
