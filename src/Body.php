<?php

namespace Stellisoft\Stellify;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Body extends Model
{
    //use SoftDeletes;
    protected $fillable = [
        'slug', 'site_id', 'type', 'data', 'requires', 'searchable', 'name', 'shadowpath', 'path', 'title', 'keywords', 'meta_description'
    ];
}
