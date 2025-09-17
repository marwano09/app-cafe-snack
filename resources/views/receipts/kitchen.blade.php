<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<style>
  body{font-family: DejaVu Sans, system-ui; font-size:12px; color:#111;}
  table{width:100%; border-collapse:collapse; margin-top:8px}
  th,td{border-bottom:1px dashed #ccc; padding:6px 4px; text-align:right}
</style>
</head>
<body>
  <div style="font-weight:700; margin-bottom:6px">تذكرة المطبخ/البار — طلب #{{ $order->id }}</div>
  <div class="small" style="opacity:.7">
    التاريخ: {{ $order->created_at->format('Y-m-d H:i') }} —
    النادل: {{ optional($order->waiter)->name ?? '—' }}
    @if($order->table_number) — الطاولة: {{ $order->table_number }} @endif
  </div>

  <table>
    <thead>
      <tr><th>الصنف</th><th>الكمية</th></tr>
    </thead>
    <tbody>
      @foreach($order->items as $it)
        <tr>
          <td>{{ optional($it->menuItem)->name }}</td>
          <td style="font-weight:700">{{ $it->quantity }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  @if($order->notes)
  <p style="margin-top:10px"><strong>ملاحظات:</strong> {{ $order->notes }}</p>
  @endif
</body>
</html>
