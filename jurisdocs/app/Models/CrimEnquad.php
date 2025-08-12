<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrimEnquad extends Model
{
    /**
     *
     * @var string 
     */
    protected $table = 'crimenquad';
    
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'designacao'
    ];
}
