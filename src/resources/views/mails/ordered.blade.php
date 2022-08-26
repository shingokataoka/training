<p class="mb-4">{{ $item->ownerName }} 様の商品が購入されました。</p>

<div class="mb-4">商品情報</div>
<ul class="mb-4">
    <li>商品名：{{ $item->name}}</li>
    <li>商品金額：{{ number_format($item->price) }}円</li>
    <li>商品数：{{ $item->quantity }}</li>
    <li>合計金額：{{ number_format($item->price * $item->quantity) }}円</li>
</ul>

<div class="mb-4">購入者情報</div>
<ul>
    <li>{{ $user->name }} 様</li>
</ul>

