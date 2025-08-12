<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $permissoes = Permission::with('funcoes')->get();

        foreach ($permissoes as $permissao) {

            $gate->define($permissao->nome, function(User $user) use ($permissao) {

                return $user->hasPermissao($permissao);
            });
        }
        $gate->before(function(User $user, $ability) {

            if ($user->funcaoUtilizador('SuperAdmin'))
                return true;
        });

        VerifyEmail::toMailUsing(function($notifiable, $url) {

            return (new MailMessage)
                            ->subject('Verifique seu e-mail')
                            ->line('Por favor, clique no link abaixo para verificar seu e-mail')
                            ->action('Verifique seu e-mail', $url)
                            ->line('Se você não criou uma conta, nenhuma acção é requerida!');
        });
    }
}
