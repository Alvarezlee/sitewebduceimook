<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; text-align: center; padding: 60px; }
        .frame { border: 8px solid #1d4ed8; padding: 60px; }
        h1 { color: #1d4ed8; font-size: 28px; text-transform: uppercase; letter-spacing: 2px; }
        .name { font-size: 26px; font-weight: bold; margin: 24px 0; }
        .score { font-size: 18px; color: #059669; font-weight: bold; }
        .meta { margin-top: 40px; font-size: 11px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="frame">
        <p style="letter-spacing:3px;color:#6b7280;">CERCLE DES ENSEIGNANTS D'INFORMATIQUE DU MOUNGO</p>
        <h1>Certificat de participation</h1>
        <p>MOUNGO TIC QUIZZ — {{ $attempt->candidate->edition->name }}</p>

        <p class="name">{{ $attempt->candidate->user->full_name }}</p>

        <p>a obtenu un score de</p>
        <p class="score">{{ $attempt->score }} / 100</p>

        <div class="meta">
            <p>Certificat n° {{ $certificateNumber }}</p>
            <p>Délivré le {{ now()->translatedFormat('d/m/Y') }}</p>
        </div>
    </div>
</body>
</html>
