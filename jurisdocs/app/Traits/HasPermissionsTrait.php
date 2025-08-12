<?php
namespace App\Traits;

use App\Models\Permissao;
use App\Models\Funcao;

trait HasPermissionsTrait {

	
	public function hasPermissionTo($permissao) {
		// return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
		return $this->hasPermissionThroughRole($permissao) ;
	}

	public function hasPermissionThroughRole($permissao) {
		foreach ($permissao->funcoes as $funcao){
                    
			if($this->funcoes->contains($funcao)) {
				return true;
			}
		}
		return false;
	}

	public function hasRole( ... $funcoes ) {
		foreach ($funcoes as $funcao) {
			if ($this->funcoes->contains('slug', $funcao)) {
				return true;
			}
		}
		return false;
	}

	public function funcoes()
        {
		return $this->belongsToMany(Funcao::class,'admin_funcao');
	}

	protected function hasPermission($permissao) {
		return (bool) $this->permissioes->where('slug', $permissao->slug)->count();
	}

	protected function getAllPermissions(array $permissao) {
		return Permissao::whereIn('slug',$permissao)->get();
	}
}