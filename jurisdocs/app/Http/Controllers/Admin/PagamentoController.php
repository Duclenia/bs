<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\PaymentReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\LogActivity;
use App\Models\Cliente;
use App\Models\ConfiguracaoFactura;
use PDF;
use App\Models\Mailsetup;
use Faker\Provider\ar_SA\Payment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class PagamentoController extends Controller
{
    private $factura;


    public function __construct(FacturaController $factura)
    {
        $this->factura = $factura;
    }
    /*  public function store(Request $request)
    {
        /*  try {
        $getInvoice_Detail = Factura::findOrFail($request->invoice_id);
        $receiptNo = $this->generateReceiptNo();

        $pdf = null;
        if ($request->hasFile('comprovativo')) {
            $pdf = $this->uploadComprovativo($request);
        }

        $paymentReceived = new PaymentReceived();
        $paymentReceived->cliente_id = $getInvoice_Detail->cliente_id;
        $paymentReceived->factura_id = $request->invoice_id;
        $paymentReceived->receipt_number = $receiptNo;
        $paymentReceived->amount = $request->amount;
        $paymentReceived->receiving_date = date('Y-m-d H:i:s');
        $paymentReceived->cheque_date = (!empty($request->cheque_date)) ? date('Y-m-d H:i:s', strtotime(LogActivity::commonDateFromat($request->cheque_date))) : null;
        $paymentReceived->payment_type = $request->method;
        $paymentReceived->reference_number = $request->referance_number;
        $paymentReceived->note = $request->note;
        $paymentReceived->status = 'pendente';
        $paymentReceived->comprovativo = $pdf;
        $paymentReceived->payment_received_by = auth()->user()->id;
        $paymentReceived->save();

        $this->updateStatusFatura($request->invoice_id, $request->status);

        return response()->json(['success' => 'Pagamento registado com sucesso.'], 200);
        /*   } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    } */

    public function registarPagamento(Request $request, $cliente_id, $factura_id)
    {

        $pdf = null;
        if ($request->hasFile('comprovativo')) {
            $pdf = $this->uploadComprovativo($request);
        }

        $paymentReceived = PaymentReceived::create([
            'cliente_id' => $cliente_id,
            'factura_id' => $factura_id,
            'amount' => $request->custo,
            'receiving_date' => date('Y-m-d H:i:s'),
            'payment_type' => ucfirst($request->forma_pagamento),
            'reference_number' => $request->referencia_pagamento,
            'note' => $request->observacoes_pagamento,
            'comprovativo' => $pdf,
            'status' => 'pendente',
            'payment_received_by' => auth()->user()->id,
        ]);

        if ($paymentReceived) {
            return $this->CreateInvoiceViewDetail(encrypt($factura_id), 'print');
        } else {
            Session::flash('error', 'Pagamento não registado.');
        }
    }
    public function show($id)
    {
        $data['invoice_id'] = $id;
        $html = view('admin.factura.modal_invoice_paid', $data)->render();
        return response()->json(['html' => $html], 200);
    }

  
    public function uploadComprovativo(Request $request)
    {
        $request->validate([
            'comprovativo' => 'required|file|mimes:pdf|max:10240'
        ]);

        try {
            $file = $request->file('comprovativo');
            $path = $file->store('agendamento/comprovativos', 'public');
            return $path;
        } catch (\Exception $e) {
            return null;
        }
    }
    public function generateReceiptNo()
    {
        $setting = ConfiguracaoFactura::find(1);
        $receipt = $setting->receipt_no + 1;
        return $receipt;
    }
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
        $data['city'] = $this->factura->getCityName($data['advocate_client']->municipio_id);
        $data['subTotal'] = $data['invoice']->sub_total_amount;
        $data['tax_amount'] = $data['invoice']->tax_amount;
        $data['total_amount'] = $data['invoice']->total_amount;

        $data['json_to_array'] = array();

        $data['total_amount_world'] = $this->factura->getIndianCurrency(round($data['invoice']->total_amount)) . " Only.";

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
}
