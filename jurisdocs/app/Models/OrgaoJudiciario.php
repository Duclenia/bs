<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgaoJudiciario extends Model
{
    protected $table= 'orgaojudiciario';
    
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
