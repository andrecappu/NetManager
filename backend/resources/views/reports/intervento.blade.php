<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Intervento #{{ $intervento->id }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .title { font-size: 24px; font-weight: bold; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 16px; font-weight: bold; background-color: #f0f0f0; padding: 5px; border-bottom: 1px solid #ccc; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f9f9f9; width: 30%; }
        .checklist-item { margin-bottom: 5px; }
        .status-badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 10px; font-weight: bold; color: #fff; }
        .bg-success { background-color: #28a745; }
        .bg-warning { background-color: #ffc107; color: #000; }
        .bg-danger { background-color: #dc3545; }
        .bg-secondary { background-color: #6c757d; }
        .footer { margin-top: 50px; text-align: right; }
        .signature-box { display: inline-block; width: 250px; border-top: 1px solid #000; text-align: center; padding-top: 5px; margin-top: 50px; }
        .page-break { page-break-after: always; }
        .allegato-img { max-width: 100%; max-height: 300px; margin-bottom: 10px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Report Intervento Tecnico</div>
        <div>Codice: INT-{{ str_pad($intervento->id, 5, '0', STR_PAD_LEFT) }}</div>
        <div>Data Generazione: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="section">
        <div class="section-title">Dettagli Intervento</div>
        <table>
            <tr>
                <th>Titolo</th>
                <td>{{ $intervento->titolo }}</td>
            </tr>
            <tr>
                <th>Stato</th>
                <td>{{ strtoupper($intervento->stato) }}</td>
            </tr>
            <tr>
                <th>Priorità</th>
                <td>{{ strtoupper($intervento->priorita) }}</td>
            </tr>
            <tr>
                <th>Ente</th>
                <td>{{ $intervento->ente->nome ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Sito</th>
                <td>{{ $intervento->sito->nome ?? 'N/A' }} ({{ $intervento->sito->indirizzo ?? '' }})</td>
            </tr>
            <tr>
                <th>Apparato</th>
                <td>
                    @if($intervento->apparato)
                        {{ strtoupper($intervento->apparato->tipo) }} - {{ $intervento->apparato->marca }} {{ $intervento->apparato->modello }}
                        (IP: {{ $intervento->apparato->ip_address ?? 'N/A' }})
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            <tr>
                <th>Descrizione</th>
                <td>{{ $intervento->descrizione ?? 'Nessuna descrizione' }}</td>
            </tr>
            <tr>
                <th>Assegnato a</th>
                <td>{{ $intervento->assegnatoA ? $intervento->assegnatoA->nome . ' ' . $intervento->assegnatoA->cognome : 'Non assegnato' }}</td>
            </tr>
            <tr>
                <th>Data Scadenza</th>
                <td>{{ $intervento->data_scadenza ? $intervento->data_scadenza->format('d/m/Y H:i') : 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Checklist Operazioni</div>
        @if($intervento->checklistItems->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">Stato</th>
                        <th style="width: 50%;">Descrizione</th>
                        <th style="width: 40%;">Completato il / da</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($intervento->checklistItems as $item)
                        <tr>
                            <td style="text-align: center;">
                                @if($item->completato)
                                    <span style="color: green;">&#10004;</span>
                                @else
                                    <span style="color: red;">&#10008;</span>
                                @endif
                            </td>
                            <td>{{ $item->descrizione }}</td>
                            <td>
                                @if($item->completato)
                                    {{ $item->completato_at->format('d/m/Y H:i') }}<br>
                                    <small>da {{ $item->completatoDa->nome ?? '' }} {{ $item->completatoDa->cognome ?? '' }}</small>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Nessuna checklist presente per questo intervento.</p>
        @endif
    </div>

    <div class="footer">
        <div>Il Tecnico Incaricato</div>
        <div class="signature-box">
            {{ $intervento->assegnatoA ? $intervento->assegnatoA->nome . ' ' . $intervento->assegnatoA->cognome : 'Firma' }}
        </div>
    </div>

    @if($intervento->allegati->count() > 0)
        <div class="page-break"></div>
        <div class="header">
            <div class="title">Allegati Fotografici</div>
            <div>Codice: INT-{{ str_pad($intervento->id, 5, '0', STR_PAD_LEFT) }}</div>
        </div>
        
        <div class="section">
            @foreach($intervento->allegati as $allegato)
                @if(str_starts_with($allegato->mime_type, 'image/'))
                    <div style="margin-bottom: 20px; text-align: center;">
                        <p><strong>{{ $allegato->filename }}</strong> ({{ $allegato->created_at->format('d/m/Y H:i') }})</p>
                        <!-- In un ambiente reale, qui andrebbe letto il file da MinIO e convertito in base64 per dompdf -->
                        <!-- <img src="data:image/jpeg;base64,..." class="allegato-img"> -->
                        <div style="border: 1px dashed #ccc; padding: 50px; color: #999;">
                            [Immagine: {{ $allegato->filename }}]<br>
                            (Percorso: {{ $allegato->path_storage }})
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</body>
</html>
