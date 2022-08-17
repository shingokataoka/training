<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            カート
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if (count($products) > 0)
                        @foreach ($products as $product)
                            <div class="md:flex md:items-center mb-2">
                                <div class="w-3/12">
                                    <x-thumbnail type="products" filename="{{ $product->filename }}" />
                                </div>
                                <div class="w-4/12 md:ml-2">{{ $product->name }}</div>
                                <div class="w-3/12 flex justify-around">
                                    <div>{{ $product->pivot->quantity }}個</div>
                                    <div>{{ number_format($product->pivot->quantity * $product->price) }}<span class="text-sm text-gray-700">円（税込）</span></div>
                                </div>
                                <div class="w-2/12">削除ボタン</div>
                            </div>
                        @endforeach
                        <div>合計金額:{{ number_format($totalPrice) }}</div>
                    @else
                        <div>カートに商品が入っていません。</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
