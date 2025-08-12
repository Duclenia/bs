<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crime extends Model
{
    protected $table = 'crime';
    
    public $timestamps = false;
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'designacao', 'idEnq', 'idSubEnq', 'artigo'
    ];
    
    
    public function crimEnquad()
    {
        return $this->belongsTo(CrimEnquad::class, 'idEnq');
    }
    
    
    public function crimSubEnquad()
    {
        return $this->belongsTo(CrimeSubEnquad::class, 'idSubEnq');
    }
}
