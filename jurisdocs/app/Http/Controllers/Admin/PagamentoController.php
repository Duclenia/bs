<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\PaymentReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\LogActivity;

class PagamentoController extends Controller
{
    public function store(Request $request)
    {
        /*  try { */
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
        } */
    }
    public function show($id)
    {
        $data['invoice_id'] = $id;
        $html = view('admin.factura.modal_invoice_paid', $data)->render();
        return response()->json(['html' => $html], 200);
    }

    public function paymentHistory($inv_id = null)
    {
        $data['getPaymentHistory'] = DB::table('payment_receiveds AS pr')
            ->leftJoin('factura AS inv', 'pr.factura_id', '=', 'inv.id')
            ->where('pr.factura_id', decrypt($inv_id))
            ->orderby('pr.id', 'DESC')
            ->select('pr.*', 'inv.factura_no', 'inv.id as factura_id')
            ->get();
        $html = view('admin.factura.payment-history', $data)->render();

        return response()->json(['html' => $html], 200);
    }

    public function billHistory($pr_id = null)
    {
        $advocate_id = $this->getLoginUserId();
        $data['getPaymentHistory'] = DB::table('payment_receiveds AS pr')
            ->leftJoin('factura AS inv', 'pr.invoice_id', '=', 'inv.id')
            ->where('pr.advocate_id', $advocate_id)
            ->where('pr.id', $pr_id)
            ->orderby('pr.id', 'DESC')
            ->get();
        return view('admin.factura.payment-history', $data);
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
}
