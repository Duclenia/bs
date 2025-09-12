<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Factura#{{ $invoice_no ?? '' }} | Advocate Diary</title>
    <style type="text/css">
        @media print {
            body {
                margin: 3mm 8mm 5mm 5mm;
            }
        }

        @page {
            margin: 3mm 8mm 5mm 5mm;
        }
    </style>
</head>

<body>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">

        <tr>
            <td>
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td width="25%">
                            @if ($setting->logo_img != '')
                                <img
                                    src="{{ public_path(config('constants.LOGO_FOLDER_PATH') . '/' . $setting->logo_img) }}">
                            @else
                                <img src="{{ public_path('uploads/logo.png') }}">
                            @endif
                        </td>

                        <td width="75%" valign="top">
                            <h2 class="heading1" style="text-align: center; margin: 0; padding: 20px;">
                                <b> FACTURA PERFORMACE</b>
                            </h2>


                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <hr>
    <br>

    <table border="1" cellpadding="0" cellspacing="0" width="100%"
        style="border-collapse:collapse;font-size: 13px;border: 1px solid #000;">

        <tr>
            <td>
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td width="64%">
                            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                                <tr>
                                    <td width="96%" style="font-size: 12px;">
                                        <b> Factura para:</b>
                                        @if ($advocate_client->tipo == 2)
                                            {{ $advocate_client->nome . ' ' . $advocate_client->sobrenome }}
                                        @else
                                            {{ $advocate_client->instituicao }}
                                        @endif
                                        <br>
                                        @if ($advocate_client->endereco)
                                            <strong>Endereço: </strong>{{ $advocate_client->endereco }}
                                            <br>
                                        @endif
                                        @if ($advocate_client->telefone)
                                            <strong>Telefone:</strong> {{ $advocate_client->telefone }}
                                            <br>
                                        @endif
                                        @if ($advocate_client->email)
                                            <strong>Email:</strong> {{ $advocate_client->email }}
                                            <br>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="36%" valign="top">
                            <table width="100%" border="0" cellpadding="4" cellspacing="0"
                                style="border-left:1px solid black;">
                                <tr>
                                    <td width="50%"><strong>Factura No.</strong></td>
                                    <td width="50%">: {{ $invoice_no ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td style=""><strong>Data da factura:</strong></td>
                                    <td style="">: {{ $inv_date ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:0px solid black;"><strong>Data limite de pagto:</strong>
                                    </td>
                                    <td style="border-bottom:0px solid black;">: {{ $due_date ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:0px solid black;" colspan="2"></td>
                                    <td style="border-bottom:0px solid black;" colspan="2"></td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:0px solid black;" colspan="2"></td>
                                    <td style="border-bottom:0px solid black;" colspan="2"></td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>


        <tr>
            <td height="0" style="border: 0px solid #fff;" border="0" valign="top">
                <table width="100%" border="0" cellpadding="2" cellspacing="0">
                    <tr>
                        <td width="3%" align="center"
                            style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 9pt;">
                            <strong>Sr.</strong>
                        </td>
                        <td width="25%" align="left"
                            style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 9pt;">
                            <strong>Servi&ccedil;o</strong>
                        </td>
                        <td width="50%" align="left"
                            style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 9pt;">
                            <strong>Descri&ccedil;&atilde;o</strong>
                        </td>

                        <td width="9%" align="center"
                            style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 9pt;">
                            <strong>Quantidade</strong>
                        </td>
                        <td width="7%" align="center"
                            style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 9pt;">
                            <strong>Rate</strong>
                        </td>

                        <td width="15%" align="center" style="border-bottom: 1px solid #000;font-size: 9pt;">
                            <strong>Net
                                <br>Valor</strong>
                        </td>
                    </tr>


                    @php $i=1; @endphp
                    @if (!empty($iteam) && count($iteam) > 0)
                        @foreach ($iteam as $key => $value)
                            <tr>
                                <td align="center"
                                    style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 9pt;">
                                    {{ $i }}</td>
                                <td align="left"
                                    style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 9pt;">
                                    {{ $value['service_name'] ?? '' }}</td>
                                <td align="left"
                                    style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 9pt;">
                                    {{ $value['custom_items_name'] }}</td>
                                <td align="center"
                                    style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 9pt;">
                                    {{ $value['custom_items_qty'] }}</td>
                                <td align="right"
                                    style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 9pt;">
                                    {{ $value['item_rate'] }}</td>

                                <td align="center"
                                    style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 9pt;">
                                    {!! App\Helpers\LogActivity::moneyFormatIndia(round($value['custom_items_amount'])) !!}</td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach

                    @endif
                    <tr>
                        <td style="border-right: 1px solid #000;font-size: 9pt;"></td>
                        <td align="left"
                            style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 9pt;">
                            <strong>ESTADO DA FACTURA</strong>
                        </td>
                        <td align="center"
                            style="border-right: 1px solid #000;border-bottom: 1px solid #000;font-size: 9pt;">
                            @if (isset($invoice) && $invoice->status)
                                {{ ucfirst($invoice->status) }}
                            @else
                                Pendente
                            @endif
                        </td>
                        <td colspan="3" style="border-bottom: 1px solid #000;"></td>

                    </tr>
                </table>
            </td>
        </tr>


        <tr>
            <td>
                <table border="0" cellpadding="5" cellspacing="0" width="100%">
                    <tr>
                        <td width="28%" border="0" style="border-left:1px solid black;">
                            <table width="100%" border="0">
                                <tr>
                                    <td width="75%" style="font-weight: bolder;">SubTotal</td>
                                    <td width="25%" style="font-weight: bolder;" align="right">
                                        {{ number_format($subTotal ?? 0, 2) }} AOA</td>
                                </tr>

                                @if (isset($tax_amount) && $tax_amount > 0)
                                    <tr>
                                        <td width="75%" style="font-weight: bolder;">Imposto</td>
                                        <td width="25%" style="font-weight: bolder;" align="right">
                                            {{ number_format($tax_amount, 2) }} AOA</td>
                                    </tr>
                                @endif

                                <tr style="border-top: 2px solid #000;">
                                    <td style="font-weight: bolder; font-size: 14px;">TOTAL</td>
                                    <td style="font-weight: bolder; font-size: 14px;" align="right">
                                        {{ number_format($total_amount ?? 0, 2) }} AOA</td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>
</body>

</html>
