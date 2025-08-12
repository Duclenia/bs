<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrimeSubEnquad extends Model
{
    /**
     *
     * @var string 
     */
    protected $table = 'crimsubenquad';
    
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'designacao', 'idEnq'
    ];
}
