<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\ItemFactura;
use App\Models\Servico;
use App\Models\ConfiguracaoFactura;
use App\Models\PaymentReceived;
use App\Models\Imposto;
use App\Helpers\LogActivity;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ActivityNotification;
use Auth;
use PDF;
use Mail;
use App\Traits\DatatablTrait;
use App\Models\Mailsetup;
use Illuminate\Contracts\Session\Session as SessionSession;

class FacturaController extends Controller
{

    use DatatablTrait;

    public function __construct()
    {

        $this->middleware('check.subscricao', ['only' => ['create', 'store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getClientDetailByIdEdit($id)
    {

        $records = DB::table('cliente')
            ->where('id', $id)
            ->first();
        $detail = '';
        if (!empty($records)) {

            $detail = '<label  class="discount_text">Facturado para:- </label><br>';

            if ($records->tipo == 2):
                $detail .= '<p  style="color:#333;">' . htmlspecialchars($records->nome) . ' ' . htmlspecialchars($records->sobrenome) . '</p>';
            else:
                $detail .= '<p  style="color:#333;">' . htmlspecialchars($records->instituicao) . '</p>';
            endif;

            if ($records->endereco != '')
                $detail .= '<p style="color:#333;">' . $records->endereco . '</p>';
            if ($records->telefone != '')
                $detail .= '<p style="color:#333;">' . htmlspecialchars($records->telefone) . '</p>';
        }
        return $detail;
    }

    public function getClientDetailById(Request $request)
    {

        $records = DB::table('cliente')
            ->where('id', $request->id)
            ->first();
        $detail = '';

        if (!empty($records)) {

            $detail = '<label  class="discount_text">Billed To:- </label><br>';

            if ($records->tipo == 2):
                $detail .= '<p style="color:#333;">' . htmlspecialchars($records->nome) . ' ' . htmlspecialchars($records->sobrenome) . '</p>';
            else:
                $detail .= '<p style="color:#333;">' . htmlspecialchars($records->instituicao) . '</p>';
            endif;

            if ($records->endereco != '')
                $detail .= '<p style="color:#333;">' . $records->endereco . '</p>';
            if ($records->telefone != '')
                $detail .= '<p style="color:#333;">' . $records->telefone . '</p>';
        }
        return $detail;
    }

    public function index()
    {
        return view('admin.factura.invoice');
    }
    public function index_cliente()
    {

        return view('admin.factura.invoice_cliente');
    }
    public function create()
    {
        $advocate_id = $this->getLoginUserId();

        $data['client_list'] = Cliente::where('activo', 'S')->where('id', $advocate_id)->orderBy('nome', 'asc')->get();
        //generate invoice number
        $data['invoice_no'] = $this->generateInvoice();

        $data['tax_all'] = Imposto::where('activo', 'S')->get();

        $thml = '<select class="form-control taxListCustom" name="tax_id_custom[]"><option value="0" taxsepara="" taxrate="0.00">TAX@0(0.00)</option>';
        if (!empty($tax) && count($tax)) {
            foreach ($tax as $key => $value) {
                $thml .= '<option value="' . $value->id . '" taxsepara="' . $value->name . '" taxrate="' . $value->per . '">' . $value->name . '@ ' . $value->per . '</option>';
            }
        }
        $thml .= '</select>';
        $data['tax'] = "'" . $thml . "'";

        return view('admin.factura.invoice_menu', $data);
    }

    public function CreateInvoiceView($id = "no")
    {

        $user = auth()->user();

        if (!$user->can('invoice_add'))
            return back();

        $data['client_list'] = Cliente::where('activo', 'S')
            ->orderBy('nome', 'asc')
            ->get();

        $data['service_lists'] = Servico::where('activo', 'S')
            ->orderBy('nome', 'asc')
            ->get();

        //generate invoice number
        $data['invoice_no'] = $this->generateInvoice();

        $data['tax'] = Imposto::where('activo', 'S')->get();

        return view('admin.factura.invoice_create', $data);
    }

    public function updateStatusFatura($invoice_id, $status)
    {
        if ($status == 'aprovado') {
            $factura = Factura::findOrFail($invoice_id);
            $factura->status = 'pago';
        }
        if ($status == 'rejeitado') {
            $factura = Factura::findOrFail($invoice_id);
            $factura->status = 'cancelado';
        } else {
            $status = Factura::findOrFail($invoice_id);
            $status->status = "Comprovativo enviado";
        }

        $status->save();
    }

    public function edit($id)
    {

        $user = auth()->user();
        if (!$user->can('invoice_edit'))
            return back();

        $data['client_list'] = Cliente::orderBy('nome', 'asc')->get();

        $data['invoice'] = Factura::with('itensFactura')->findOrFail(decrypt($id));
        $data['iteams'] = ItemFactura::where('factura_id', decrypt($id))->get();
        $data['tax'] = Imposto::all();
        $data['client_detail'] = $this->getClientDetailByIdEdit($data['invoice']->cliente_id);

        $data['service_lists'] = Servico::orderBy('nome', 'asc')->get();

        return view('admin.factura.invoice_edit', $data);
    }

    public function editInvoice(Request $request)
    {

        $this->validate($request, [
            'client_id' => 'required',
            'inc_Date' => 'required',
            'due_Date' => 'required',

        ]);

        $check = $this->check_invoice_exits($request->invoice_id, $request->invoice_id);
        if ($check == "false") {


            return redirect()->route('factura.index');
        }

        $factura = Factura::findOrFail($request->invoice_id);
        $factura->sub_total_amount = $request->subTotal;
        $factura->cliente_id = $request->client_id;
        $factura->tax_amount = $request->taxTotal;
        $factura->total_amount = $request->total;

        $factura->due_date = date('Y-m-d', strtotime(LogActivity::commonDateFromat($request->due_Date)));
        $factura->inv_date = date('Y-m-d', strtotime(LogActivity::commonDateFromat($request->inc_Date)));
        $factura->remarks = $request->note;

        $factura->tax_amount = $request->taxVal;
        $factura->tax_id = $request->tax;
        $factura->save();

        if (!empty($request->invoice_items) && count($request->invoice_items) > 0) {
            $idsarray = collect($request->invoice_items)->pluck('id')->toArray();
            $ids = array_filter($idsarray);

            if (!empty($ids) && count($ids) > 0) {
                $getIdes = ItemFactura::where('factura_id', $request->invoice_id)->whereNotIn('id', $ids)->delete();
            }
        }

        if (!empty($request->invoice_items) && count($request->invoice_items) > 0) {
            foreach ($request->invoice_items as $key => $value) {

                if (isset($value['id']) && !empty($value['id'])) {

                    $itemFactura = ItemFactura::findOrFail($value['id']);
                } else {
                    $itemFactura = new ItemFactura();
                }

                $itemFactura->factura_id = $factura->id;
                $itemFactura->item_descricao = $value['description'];
                $itemFactura->servico_id = $value['services'];
                $itemFactura->item_rate = $value['rate'];
                $itemFactura->iteam_qty = $value['qty'];
                $itemFactura->item_amount = $value['amount'];
                $itemFactura->save();
            }
        }
        return redirect()->route('factura.index');
    }

    public function update(Request $request, $id) {}

    public function CreateInvoiceViewDetail($id, $p)
    {

        $data['setting'] = ConfiguracaoFactura::where('id', "1")->first();

        $data['invoice'] = Factura::with('itensFactura', 'cliente')->find(decrypt($id));
        $term_condition = ConfiguracaoFactura::where('id', 1)->first();
        $data['myTerm'] = [];
        if ($term_condition->termos_condicoes != "") {
            $data['myTerm'] = explode('##', $term_condition->termos_condicoes);
        }
        if (isset($data['invoice']->itensFactura) && count($data['invoice']->itensFactura) > 0) {
            foreach ($data['invoice']->itensFactura as $key => $value) {

                $data['iteam'][$key]['service_name'] = isset($value->servico->nome) ? $value->servico->nome : '';
                $data['iteam'][$key]['custom_items_name'] = $value['item_descricao'];
                $data['iteam'][$key]['hsn'] = $value['hsn'];
                $data['iteam'][$key]['custom_items_amount'] = $value['item_amount'];
                $data['iteam'][$key]['item_rate'] = $value['item_rate'];
                $data['iteam'][$key]['custom_items_qty'] = $value['iteam_qty'];
            }
        }

        $data['advocate_client'] = Cliente::find($data['invoice']->cliente_id);
        $data['invoice_no'] = $data['invoice']->factura_no;

        $data['due_date'] = date('d-m-Y', strtotime(LogActivity::commonDateFromat($data['invoice']->due_date)));
        $data['inv_date'] = date('d-m-Y', strtotime(LogActivity::commonDateFromat($data['invoice']->inv_date)));
        $data['city'] = $this->getCityName($data['advocate_client']->municipio_id);
        $data['subTotal'] = $data['invoice']->sub_total_amount;
        $data['tax_amount'] = $data['invoice']->tax_amount;
        $data['total_amount'] = $data['invoice']->total_amount;

        $data['json_to_array'] = array();

        $data['total_amount_world'] = $this->getIndianCurrency(round($data['invoice']->total_amount)) . " Only.";

        if ($p == "view") {
            return view('admin.factura.invoice_view', $data);
        } else if ($p == "print") {
            $pdf = PDF::loadView('pdf.invoice', $data);
            return $pdf->stream();
        } else if ($p == "email") {
            $mailsetup = Mailsetup::findOrfail(1);
            if ($mailsetup->mail_email != '') {
                $pdf = PDF::loadView('pdf.invoice', $data);
                $input['from'] = $mailsetup->mail_email;
                $input['to'] = $data['advocate_client']->email;
                $input['subject'] = "FACTURA" . $data['invoice_no'];
                $input['title'] = "FACTURA" . $data['invoice_no'];
                $input['pdfName'] = $data['invoice_no'] . ".pdf";

                Mail::send('pdf.invoice', $data, function ($message) use ($pdf, $input) {
                    $message
                        ->from($input['from'], $input['title'])
                        ->subject($input['subject']);
                    $message->to($input['to']);
                    $message->attachData($pdf->output(), $input['pdfName']);
                });
                Session::flash('success', "A factura foi enviada com sucesso para o e-mail do cliente.");
                return back();
            } else {
                Session::flash('error', "Por favor, define primeiro os detalhes do SMTP nas configurações.");
                return back();
            }
        }
    }

    public function checkClientEmailExits($id)
    {
        $cliente = Cliente::findOrFail($id);
        if ($cliente->email == "") {
            return false;
        } else {
            return true;
        }
    }

    public function InvoiceList(Request $request)
    {
        $user = auth()->user();
        $isEdit = $user->can('invoice_edit');
        $isDelete = $user->can('invoice_delete');

        /*
          |----------------
          | Listing colomns
          |----------------
         */
        $columns = array(
            0 => 'id',
            1 => 'invoice_no',
            2 => 'client_id',
            3 => 'total_amount',
            4 => 'dueAmount',
            5 => 'inv_status',
        );

        $totalData = DB::table('factura AS f')
            ->count();

        $totalFiltered = $totalData;
        $totalRec = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            if ($request->agenda_id) {
                $terms = DB::table('factura AS f')
                    ->leftJoin('payment_receiveds As p', 'p.factura_id', '=', 'f.id')
                    ->leftJoin('cliente As c', 'c.id', '=', 'f.cliente_id')
                    ->select('f.factura_no', 'f.total_amount')
                    ->selectRaw('sum(p.amount) as paidAmount')
                    ->selectRaw('f.total_amount-SUM(ifnull(p.amount, 0)) AS dueAmount')
                    ->where('f.agenda_id', $request->agenda_id)
                    ->groupBy('f.factura_no')
                    ->groupBy('f.total_amount')
                    ->offset($start)
                    ->limit($limit)
                    ->get();
            } else {

                $terms = DB::table('factura AS f')
                    ->leftJoin('payment_receiveds As p', 'p.factura_id', '=', 'f.id')
                    ->leftJoin('cliente As c', 'c.id', '=', 'f.cliente_id')
                    ->select('f.factura_no', 'f.total_amount')
                    ->selectRaw('sum(p.amount) as paidAmount')
                    ->selectRaw('f.total_amount-SUM(ifnull(p.amount, 0)) AS dueAmount')
                    ->groupBy('f.factura_no')
                    ->groupBy('f.total_amount')
                    ->offset($start)
                    ->limit($limit)
                    ->get();
            }
        } else {
            /*
              |--------------------------------------------
              | For table search filter from frontend site inside two table namely courses and courseterms.
              |--------------------------------------------
             */
            $search = $request->input('search.value');

            $terms = DB::table('factura AS f')
                ->leftJoin('payment_receiveds As p', 'p.factura_id', '=', 'f.id')
                ->leftJoin('cliente As c', 'c.id', '=', 'f.cliente_id')
                ->select('f.factura_no', 'f.total_amount')
                ->selectRaw('sum(p.amount) as paidAmount')
                ->selectRaw('f.total_amount-SUM(ifnull(p.amount, 0)) AS dueAmount')
                ->where('f.activo', 'S')
                ->where(function ($quary) use ($search) {
                    $quary->where('f.factura_no', 'LIKE', "%{$search}%")
                        ->orWhere('f.total_amount', 'LIKE', "%{$search}%");
                })
                ->groupBy('f.factura_no')
                ->groupBy('f.total_amount')
                ->offset($start)
                ->limit($limit)
                ->get();

            $totalFiltered = collect($terms)->count();
        }

        $data = array();
        if (!empty($terms)) {
            foreach ($terms as $Key => $term) {

                $factura = DB::table('factura AS f')
                    ->leftJoin('payment_receiveds As p', 'p.factura_id', '=', 'f.id')
                    ->leftJoin('cliente As c', 'c.id', '=', 'f.cliente_id')
                    ->select('f.id AS id', 'p.amount', 'f.total_amount As total_amount', 'f.inv_date', 'f.due_date', 'c.nome AS cl_nome', 'c.sobrenome AS cl_sobrenome', 'c.tipo AS cl_tipo', 'c.instituicao AS cl_instituicao', 'f.status', 'c.id as client_id')
                    ->where('f.activo', 'S')
                    ->where('f.factura_no', $term->factura_no)
                    ->first();

                /**
                 * For HTMl action option like edit and delete
                 */
                $token = csrf_field();
                $clientLink = url('admin/client-account-list/' . $factura->client_id);
                $action_delete = route('factura.destroy', $factura->id);
                $delete = "<form action='{$action_delete}' method='post' onsubmit ='return  confirmDelete()'>
                {$token}
                            <input name='_method' type='hidden' value='DELETE'>
                            <button class='dropdown-item text-center' type='submit'  style='background: transparent;
    border: none;'><i class='fa fa-trash fa-1x'></i> Delete</button>
                          </form>";

                $view = url('admin/create-Invoice-view-detail/' . encrypt($factura->id) . '/view');
                $email = url('admin/create-Invoice-view-detail/' . $factura->id . '/email');

                $chk = $this->checkClientEmailExits($factura->client_id);

                /**
                 * -/End
                 */
                if ($chk) {
                    $email = "<li style='text-align:left'><a class='dropdown-item' href='{$email}' title='Enviar factura por e-mail para o cliente'>&nbsp;&nbsp;<i class='fa fa-envelope'></i>
                                                        Email</a></li>";
                } else {
                    $email = "<li style='text-align:left'><a class='dropdown-item' href='javascript:void(0);' style='cursor: no-drop;' title='Client don`t have email'>&nbsp;&nbsp;<i class='fa fa-envelope'></i>
                                                    Email</a></li>";
                }

                if (empty($request->input('search.value'))) {
                    $final = $totalRec - $start;
                    $nestedData['id'] = $final;
                    $totalRec--;
                } else {
                    $start++;
                    $nestedData['id'] = $start;
                }
                if ($factura->cl_tipo == 2):
                    $nestedData['name'] = '<div  style="font-size:15px;"  class="clinthead text-primary"><a  class="title text-primary" href="">' . htmlspecialchars($factura->cl_nome) . " " . htmlspecialchars($factura->cl_sobrenome) . '</b></a>&nbsp;</div><p class="currenttittle"><i class="fa fa-calendar-check-o text-success"></i>&nbsp;' . date(LogActivity::commonDateFromatType(), strtotime($factura->inv_date)) . '</b></p>';
                else:
                    $nestedData['name'] = '<div  style="font-size:15px;"  class="clinthead text-primary"><a  class="title text-primary" href="">' . htmlspecialchars($factura->cl_instituicao) . '</b></a>&nbsp;</div><p class="currenttittle"><i class="fa fa-calendar-check-o text-success"></i>&nbsp;' . date(LogActivity::commonDateFromatType(), strtotime($factura->inv_date)) . '</b></p>';
                endif;

                $nestedData['total_amount'] = $term->total_amount;
                $nestedData['paid_amount'] = ($term->paidAmount != '') ? $term->paidAmount : '0';

                $nestedData['due_amount'] = '<p class="currenttittle text-danger"><i class="fa fa-calendar-times-o"></i> ' . date(LogActivity::commonDateFromatType(), strtotime($factura->due_date)) . ' </p></b>';

                $nestedData['invoice_no'] = '<a href="' . $view . '" class="text-primary">' . $term->factura_no . '</a>';
                $nestedData['status'] = $factura->status;


                if ($isEdit == "1" || $isDelete == "1") {

                    $nestedData['options'] = $this->action([
                        'view' => url('admin/create-Invoice-view-detail/' . encrypt($factura->id) . '/view'),
                        'edit' => route('factura.edit', encrypt($factura->id)),
                        'delete' => collect([
                            'id' => $factura->id,
                            'action' => route('factura.destroy', $factura->id),
                        ]),
                        'print' => url('admin/create-Invoice-view-detail/' . encrypt($factura->id) . '/print'),
                        'payment_recevie_modal' => collect([
                            'id' => $factura->id,
                            'action' => route('factura.show', $factura->id),
                            'target' => '#clientPaymentreceivemodal'
                        ]),
                        'payment_histroy_modal' => collect([
                            'id' => $factura->id,
                            'action' => url('admin/show_payment_history/' . encrypt($factura->id)),
                            'target' => '#clientPaymenthistroymodal'
                        ]),
                        'delete_permission' => $isDelete,
                        'edit_permission' => $isEdit,
                    ]);
                } else {
                    $nestedData['options'] = $this->action([
                        'view' => url('admin/create-Invoice-view-detail/' . $factura->id . '/view'),
                        'print' => url('admin/create-Invoice-view-detail/' . $factura->id . '/print'),
                        'payment_histroy_modal' => collect([
                            'id' => $factura->id,
                            'action' => url('admin/show_payment_history/' . $factura->id),
                            'target' => '#clientPaymenthistroymodal'
                        ]),
                    ]);
                }

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function agendaFacturas($agenda_id)
    {
        $data['agenda_id'] = decrypt($agenda_id);
        $agenda = DB::table('factura')->join('agenda', 'agenda.id', '=', 'factura.agenda_id')
            ->where('agenda.id', decrypt($agenda_id))->first();
        $data['agenda_info'] = $agenda;

        if ($data['agenda_info']) {
            return view('admin.factura.agenda_invoices', $data);
        } else {
            return redirect()->back()->with('error', 'Agenda sem factura.');
        }
    }

    public function InvoiceClientList(Request $request)
    {

        $user = auth()->user();
        $isEdit = $user->can('invoice_edit');
        $isDelete = $user->can('invoice_delete');
        $id_cliente = $user->cliente->id;

        /*
          |----------------
          | Listing colomns
          |----------------
         */
        $columns = array(
            0 => 'id',
            1 => 'invoice_no',
            2 => 'client_id',
            3 => 'total_amount',
            4 => 'dueAmount',
            5 => 'inv_status',
        );

        $totalData = DB::table('factura AS f')
            ->count();

        $totalFiltered = $totalData;
        $totalRec = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $terms = DB::table('factura AS f')
                ->leftJoin('payment_receiveds As p', 'p.factura_id', '=', 'f.id')
                ->leftJoin('cliente As c', 'c.id', '=', 'f.cliente_id')
                ->select('f.factura_no', 'f.total_amount')
                ->selectRaw('sum(p.amount) as paidAmount')
                ->selectRaw('f.total_amount-SUM(ifnull(p.amount, 0)) AS dueAmount')
                ->where('f.cliente_id', $id_cliente)
                ->groupBy('f.factura_no')
                ->groupBy('f.total_amount')
                ->offset($start)
                ->limit($limit)
                ->get();
        } else {
            /*
              |--------------------------------------------
              | For table search filter from frontend site inside two table namely courses and courseterms.
              |--------------------------------------------
             */
            $search = $request->input('search.value');

            $terms = DB::table('factura AS f')
                ->leftJoin('payment_receiveds As p', 'p.factura_id', '=', 'f.id')
                ->leftJoin('cliente As c', 'c.id', '=', 'f.cliente_id')
                ->select('f.factura_no', 'f.total_amount')
                ->selectRaw('sum(p.amount) as paidAmount')
                ->selectRaw('f.total_amount-SUM(ifnull(p.amount, 0)) AS dueAmount')
                ->where('f.activo', 'S')
                ->where('f.cliente_id', $id_cliente)
                ->where(function ($quary) use ($search) {
                    $quary->where('f.factura_no', 'LIKE', "%{$search}%")
                        ->orWhere('f.total_amount', 'LIKE', "%{$search}%");
                })
                ->groupBy('f.factura_no')
                ->groupBy('f.total_amount')
                ->offset($start)
                ->limit($limit)
                ->get();

            $totalFiltered = collect($terms)->count();
        }

        $data = array();
        if (!empty($terms)) {
            foreach ($terms as $Key => $term) {

                $factura = DB::table('factura AS f')
                    ->leftJoin('payment_receiveds As p', 'p.factura_id', '=', 'f.id')
                    ->leftJoin('cliente As c', 'c.id', '=', 'f.cliente_id')
                    ->select('f.id AS id', 'p.amount', 'f.total_amount As total_amount', 'f.inv_date', 'f.due_date', 'c.nome AS cl_nome', 'c.sobrenome AS cl_sobrenome', 'c.tipo AS cl_tipo', 'c.instituicao AS cl_instituicao', 'f.status', 'c.id as client_id')
                    ->where('f.activo', 'S')
                    ->where('f.cliente_id', $id_cliente)
                    ->where('f.factura_no', $term->factura_no)
                    ->first();

                /**
                 * For HTMl action option like edit and delete
                 */
                $token = csrf_field();
                $clientLink = url('admin/client-account-list/' . $factura->client_id);
                $action_delete = route('factura.destroy', $factura->id);
                $delete = "<form action='{$action_delete}' method='post' onsubmit ='return  confirmDelete()'>
                {$token}
                            <input name='_method' type='hidden' value='DELETE'>
                            <button class='dropdown-item text-center' type='submit'  style='background: transparent;
    border: none;'><i class='fa fa-trash fa-1x'></i> Delete</button>
                          </form>";

                $view = url('admin/create-Invoice-view-detail/' . encrypt($factura->id) . '/view');
                $email = url('admin/create-Invoice-view-detail/' . $factura->id . '/email');

                $chk = $this->checkClientEmailExits($factura->client_id);

                /**
                 * -/End
                 */
                if ($chk) {
                    $email = "<li style='text-align:left'><a class='dropdown-item' href='{$email}' title='Enviar factura por e-mail para o cliente'>&nbsp;&nbsp;<i class='fa fa-envelope'></i>
                                                        Email</a></li>";
                } else {
                    $email = "<li style='text-align:left'><a class='dropdown-item' href='javascript:void(0);' style='cursor: no-drop;' title='Client don`t have email'>&nbsp;&nbsp;<i class='fa fa-envelope'></i>
                                                    Email</a></li>";
                }

                if (empty($request->input('search.value'))) {
                    $final = $totalRec - $start;
                    $nestedData['id'] = $final;
                    $totalRec--;
                } else {
                    $start++;
                    $nestedData['id'] = $start;
                }
                if ($factura->cl_tipo == 2):
                    $nestedData['name'] = '<div  style="font-size:15px;"  class="clinthead text-primary"><a  class="title text-primary" href="">' . htmlspecialchars($factura->cl_nome) . " " . htmlspecialchars($factura->cl_sobrenome) . '</b></a>&nbsp;</div><p class="currenttittle"><i class="fa fa-calendar-check-o text-success"></i>&nbsp;' . date(LogActivity::commonDateFromatType(), strtotime($factura->inv_date)) . '</b></p>';
                else:
                    $nestedData['name'] = '<div  style="font-size:15px;"  class="clinthead text-primary"><a  class="title text-primary" href="">' . htmlspecialchars($factura->cl_instituicao) . '</b></a>&nbsp;</div><p class="currenttittle"><i class="fa fa-calendar-check-o text-success"></i>&nbsp;' . date(LogActivity::commonDateFromatType(), strtotime($factura->inv_date)) . '</b></p>';
                endif;

                $nestedData['total_amount'] = $term->total_amount;
                $nestedData['paid_amount'] = ($term->paidAmount != '') ? $term->paidAmount : '0';

                $nestedData['due_amount'] = '<p class="currenttittle">' . $term->dueAmount . '</p></b><p class="currenttittle text-danger"><i class="fa fa-calendar-times-o"></i> ' . date(LogActivity::commonDateFromatType(), strtotime($factura->due_date)) . ' </p></b>';

                $nestedData['invoice_no'] = '<a href="' . $view . '" class="text-primary">' . $term->factura_no . '</a>';
                $nestedData['status'] = $factura->status;


                if ($isEdit == "1" || $isDelete == "1") {

                    $nestedData['options'] = $this->action([
                        'view' => url('admin/create-Invoice-view-detail/' . encrypt($factura->id) . '/view'),
                        'edit' => route('factura.edit', encrypt($factura->id)),
                        'delete' => collect([
                            'id' => $factura->id,
                            'action' => route('factura.destroy', $factura->id),
                        ]),
                        'print' => url('admin/create-Invoice-view-detail/' . encrypt($factura->id) . '/print'),
                        'payment_recevie_modal' => collect([
                            'id' => $factura->id,
                            'action' => route('factura.show', $factura->id),
                            'target' => '#clientPaymentreceivemodal'
                        ]),
                        'payment_histroy_modal' => collect([
                            'id' => $factura->id,
                            'action' => url('admin/show_payment_history/' . encrypt($factura->id)),
                            'target' => '#clientPaymenthistroymodal'
                        ]),
                        'delete_permission' => $isDelete,
                        'edit_permission' => $isEdit,
                    ]);
                } else {
                    $nestedData['options'] = $this->action([
                        'view' => url('admin/create-Invoice-view-detail/' . encrypt($factura->id) . '/view'),
                        'print' => url('admin/create-Invoice-view-detail/' . $factura->id . '/print'),
                        'payment_recevie_modal' => collect([
                            'id' => $factura->id,
                            'action' => route('factura.show', $factura->id),
                            'target' => '#clientPaymentreceivemodal'
                        ]),
                        'payment_histroy_modal' => collect([
                            'id' => $factura->id,
                            'action' => url('admin/show_payment_history/' . encrypt($factura->id)),
                            'target' => '#clientPaymenthistroymodal'
                        ])
                    ]);
                }

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function checkClientEmail(Request $request)
    {
        $client_id = $request->client_id;

        $cliente = Cliente::findOrFail($client_id);

        if ($cliente->email == "") {
            $json_data = array("success" => false);
        } else {
            $json_data = array("success" => true);
        }
        return response()->json($json_data, 200);
    }

    public function check_client_email_exits(Request $request)
    {
        if ($request->id == "") {
            $count = Cliente::where('email', $request->email)->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            $count = Cliente::where('email', $request->email)
                ->where('id', '<>', $request->id)
                ->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        }
    }

    public function getCityName($id)
    {
        $records = DB::table('municipio')->where('id', $id)->first();
        if (isset($records) && !empty($records)) {
            return $records->nome;
        }
        return "";
    }

    public function invoiceView($id, $tax = "")
    {
        $advocate_id = $this->getLoginUserId();
        $records = Cliente::findOrfail($id);

        $data['email'] = htmlspecialchars($records->email);

        $detail = '';
        if (!empty($records)) {

            $detail = '<label  class="discount_text">Billed To:- </label><br>';
            $detail .= '<p style="color:#333;">' . htmlspecialchars($records->full_name) . '</p>';
            if ($records->endereco != '')
                $detail .= '<p style="color:black;">' . htmlspecialchars($records->endereco) . '</p>';
            if ($records->email != '')
                $detail .= '<p style="color:black;">' . htmlspecialchars($records->email) . '</p>';
            if ($records->telefone != '')
                $detail .= '<p style="color:black;">' . htmlspecialchars($records->telefone) . '</p>';
        }
        $data['client_detail'] = $detail;

        //generate invoice number
        $data['invoice_no'] = $this->generateInvoice();

        $taxs = Imposto::where('activo', 'S')->where('nome', $tax)
            ->where(function ($tax) use ($advocate_id) {
                $tax->where('advocate_id', 0)
                    ->orWhere('advocate_id', $advocate_id);
            })->get();

        $thml = '<option value="0" taxsepara="" taxrate="0.00">' . $tax . ' 0 %</option>';
        if (!empty($taxs) && count($taxs)) {
            foreach ($taxs as $key => $value) {
                $thml .= '<option value="' . $value->id . '" taxsepara="' . $value->name . '" taxrate="' . $value->per . '">' . $value->name . ' ' . $value->per . ' %</option>';
            }
        }

        $data['tax'] = "'" . $thml . "'";


        return view('admin.factura.add_invoice', $data);
    }

    public function storeInvoice(Request $request)
    {

        if (empty($request->invoice_items)) {
            Session::flash('error', "Something want wrong.");
            return back();
        }

        $this->validate($request, [
            'client_id' => 'required',
            'inc_Date' => 'required',
            'due_Date' => 'required',

        ]);

        $check = $this->check_invoice_exits("", $request->invoice_id);
        if ($check == "false") {
            Session::flash('error', "Faild to data");
            return redirect()->route('factura.index');
        }

        $factura = new Factura();

        $factura->cliente_id = $request->client_id;
        $factura->sub_total_amount = $request->subTotal;
        $factura->tax_amount = $request->taxVal;
        $factura->total_amount = $request->total;
        $factura->due_date = date('Y-m-d', strtotime(LogActivity::commonDateFromat($request->due_Date)));
        $factura->inv_date = date('Y-m-d', strtotime(LogActivity::commonDateFromat($request->inc_Date)));
        $factura->agenda_id = $request->agenda_id;
        $factura->status = 'pendente';
        $factura->remarks = $request->note;
        $factura->factura_no = $request->invoice_id;
        $factura->invoice_created_by = auth()->user()->id;
        $factura->tax_type = $request->tex_type;
        $factura->tax_id = $request->tax;
        $factura->save();

        //increment count of invoice setting
        $setting = ConfiguracaoFactura::where('id', "1")->first();
        $setting->factura_no = $setting->factura_no + 1;
        $setting->save();

        $resp = array();

        if (!empty($request->invoice_items) && count($request->invoice_items) > 0) {
            foreach ($request->invoice_items as $key => $value) {
                $itemFactura = new ItemFactura();
                $itemFactura->factura_id = $factura->id;
                $itemFactura->item_descricao = $value['description'];
                $itemFactura->servico_id = $value['services'];
                $itemFactura->item_rate = $value['rate'];
                $itemFactura->iteam_qty = $value['qty'];
                $itemFactura->item_amount = $value['amount'];
                $itemFactura->save();
            }
        }

        Session::flash('success', "Factura gerada.");

        return redirect()->route('factura.index');
    }
    public function storeFactura(Request $request)
    {

        if (empty($request->invoice_items)) {
            return back();
        }

        $this->validate($request, [
            'client_id' => 'required',
            'inc_Date' => 'required',
            'due_Date' => 'required',

        ]);

        $check = $this->check_invoice_exits("", $request->invoice_id);
        if ($check == "false") {
            return back();
        }

        $factura = new Factura();

        $factura->cliente_id = $request->client_id;
        $factura->sub_total_amount = $request->subTotal;
        $factura->tax_amount = $request->taxVal;
        $factura->total_amount = $request->total;
        $factura->due_date = date('Y-m-d', strtotime(LogActivity::commonDateFromat($request->due_Date)));
        $factura->inv_date = date('Y-m-d', strtotime(LogActivity::commonDateFromat($request->inc_Date)));
        $factura->agenda_id = $request->agenda_id;
        $factura->status = 'pendente';
        $factura->remarks = $request->note;
        $factura->factura_no = $request->invoice_id;
        $factura->invoice_created_by = auth()->user()->id;
        $factura->tax_type = $request->tex_type;
        $factura->tax_id = $request->tax;
        $factura->save();

        //increment count of invoice setting
        $setting = ConfiguracaoFactura::where('id', "1")->first();
        $setting->factura_no = $setting->factura_no + 1;
        $setting->save();

        $resp = array();

        if (!empty($request->invoice_items) && count($request->invoice_items) > 0) {
            foreach ($request->invoice_items as $key => $value) {
                $itemFactura = new ItemFactura();
                $itemFactura->factura_id = $factura->id;
                $itemFactura->item_descricao = $value['description'];
                $itemFactura->servico_id = $value['services'];
                $itemFactura->item_rate = $value['rate'];
                $itemFactura->iteam_qty = $value['qty'];
                $itemFactura->item_amount = $value['amount'];
                $itemFactura->save();
            }
        }
    }

    public function sendMail($id)
    {
        $data['invoice'] = Factura::with('itensFactura', 'cliente')->findOrFail($id);
        if (isset($data['invoice']->itensFactura) && count($data['invoice']->itensFactura) > 0) {

            foreach ($data['invoice']->itensFactura as $key => $value) {
                $data['iteam'][$key]['custom_items_name'] = $value['item_description'];
                $data['iteam'][$key]['hsn'] = $value['hsn'];
                $data['iteam'][$key]['custom_items_amount'] = $value['item_amount'];
                $data['iteam'][$key]['item_rate'] = $value['item_rate'];
                $data['iteam'][$key]['tax_id_custom'] = $this->getTax($value['tax_id']);
                $data['iteam'][$key]['custom_items_qty'] = $value['iteam_qty'];

                $taxRate = $this->getTax($value['tax_id']);
                $price = $value['item_rate'] * $value['iteam_qty'];
                $taxAmount = number_format((($price * $taxRate) / 100), 2);
                $data['iteam'][$key]['tax'] = $taxAmount;
            }
        }

        $data['advocate_client'] = Cliente::findOrFail($data['invoice']->cliente_id);
        $data['invoice_no'] = $data['invoice']->factura_no;
        $data['due_date'] = date('d-m-Y', strtotime($data['invoice']->due_date));
        $data['inv_date'] = date('d-m-Y', strtotime($data['invoice']->inv_date));
        $data['city'] = $this->getCityName($data['advocate_client']->city_id);
        $data['subTotal'] = $data['invoice']->sub_total_amount;
        $data['tax_amount'] = $data['invoice']->tax_amount;
        $data['total_amount'] = $data['invoice']->total_amount;

        $term_condition = ConfiguracaoFactura::where('advocate_id', $data['advocate_client']->advocate_id)->first();

        $data['myTerm'] = [];
        if ($term_condition->term_condition != "") {
            $data['myTerm'] = explode('##', $term_condition->term_condition);
        }
        $data['json_to_array'] = array();

        // for gst
        if ($data['invoice']->tax_type == "GST" || $data['invoice']->tax_type == "IGST") {
            $data['json_to_array'] = json_decode($data['invoice']->json_content, true);
        }
        $data['tax_type'] = $data['invoice']->tax_type;
        $data['total_amount_world'] = $this->getIndianCurrency($data['invoice']->total_amount) . " Only.";
        $pdf = PDF::loadView('pdf.invoice', $data);

        $input['from'] = "care@advocatesdiary.in";
        $input['to'] = $data['advocate_client']->email;
        $input['subject'] = "FACTURA " . $data['invoice_no'];
        $input['title'] = "FACTURA " . $data['invoice_no'];
        $input['pdfName'] = $data['invoice_no'] . ".pdf";

        Mail::send('pdf.invoice', $data, function ($message) use ($pdf, $input) {
            $message
                ->from($input['from'], $input['title'])
                ->subject($input['subject']);
            $message->to($input['to']);
            $message->attachData($pdf->output(), $input['pdfName']);
            // $message->attach($pdf->output());
        });
    }

    public function getTax($id)
    {
        $tax = Imposto::where('id', $id)->first();
        $name = "0";
        if (!empty($tax)) {
            $name = $tax->per;
        }

        return $name;
    }

    public function calculateDiscountPrice($p, $d)
    {
        if ($d == "") {
            $d = 0;
        }
        $discount = ($d * $p) / 100;
        $result = $p - $discount;
        return round($result);
    }

    public function generateInvoice()
    {
        $data['setting'] = ConfiguracaoFactura::where('id', 1)->first();

        if (empty($data['setting'])) {
            $inv = new ConfiguracaoFactura();
            $inv->id = '1';
            $inv->prefixo = "INV-";
            $inv->save();
        }

        return $this->addPrefix();
    }

    public function generateReceiptNo()
    {
        $setting = ConfiguracaoFactura::find(1);
        $receipt = $setting->receipt_no + 1;
        return $receipt;
    }

    public function addPrefix()
    {
        $setting = ConfiguracaoFactura::where('id', "1")->first();
        $inv = $setting->factura_no + 1;
        $inv = $my_val = str_pad($inv, 6, '0', STR_PAD_LEFT);
        $prefix = "";
        $prefix = $setting->prefixo;

        $for = "";
        if ($setting->formato_factura == 1) {
            $for = $prefix . $inv;
        } else if ($setting->formato_factura == 2) {
            $for = $prefix . date('Y') . '/' . $inv;
        } else if ($setting->formato_factura == 3) {
            $for = $prefix . $inv . '-' . date('y');
        } else if ($setting->formato_factura == 4) {
            $for = $prefix . $inv . '/' . date('m') . '/' . date('y');
        }
        return $for;
    }


    public function updateStatus(Request $request)
    {
        try {
            $paymentId = $request->payment_id;
            $status = $request->status;

            DB::table('payment_receiveds')
                ->where('id', $paymentId)
                ->update(['status' => $status]);

            $this->updateStatusFatura($request->factura_id, $status);

            return response()->json(['success' => true, 'message' => 'Status atualizado com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar status']);
        }
    }

    public function destroy($id)
    {
        $factura = Factura::findOrFail($id);
        $factura->activo = 'N';

        $factura->save();

        return response()->json([
            'success' => true,
            'message' => 'Factura eliminada.'
        ], 200);
    }

    public function check_invoice_exits($id, $data)
    {
        if ($id == "") {
            $count = Factura::where('factura_no', $data)->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            $count = Factura::where('factura_no', '=', $data)
                ->where('id', '<>', $id)
                ->count();
            if ($count == 0) {
                return 'true';
            } else {
                return 'false';
            }
        }
    }


    function getIndianCurrency(float $number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            0 => '',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'forty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety'
        );
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_length) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
            } else
                $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
    }
}
