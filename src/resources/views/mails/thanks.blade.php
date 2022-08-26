<p class="mb-4">{{ $user->name }} 様</p>
<p class="mb-4">ご購入ありがとうございました。</p>

商品内容
@foreach ($items as $item)
    <ul class="mb-4">
        <li>商品名：{{ $item->name }}</li>
        <li>商品金額：{{ number_format($item->price) }}</li>
        <li>商品数：{{ $item->quantity }}</li>
        <li>合計金額：{{ number_format($item->price * $item->quantity) }}</li>
    </ul>
@endforeach


