<?php

namespace Stellisoft\Stellify;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model
{
    //use SoftDeletes;
    protected $fillable = [
        'user_id', 'slug', 'data', 'requires', 'commit_message'
    ];

}
