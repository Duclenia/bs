<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Config;
use App\Models\ConfiguracaoGeral;
use App\Models\Language;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        try {
            DB::connection()->getPdo();
//            if (\Schema::hasTable('mailsetups')) {
//
//                $mail = DB::table('mailsetups')->first();
//                // dd( $mail );
//
//                if ($mail) { //checking if table is not empty
//                    $config = array(
//                        'driver' => 'SMTP',
//                        'host' => $mail->mail_host,
//                        'port' => $mail->mail_port,
//                        'from' => array(
//                            'address' => $mail->mail_username,
//                            'name' => "Advocate"
//                        ),
//                        'encryption' => $mail->mail_encryption,
//                        'username' => $mail->mail_username,
//                        'password' => $mail->mail_password
//                            // 'bcc'        => $mail->bcc_mail
//                    );
//                    Config::set('mail', $config);
//                    // dd(config());
//                }
//            }
            //set timezone
            if (\Schema::hasTable('configuracao_geral') && \Schema::hasTable('fusohorario')) {

                $time = DB::table('configuracao_geral')->first()->timezone;
                $timezone = DB::table('fusohorario')->where('id', $time)->first()->nome;
                // dd( $timezone);
                config::set(['app.timezone' => $timezone]);
                date_default_timezone_set($timezone);
            }
        } catch (\Exception $e) {
            
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        $languages = Language::select('iso', 'name')->where('active', true)->orderBy('name', 'asc')->get();
        
        
        view()->share([
            'languages' => $languages   
        ]);
        
        view()->composer('*', function($view)
        {

            $formato_data = ConfiguracaoGeral::findOrfail(1)->formato_data;

            if ($formato_data == 1) {
                $date1 = "dd-mm-yyyy";
                $date2 = "d-m-Y";
            } elseif ($formato_data == 2) {
                $date1 = "yyyy-mm-dd";
                $date2 = "Y-m-d";
            } elseif ($formato_data == 3) {
                $date1 = "mm-dd-yyyy";
                $date2 = "m-d-Y";
            }
            // dd( $date);

            $data['date_format_datepiker'] = $date1;
            $data['date_format_laravel'] = $date2;

            $data['image_logo'] = ConfiguracaoGeral::findOrfail(1)->first();

            $data['notificacoes'] = auth()->user()->unreadNotifications ?? [];


            $view->with($data);
        });
    }
}
