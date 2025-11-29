@php
  /**
   * Safe café config with our required overrides:
   * - SSID: GOLDEN POOL – 2.4G
   * - Password: 20252025
   */
  $cafe = array_merge([
      'brand_name' => 'Golden Pool Academy Café',
      // A clean, professional address presentation:
      'address_lines' => [
        'Avenue molay ismail, Quartier oum saad',
        'Laâyoune 70000, Maroc'
      ],
      'phone'      => '+212 612-345-678',
      'brand_logo' => null,
      // Force the requested Wi-Fi credentials
      'wifi_ssid'  => 'GOLDEN POOL – 2.4G',
      'wifi_pass'  => '20252025',
  ], (array) (config('cafe') ?? []));

  // Backward compat if an old single-line address exists in config
  if (!isset($cafe['address_lines']) && !empty($cafe['address'])) {
    $cafe['address_lines'] = [$cafe['address']];
  }

  // Resolve logo path (absolute for dompdf/knp/etc.)
  $logoPath = null;
  if (!empty($cafe['brand_logo'])) {
      $full = public_path(ltrim($cafe['brand_logo'], '/'));
      $logoPath = (is_file($full) ? $full : null);
  }

  // Compute a robust subtotal from items if not present
  $computedSubtotal = $order->items->sum(function($it){
      $price = (float)($it->price ?? 0);
      return $price * (int)($it->quantity ?? 0);
  });
  $subtotal = (float)($order->subtotal ?? $computedSubtotal);
  $taxRate  = (float)($order->tax_rate ?? 0);
  $taxAmt   = (float)($order->tax_amount ?? round($subtotal * $taxRate / 100, 2));
  $grand    = (float)($order->total ?? ($subtotal + $taxAmt));
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Reçu #{{ $order->id }}</title>
  <style>
    /* 80mm thermal receipt */
    @page { margin: 8px; }
    body {
      font-family: "Courier New", monospace;
      font-size: 12px;
      color: #000;
      background: #fff;
      margin: 0;
      padding: 10px;
    }
    .receipt { width: 80mm; margin: 0 auto; }
    .center { text-align: center; }
    .right { text-align: right; }
    .bold { font-weight: 700; }
    .muted { opacity: .75; }
    .section { margin-bottom: 10px; }
    .line { border-top: 1px dashed #000; margin: 8px 0; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 4px 0; }
    th { border-bottom: 1px solid #000; text-align: left; }
    td:last-child { text-align: right; }
    .item-name {
      max-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
      font-weight: 600;
    }
    .totals td { padding: 2px 0; }
    .badge {
      display:inline-block; border:1px solid #000; padding:1px 6px; border-radius:999px; font-size:10px;
    }
  </style>
</head>
<body>
  <div class="receipt">

    {{-- Header --}}
    <div class="center section">
      @if($logoPath)
        <img src="{{ $logoPath }}" alt="Logo" style="width:58px;height:58px"><br>
      @endif
      <div class="bold">{{ $cafe['brand_name'] }}</div>
      @if(!empty($cafe['address_lines']))
        <div class="muted">
          @foreach($cafe['address_lines'] as $line)
            {{ $line }}@if(!$loop->last)<br>@endif
          @endforeach
        </div>
      @endif
      @if(!empty($cafe['phone']))
        <div class="muted">Tél : {{ $cafe['phone'] }}</div>
      @endif>
    </div>

    {{-- Order meta --}}
    <div class="section">
      Reçu n° : <span class="bold">#{{ $order->id }}</span><br>
      Date : {{ $order->created_at->format('d/m/Y H:i') }}<br>
      Serveur : {{ optional($order->waiter)->name ?? '—' }}<br>
      @if($order->table_number)
        Table : {{ $order->table_number }}<br>
      @endif
    </div>

    <div class="line"></div>

    {{-- Items --}}
    <table>
      <thead>
        <tr>
          <th>Article</th>
          <th>Qté</th>
          <th>Prix</th>
          <th class="right">Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->items as $it)
          @php
            $name = optional($it->menuItem)->name ?? $it->name ?? '—';
            $qty  = (int)($it->quantity ?? 0);
            $pr   = (float)($it->price ?? 0);
          @endphp
          <tr>
            <td class="item-name" title="{{ $name }}">{{ $name }}</td>
            <td>{{ $qty }}</td>
            <td>DH {{ number_format($pr, 2) }}</td>
            <td class="right">DH {{ number_format($qty * $pr, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="line"></div>

    {{-- Totals --}}
    <table class="totals">
      <tr>
        <td>Sous-total</td>
        <td class="right">DH {{ number_format($subtotal, 2) }}</td>
      </tr>
      <tr>
        <td>TVA ({{ number_format($taxRate, 2) }} %)</td>
        <td class="right">DH {{ number_format($taxAmt, 2) }}</td>
      </tr>
      <tr class="bold">
        <td>Total</td>
        <td class="right">DH {{ number_format($grand, 2) }}</td>
      </tr>
    </table>

    {{-- Notes --}}
    @if($order->notes)
      <div class="section">
        <span class="bold">Remarques :</span> {{ $order->notes }}
      </div>
    @endif

    {{-- Wi-Fi --}}
    <div class="section center">
      Wi-Fi : <span class="bold">{{ $cafe['wifi_ssid'] }}</span><br>
      Mot de passe : <span class="bold">{{ $cafe['wifi_pass'] }}</span>
    </div>

    <div class="line"></div>

    {{-- Footer --}}
    <div class="center section">
      <div class="bold">Make sure to smile thats makes you more beautiful !</div>
      <div class="muted">good by  👋</div>
    </div>
  </div>
</body>
</html>
