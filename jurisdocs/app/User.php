<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\{Permission,Role,Admin,Cliente};

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','user_type', 'language'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function funcoes()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function hasPermissao(Permission $permissao)
    {
// retorna todas as funções associadas a uma permissão
        return $this->funcaoUtilizador($permissao->funcoes);
    }

    /**
     * Este método verifica se o utilizador possui uma funcao especifica
     */
    public function funcaoUtilizador($funcoes)
    {

        if (is_array($funcoes) || is_object($funcoes))
            return !!$funcoes->intersect($this->funcoes)->count();

        return $this->funcoes->contains('nome', $funcoes);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id');
    }


    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'user_id');
    }
}
