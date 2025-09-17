@php
  $cafe   = config('cafe');
  $logo   = public_path(ltrim($cafe['brand_logo'] ?? '', '/'));
  $hasLogo= is_file($logo);
@endphp
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<style>
  body{font-family: DejaVu Sans, system-ui; font-size:12px; color:#111;}
  .head{display:flex; align-items:center; gap:12px; margin-bottom:10px;}
  .logo{width:48px; height:48px; object-fit:cover; border-radius:8px;}
  table{width:100%; border-collapse:collapse; margin-top:8px}
  th,td{border-bottom:1px solid #ddd; padding:6px 4px; text-align:right}
  .right{ text-align:left }
  .small{opacity:.7}
</style>
</head>
<body>
  <div class="head">
    @if($hasLogo)<img src="{{ $logo }}" class="logo">@endif
    <div>
      <div style="font-weight:700">{{ $cafe['brand_name'] ?? '' }}</div>
      <div class="small">{{ $cafe['address'] ?? '' }} — {{ $cafe['phone'] ?? '' }}</div>
    </div>
  </div>

  <div class="small">
    رقم: #{{ $order->id }} —
    التاريخ: {{ $order->created_at->format('Y-m-d H:i') }} —
    النادل: {{ optional($order->waiter)->name ?? '—' }} 
    @if($order->table_number) — الطاولة: {{ $order->table_number }} @endif
  </div>

  <table>
    <thead>
      <tr><th>الصنف</th><th>الكمية</th><th>السعر</th><th class="right">الإجمالي</th></tr>
    </thead>
    <tbody>
      @foreach($order->items as $it)
        @php $line = $it->quantity * $it->price; @endphp
        <tr>
          <td>{{ optional($it->menuItem)->name }}</td>
          <td>{{ $it->quantity }}</td>
          <td>DH {{ number_format($it->price,2) }}</td>
          <td class="right">DH {{ number_format($line,2) }}</td>
        </tr>
      @endforeach
      <tr>
        <td colspan="3" style="font-weight:700">المجموع</td>
        <td class="right" style="font-weight:700">DH {{ number_format((float)$order->total,2) }}</td>
      </tr>
    </tbody>
  </table>

  @if($order->notes)
  <p class="small">ملاحظات: {{ $order->notes }}</p>
  @endif

  <p class="small" style="margin-top:10px">
    Wi-Fi: <strong>{{ $cafe['wifi_ssid'] ?? '' }}</strong> — كلمة السر: <strong>{{ $cafe['wifi_pass'] ?? '' }}</strong>
  </p>
  <p style="text-align:center; margin-top:8px">شكراً لزيارتكم! نتمنى لكم وقتاً ممتعاً 🎱</p>
</body>
</html>
