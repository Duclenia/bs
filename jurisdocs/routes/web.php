<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/backup', function () {
    $exitCode = Artisan::call('backup:run --only-db');
    echo 'DONE'; //Return anything
});

Route::get('/createlink', function () {
    Artisan::call('storage:link');
    echo 'created';
});

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('config:clear');
    echo 'DONE'; //Return anything
});

Route::get('/', function () {
    return view('login');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('locale'); //->middleware('verified');
Route::post('/mark-as-read', 'HomeController@markNotification')->name('markNotification');
Route::get('/notificacoes', 'HomeController@notificacoes');

Route::get('f/country', 'Admin\SerchController@getCountry')->name('get.country');
Route::get('f/state', 'Admin\SerchController@getState')->name('get.state');
Route::get('f/city', 'Admin\SerchController@getCity')->name('get.city');

Route::get('areaprocessual', 'Admin\SerchController@getAreaprocessual')->name('get.areaprocessual');
Route::get('tipo-processo', 'Admin\SerchController@getTipoProcesso')->name('get.tipoprocesso');
Route::get('estado-processo', 'Admin\SerchController@getEstadoProcesso')->name('get.estadoprocesso');
Route::get('get-orgao-judiciario', 'Admin\SerchController@getOrgaoJudiciario')->name('get.orgao.judiciario');
Route::get('get-crime-enquad', 'Admin\SerchController@getCrimEnquadramento')->name('get.crimEnquad');
Route::get('get-crime-sub-enquad', 'Admin\SerchController@getCrimSubEnquadramento')->name('get.crimSubEnquad');
Route::get('tipo-crime', 'Admin\SerchController@getTipoCrime')->name('get.tipocrime');
Route::get('tribunal', 'Admin\SerchController@getTribunais')->name('get.tribunal');
Route::get('get-seccoes', 'Admin\SerchController@getSeccoes')->name('get.seccao');
Route::get('get-juizes', 'Admin\SerchController@getJuizes')->name('get.juiz');
Route::get('interveniente-designacao', 'Admin\SerchController@getDesignacaoInterveniente')->name('get.intervdesignacao');

Route::get('municipio/{id_provincia}', 'Admin\SerchController@getMunicipios');
Route::get('bairros', 'Admin\SerchController@getBairros')->name('get.bairro');

Route::post('common_check_exist', 'Controller@common_check_exist')->name('common_check_exist');

Route::post('getCaseSubType', 'Controller@getCaseSubType');
Route::post('getCourt', 'Controller@getCourt');
Route::post('getTaxById', 'Controller@getTaxById');

Route::post('common_change_state', 'Controller@common_change_state')->name('common_change_state');

Route::get('/provincia', 'Auth\RegisterController@getProvincias')->name('get.provincia');
Route::get('/municipio', 'Auth\RegisterController@getMunicipios')->name('get.municipio');

Route::group(['prefix' => 'cliente', 'as' => 'cliente.'], function () {

    Route::post('check_user_email_exits', 'ClienteController@check_user_email_exits');
    Route::post('check_nif_exits', 'ClienteController@check_nif_exits');
    Route::post('check_ndi_exits', 'ClienteController@check_ndi_exits');
    Route::post('editar-perfil', 'ClienteController@editarPerfil');
    Route::get('processos', 'ClienteController@caseListByClientId');
    Route::post('client_case_list', 'ClienteController@client_case_list')->name('client.case_view.list');
    Route::get('/ajaxCalander', 'ClienteController@ajaxCalander');
    Route::post('agendar', 'ClienteController@agendar');
    Route::get('change/password', 'Admin\PerfilController@change_pass')->name('alterar.palavra.passe')->middleware('auth');


    Route::group(['namespace' => 'Cliente', 'middleware' => ['auth', 'locale']], function () {

        //------------------Agenda----------------------------//

        Route::resource('consulta', 'AppointmentConsultaController');
        Route::get('consulta_create', 'AppointmentController@create_consulta')->name('consulta_create');
        Route::post('appointment/data-list', 'AppointmentController@appointmentList')->name('appointment.list');
        Route::resource('reuniao', 'AppointmentReuniaoController');
         Route::resource('agenda', 'AppointmentController');

        Route::post('appointment/reuniao/data-list', 'AppointmentReuniaoController@appointmentList')->name('appointmentReuniao.list');
        Route::resource('consulta', 'AppointmentConsultaController');
        Route::post('appointment/consulta/data-list', 'AppointmentConsultaController@appointmentList')->name('appointmentConsulta.list');
        Route::post('appointment/consulta/data-list', 'AppointmentConsultaController@appointmentList')->name('appointmentConsulta.list');

        Route::post('getMobileno', 'AppointmentController@getMobileno')->name('getMobileno');
    });
});


Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'locale']], function () {

    //Dashboard

    Route::resource('/dashboard', 'DashBordController');
    Route::post('/dashboard', 'DashBordController@index');
    Route::get('/ajaxCalander', 'DashBordController@ajaxCalander');
    Route::post('dashboard-all-caseList', 'DashBordController@dashboardAllCaseList');
    Route::post('dashboard-appointment-list', 'DashBordController@appointmentList')->name('dashboard-appointment-list');
    Route::get('downloadCaseBoard/{date}', 'DashBordController@downloadCaseBoard');
    Route::get('printCaseBoard/{date}', 'DashBordController@printCaseBoard');

    //---------------------------Cliente-----------------------//
    Route::resource('clients', 'ClienteController');
    Route::post('clients/data-list', 'ClienteController@ClientList')->name('clients.list');
    Route::post('clients/data-status', 'ClienteController@changeStatus')->name('clients.status');
    Route::post('check_client_email_exits', 'ClienteController@check_client_email_exits')->name('check_client_email_exits');
    Route::get('client/case-list/{id}', 'ClienteController@caseDetail')->name('clients.case-list');
    Route::get('client/account-list/{id}', 'ClienteController@AccountDetail')->name('clients.account-list');
    Route::get('client/validar-telemovel/{id}', 'ClienteController@getModalValidarTelemovel')->name('validar.telemovel');
    Route::post('client/validar-telemovel/{id}', 'ClienteController@verificarTelemovel')->name('verificar.telemovel');
    Route::get('setcodigo-verificacao', 'ClienteController@setCodVerificacao')->name('set.codigo');


    //---------------------------tarefas-----------------------//
    Route::resource('tarefas', 'TarefaController');
    Route::post('tasks/data-list', 'TarefaController@TaskList')->name('task.list');
    Route::post('tasks/data-status', 'TarefaController@changeStatus')->name('task.status');
    Route::get('tasks/notifications', 'TarefaController@getNotificacao')->name('task.notification');

    //-----------------------Plano-------------------------//
    Route::resource('plano', 'PlanoController');
    Route::post('plano/data-list', 'PlanoController@planoList')->name('plano.list');
    Route::post('plano_check_exist', 'PlanoController@planoCheckExist')->name('plano_check_exist');

    //-----------------------Subscrição-------------------------//
    Route::resource('subscricao', 'SubscricaoController');
    Route::post('subscricao/data-list', 'SubscricaoController@subscricaoList')->name('subscricao.list');

    //-----------------------Fornecedor-------------------------//
    Route::resource('fornecedor', 'FornecedorController');
    Route::post('vendor/data-list', 'FornecedorController@VendorList')->name('vendor.list');
    Route::post('vendor/data-status', 'FornecedorController@changeStatus')->name('vendor.status');

    //-----------------------Factura---------------------------//
    Route::resource('factura', 'FacturaController');
    Route::post('invoice-list', 'FacturaController@InvoiceList')->name('invoice-list');
    Route::post('invoice-list-client', 'FacturaController@InvoiceClientList')->name('invoice-list-client');
    Route::get('show_payment_history/{id}', 'FacturaController@paymentHistory')->name('paymentHistory');
    Route::get('create-Invoice-view/{id?}', 'FacturaController@CreateInvoiceView');
    Route::get('create-Invoice-view-detail/{id}/{p}', 'FacturaController@CreateInvoiceViewDetail');
    Route::post('getClientDetailById', 'FacturaController@getClientDetailById')->name('getClientDetailById');
    Route::post('add_invoice', 'FacturaController@storeInvoice')->name('store_invoice');
    Route::post('edit_invoice', 'FacturaController@editInvoice')->name('edit_invoice');


    //------------------Agenda----------------------------//
    Route::resource('agenda', 'AppointmentController');
    Route::get('consulta_create', 'AppointmentController@create_consulta')->name('consulta_create');
    Route::get('consulta_index', 'AppointmentController@index_consulta')->name('consulta_index');
    Route::post('appointment/data-list', 'AppointmentController@appointmentList')->name('appointment.list');
    Route::post('getMobileno', 'AppointmentController@getMobileno')->name('getMobileno');

    //------------------Agenda Reuniao----------------------------//
    Route::resource('reuniao', 'AppointmentReuniaoController');
    Route::get('reuniao/{id}/show', 'AppointmentReuniaoController@show')->name('reuniao.show');
    Route::post('appointment/reuniao/data-list', 'AppointmentReuniaoController@appointmentList')->name('appointmentReuniao.list');
    Route::post('getMobileno/reuniao', 'AppointmentReuniaoController@getMobileno')->name('getMobileno');
    //------------------Agenda consulta----------------------------//
    Route::resource('consulta', 'AppointmentConsultaController');
    Route::get('consulta/{id}/show', 'AppointmentConsultaController@show')->name('consulta.show');
    Route::post('appointment/consulta/data-list', 'AppointmentConsultaController@appointmentList')->name('appointmentConsulta.list');
    Route::post('getMobileno/consulta', 'AppointmentConsultaController@getMobileno')->name('getMobileno');

    //----------------------setting case type------------------//
    Route::resource('case-type', 'TipoProcessoController');
    Route::post('cash-type-list', 'TipoProcessoController@cashTypeList')->name('cash.type.list');
    Route::post('cash-type-list/changestatus', 'TipoProcessoController@changeStatus')->name('cash.type.casetype.status');

    //---------------------setting court type--------------------------//
    Route::resource('court-type', 'TipoTribunalController');
    Route::post('court-type-list', 'TipoTribunalController@courtTypeList')->name('court.type.list');
    Route::post('court-type-list/CourtTypeController', 'TipoTribunalController@changeStatus')->name('court.type.courttype.status');

    //configuração do tribunal
    Route::resource('tribunal', 'TribunalController');
    Route::post('court-list', 'TribunalController@cashList')->name('court.list');
    Route::post('court-list/changestatus', 'TribunalController@changeStatus')->name('court.status');

    //setting case status
    Route::resource('case-status', 'EstadoProcessoController');
    Route::post('case-status-list', 'EstadoProcessoController@caseStatusList')->name('case.status.list');
    Route::post('case-status-list/changestatus', 'EstadoProcessoController@changeStatus')->name('case.status');

    //configuração de juiz
    Route::resource('juiz', 'JuizController');
    Route::post('judge-list', 'JuizController@caseStatusList')->name('judge.list');
    Route::post('judge-status-list/changestatus', 'JuizController@changeStatus')->name('judge.status');

    //configuração da secção
    Route::resource('seccao', 'SeccaoController');
    Route::post('seccao-list', 'SeccaoController@caseStatusList')->name('seccao.list');
    Route::post('seccao-status-list/changestatus', 'SeccaoController@changeStatus')->name('seccao.status');


    //configuração de crimenquad
    Route::resource('crime-enquad', 'CrimEnquadController');
    Route::post('crime-enquad-list', 'CrimEnquadController@listarCrimEnquad')->name('crime.enquad.list');
    Route::post('check-exist-enquad-crime', 'CrimEnquadController@checkExistEnquadramentoCrime')->name('check_exist_enquad_crime');


    //configuração de crimeSubenquad
    Route::resource('crime-sub-enquad', 'CrimeSubEnquadController');
    Route::post('crime-sub-enquad-list', 'CrimeSubEnquadController@listarCrimeSubEnquad')->name('crime.sub.enquad.list');
    Route::post('check-exist-subEnquad-crime', 'CrimeSubEnquadController@checkExistSubEnquadramentoCrime')->name('check_exist_subEnquad_crime');

    //configuração de crime
    Route::resource('crime', 'CrimeController');
    Route::post('crime-list', 'CrimeController@listarCrimes')->name('crime.list');
    Route::post('check-exist-tipo-crime', 'CrimeController@checkExistTipoCrime')->name('check_exist_tipo_crime');


    //configuração do órgão judiciário
    Route::resource('orgao-judiciario', 'OrgaoJudiciarioController');
    Route::post('orgaojudiciario-list', 'OrgaoJudiciarioController@listarOrgaosJudiciarios')->name('orgao.judiciario.list');


    //configuração de bairro
    Route::resource('bairro', 'BairroController');
    Route::post('bairro-list', 'BairroController@caseStatusList')->name('bairro.list');
    Route::post('bairro-status-list/changestatus', 'BairroController@changeStatus')->name('bairro.status');
    Route::post('bairro_check_exist', 'BairroController@bairro_check_exist')->name('bairro_check_exist');

    Route::resource('escala-atendimento', 'EscalaTrabalhoController');

    //configuração interv-designacao
    Route::resource('interv-designacao', 'IntervDesignacaoController');
    Route::post('interv-designacao-list', 'IntervDesignacaoController@cashList')->name('interv.designacao.list');
    Route::post('interv-designacao-list/changestatus', 'IntervDesignacaoController@changeStatus')->name('interv.designacao.status');

    //setting Imposto
    Route::resource('tax', 'ImpostoController');
    Route::post('tax-list', 'ImpostoController@taxList')->name('tax.list');
    Route::post('tax-status-list', 'ImpostoController@changeStatus')->name('tax.status');

    Route::resource('database-backup', 'DatabaseBackupController');
    Route::get('database-restore/{id}', 'DatabaseBackupController@restore')->name('database-backup.restore');
    Route::post('database-backup-list', 'DatabaseBackupController@List')->name('database-backup.list');

    //setting invoice setting
    Route::resource('invoice-setting', 'ConfiguracaoFacturaController');

    // Expense type
    Route::resource('expense-type', 'TipoDespesaController');
    Route::post('expense-type-list', 'TipoDespesaController@expenceList')->name('expense.type.list');
    Route::post('expense-type-status-list', 'TipoDespesaController@changeStatus')->name('expense.status');

    Route::resource('expense', 'DespesaController');
    Route::get('expense-create/{id?}', 'DespesaController@expenseCreate');
    Route::post('edit_expense', 'DespesaController@editExpense')->name('edit_expense');
    Route::post('expense-list', 'DespesaController@expenseList')->name('expense-list');
    Route::get('expense-account-list/{id}', 'DespesaController@AccountDetail');
    Route::post('expense-filter-list', 'DespesaController@expenseFilterClientList');
    Route::post('add_expense_payment', 'DespesaController@addExpensePayment')->name('addExpensePayment');
    Route::get('show_payment_made_history/{id}', 'DespesaController@paymentMadeHistory')->name('paymentMadeHistory');

    Route::get('create-expence-view-detail/{id}/{p}', 'DespesaController@CreateExpenseViewDetail');
    Route::post('getVendorDetailById', 'DespesaController@getVendorDetailById')->name('getVendorDetailById');

    //---------------------------Case Running-----------------//
    Route::resource('processo', 'ProcessoController');
    Route::get('registar-processo/{areaprocessual}', 'ProcessoController@create');
    Route::post('allCaseList', 'ProcessoController@allCaseList');
    Route::get('select2Case', 'ProcessoController@select2Case')->name('select2Case');
    Route::get('case-list/{id}', 'ProcessoController@caseListByClientId');
    Route::post('client/client_case_list', 'ProcessoController@client_case_list')->name('client.case_view.list');
    Route::post('allCaseList', 'ProcessoController@allCaseList');
    Route::get('/case-nb', 'ProcessoController@caseNB');
    Route::get('/case-important', 'ProcessoController@caseImportant');
    Route::get('/case-archived', 'ProcessoController@caseArchived');
    Route::post('allCaseHistoryList', 'ProcessoController@allCaseHistoryList');
    Route::get('addNextDate/{case_id}', 'ProcessoController@addNextDate');
    Route::get('restoreCase/{case_id}', 'ProcessoController@restoreCase');
    Route::get('/processo/{id}/docs', 'AutoController@index')->name('processo.docs');
    Route::get('/processo/{id}/comentarios', 'ComentarioController@index')->name('processo.comentarios');
    Route::post('case-next-date', 'ProcessoController@caseNextDate');
    Route::get('/getNextDateModal/{case_id}', 'ProcessoController@getNextDateModal')->name('getnextmodal');
    Route::get('/getChangeCourtModal/{case_id}', 'ProcessoController@getChangeCourtModal')->name('transfermodal');
    Route::get('/case-history/{case_id}', 'ProcessoController@caseHistory');
    Route::get('/case-transfer/{case_id}', 'ProcessoController@caseTransfer');
    Route::get('/getCaseImportantModal/{case_id}', 'ProcessoController@getCaseImportantModal');
    Route::post('allCaseTransferList', 'ProcessoController@allCaseTransferList');
    Route::post('changeCasePriority', 'ProcessoController@changeCasePriority');
    Route::post('transferCaseCourt', 'ProcessoController@transferCaseCourt');
    Route::get('case-running-download/{id}/{action}', 'ProcessoController@downloadPdf');

    //------- Comentario ---------------

    Route::resource('comentario', 'ComentarioController');
    Route::post('get_comentarios/{cod_processo}', 'ComentarioController@getComentarios')->name('get.comentarios');
    Route::get('processo/{cod_processo}/inserir-comentario', 'ComentarioController@create')->name('processo.create.coment');
    Route::post('processo/{cod_processo}/inserir-comentario', 'ComentarioController@store')->name('store.coment');

    //------- Autos ----------------

    Route::resource('auto', 'AutoController');
    Route::get('/processo/{id}/add-docs', 'AutoController@create')->name('processo.add.docs');
    Route::post('auto-list/{id}', 'AutoController@listarAutos')->name('auto.list');
    Route::post('/processo/{id}/inserir-auto', 'AutoController@store')->name('processo.inserir.auto');

    //-----------------------invite member-----------------------//
    Route::resource('client_user', 'ClientUserController');
    Route::post('client-user-list', 'ClientUserController@clientUserList')->name('client-user-list');
    Route::post('client-user/status', 'ClientUserController@changeStatus')->name('client_user.status');
    Route::post('check_user_email_exits', 'ClientUserController@check_user_email_exits')->name('check_user_email_exits');
    Route::post('check_user_name_exits', 'ClientUserController@check_user_name_exits')->name('check_user_name_exits');

    Route::resource('mail-setup', 'SmtpController');
    Route::resource('general-setting', 'ConfiguracaoGeralController');
    Route::get('database-backups', 'ConfiguracaoGeralController@databaseBackup');
    Route::resource('date-timezone', 'GeneralSettingDateController');

    Route::resource('admin-profile', 'PerfilController');
    Route::post('edit-profile', 'PerfilController@editProfile');
    Route::post('image-crop', 'PerfilController@imageCropPost');
    Route::get('change/password', 'PerfilController@change_pass');
    Route::post('changed-password', 'PerfilController@changedPassword');

    //-----------Função----------------------//
    Route::resource('funcao', 'FuncaoController');
    Route::post('role/data-list', 'FuncaoController@roleList')->name('role.list');

    Route::resource('permission', 'PermissaoController');

    //--------------------Serviço--------------------------------//
    Route::resource('servico', 'ServicoController');
    Route::post('service/data-list', 'ServicoController@serviceList')->name('service.list');
    Route::post('service/status', 'ServicoController@changeStatus')->name('service.status');
});
