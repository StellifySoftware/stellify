<?php

namespace Stellisoft\Stellify;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
  protected $fillable = [
    'user_id', 'data'
  ];
}
