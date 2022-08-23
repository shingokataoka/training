<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                商品一覧
            </h2>
            <form action="{{ route('user.items.index') }}" method="get" class="flex">
                <div>
                    <span class="text-sm">表示順</span><br>
                    <select id="sort" name="sort" class="mr-4">
                        @foreach ([
                            'おすすめ順' => \Constant::SORT_ORDER['recommend'] ,
                            '価格の高い順' => \Constant::SORT_ORDER['higherPrice'] ,
                            '価格の安い順' => \Constant::SORT_ORDER['lowerPrice'],
                            '新しい順' => \Constant::SORT_ORDER['later'],
                            '古い順' => \Constant::SORT_ORDER['older'],
                        ] as $text => $sort)
                            <option
                                value="{{ $sort }}"
                                @if (\Request::get('sort') === $sort)
                                    selected
                                @endif
                                >
                                {{ $text }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="text-sm">表示件数</span><br>
                    <form action="{{ route('user.items.index') }}" method="get">
                        <select name="pagination" id="pagination">
                            @foreach (['20', '50', '100'] as $pagination)
                                <option
                                    value="{{$pagination}}"
                                    @if (\Request::get('pagination') === $pagination)
                                        selected
                                    @endif
                                    >
                                    {{$pagination}}件
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="flex flex-wrap">
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($products as $product)
                        {{-- 広すぎるので1/4幅にする --}}
                        <div class="w-1/3 md:w-1/4 p-2 md:p-4">
                            {{-- クリックでeditへ移動 --}}
                            <a href="{{ route('user.items.show', ['item' => $product->id]) }}">
                                <div class=" p-4 border-2 border-gray-100 rounded-md">
                                    {{-- サムネイル画像 --}}
                                    <x-thumbnail class="border-green-500" filename="{{ $product->filename ?? '' }}" type="products" />
                                    {{-- カテゴリ名、商品名、価格 --}}
                                    <div class="mt-4">
                                        <h3 class="text-gray-500 text-xs tracking-widest title-font mb-1">{{ $product->category }}</h3>
                                        <h2 class="text-gray-900 title-font text-lg font-medium">{{ $product->name }}</h2>
                                        <p class="mt-1">
                                            {{ number_format($product->price) }}
                                            <span class="text-sm text-gray-700">円（税込）</span>
                                        </p>
                                        <p class="mt-1">おすすめ順位：{{ $product->sort_order }}</p>
                                        <p class="mt-1">{{ $product->created_at }}</p>
                                        <p class="mt-1">{{ ++$i }}件目</p>
                                      </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    {{ $products->appends([
                        'sort' => \Request::get('sort'),
                        'pagination' => \Request::get('pagination'),
                    ])->links(); }}
                </div>
            </div>
        </div>
    </div>
    <script>
        const select = document.getElementById('sort');
        select.addEventListener('change', function(e){
            this.form.submit();
        });

        const paginate = document.getElementById('pagination');
        paginate.addEventListener('change', function(e) {
            this.form.submit();
        });
    </script>
</x-app-layout>
