<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="clientPaymenthistroymodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Hist&oacute;rico de pagamento</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Factura No</th>
                                    <th>Valor</th>
                                    <th>Data de recebimento</th>
                                    <th>Forma de pagamento</th>
                                    <th>Comprovativo</th>
                                    <th>Status</th>
                                    <th>{{ __('Note') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getPaymentHistory as $history)
                                    <tr>
                                        <td>{{ $history->factura_no }}</td>
                                        <td>
                                            {{ round($history->amount) }}
                                        </td>
                                        <td>{{ date($date_format_laravel, strtotime($history->receiving_date)) }}</td>
                                        <td>{{ $history->payment_type }} @if ($history->payment_type == 'Cheque')
                                                ({{ date($date_format_laravel, strtotime($history->cheque_date)) }})
                                            @endif
                                        </td>
                                        <td>
                                            @if ($history->comprovativo)
                                                <a href="{{ url('jurisdocs/storage/app/public/' . $history->comprovativo) }}"
                                                    target="_blank" class="btn btn-sm btn-success">
                                                    <i class="fa fa-file-pdf-o"></i> Ver Comprovativo
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm payment-status"
                                                data-payment-id="{{ $history->id }}"
                                                style="width: 120px; font-size: 12px;">

                                                <option value="pendente"
                                                    @if ($history->status == 'pendente') selected @endif
                                                    style="color: #f39c12;">
                                                    Pendente
                                                </option>

                                                <option value="aprovado"
                                                    @if ($history->status == 'aprovado') selected @endif
                                                    style="color: #27ae60;">
                                                    Confirmado
                                                </option>

                                                <option value="rejeitado"
                                                    @if ($history->status == 'rejeitado') selected @endif
                                                    style="color: #e74c3c;">
                                                    Rejeitado
                                                </option>



                                            </select>
                                        </td>
                                        <td><a href="javascript:void(0);" tabindex="0" class="text-right"
                                                data-placement="bottom" data-toggle="popover" data-trigger="focus"
                                                title="Remarks" data-content="{{ $history->note ?? 'N/A' }}"><i
                                                    class="fa fa-eye"></a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Nenhum registo encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        "use strict";
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();

        // Handle status change
        $('.payment-status').on('change', function() {
            var paymentId = $(this).data('payment-id');
            var newStatus = $(this).val();
            var selectElement = $(this);

            $.ajax({
                url: '{{ route('admin.payment.update-status') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    payment_id: paymentId,
                    status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Status atualizado com sucesso!');
                    } else {
                        toastr.error('Erro ao atualizar status');
                    }
                },
                error: function() {
                    toastr.error('Erro ao atualizar status');
                    // Revert to previous value on error
                    selectElement.val(selectElement.data('previous-value'));
                }
            });
        });

        // Store previous value before change
        $('.payment-status').on('focus', function() {
            $(this).data('previous-value', $(this).val());
        });
    });
</script>
