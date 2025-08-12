<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Permission extends Model
{
    
    /**
     * Este método retorna todas as funcões associadas a uma determinada permissao
     * @return type
     */
    public function funcoes()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
    
    
    
}
