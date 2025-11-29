<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <style>
    body {
      font-family: DejaVu Sans, system-ui;
      font-size: 12px;
      color: #111;
      margin: 0;
      padding: 10px;
      line-height: 1.5;
    }
    .ticket {
      width: 80mm;
      margin: auto;
    }
    .title {
      font-size: 14px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 6px;
      border-bottom: 1px dashed #999;
      padding-bottom: 4px;
    }
    .meta {
      font-size: 11px;
      color: #555;
      margin-bottom: 8px;
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 8px;
    }
    th {
      font-size: 11px;
      text-align: left;
      border-bottom: 1px solid #ccc;
      padding-bottom: 4px;
    }
    td {
      padding: 6px 4px;
      border-bottom: 1px dashed #ccc;
      vertical-align: top;
    }
    td.qty {
      font-weight: bold;
      text-align: right;
      width: 30px;
    }
    .notes {
      margin-top: 10px;
      font-size: 12px;
      background: #f9f9f9;
      padding: 6px;
      border-left: 3px solid #ccc;
    }
  </style>
</head>
<body>
  <div class="ticket">
    <!-- Header -->
    <div class="title">Ticket Cuisine / Bar — Commande #{{ $order->id }}</div>

    <!-- Metadata -->
    <div class="meta">
      Date : {{ $order->created_at->format('d/m/Y H:i') }}<br>
      Serveur : {{ optional($order->waiter)->name ?? '—' }}
      @if($order->table_number)
        — Table : {{ $order->table_number }}
      @endif
    </div>

    <!-- Items -->
    <table>
      <thead>
        <tr>
          <th>Article</th>
          <th>Qté</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->items as $it)
          <tr>
            <td>{{ optional($it->menuItem)->name ?? '—' }}</td>
            <td class="qty">{{ $it->quantity }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Notes -->
    @if($order->notes)
      <div class="notes">
        <strong>Remarques :</strong> {{ $order->notes }}
      </div>
    @endif
  </div>
</body>
</html>