<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SqlBackup extends Model
{
    protected $table = 'sql_backup';
    
    public $timestamps = false;
}
