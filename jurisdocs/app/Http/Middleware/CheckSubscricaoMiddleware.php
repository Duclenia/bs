<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class CheckSubscricaoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        
        $subscricao = DB::table('subscricao')
                ->where('data_termino', function($query) {
                    $query->selectRaw('MAX(data_termino)')
                    ->from('subscricao');
                })
                ->first();
        
        if($user->user_type == 'SuperAdmin' || $user->user_type == 'Cliente'){
              return $next($request);
        }else{
            
            if(verificarData($subscricao->data_termino)):
                
                return $next($request);
            else:
                return back()->with('warning', "O prazo da tua subscrição terminou.");
      
            endif;
            
        }
           
        
    }
}
