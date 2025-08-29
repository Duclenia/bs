<h2>Olá!</h2>

<p>Sua reunião foi agendada com sucesso 🎉</p>

<p><strong>Título:</strong> {{ $agendamento->titulo }}</p>
<p><strong>Data:</strong> {{ $agendamento->data }} às {{ $agendamento->hora }}</p>
<p><strong>Plataforma:</strong> {{ ucfirst($agendamento->plataforma) }}</p>

<p>
    <strong>Link de participação:</strong><br>
    <a href="{{ $agendamento->join_url }}">{{ $agendamento->join_url }}</a>
</p>

@if($agendamento->start_url)
    <p><strong>Link de início (host):</strong><br>
    <a href="{{ $agendamento->start_url }}">{{ $agendamento->start_url }}</a></p>
@endif

<p>Obrigado por usar nossa plataforma!</p>
