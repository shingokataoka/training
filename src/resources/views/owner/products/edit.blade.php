<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            商品の登録
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mx-auto w-1/2">
                        <!-- Validation Errors -->
                        <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    </div>
                    {{-- フラッシュメッセージ --}}
                    <x-flash-message />

                    <form action="{{ route('owner.products.update', ['product' => $product->id]) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="-m-2">

                            {{-- 商品名 --}}
                            <div class="p-2 w-1/2  mx-auto">
                                <div class="relative">
                                    <label for="name" class="leading-7 text-sm text-gray-600">商品名 ※必須</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                            </div>

                            {{-- 商品情報 --}}
                            <div class="p-2 w-1/2  mx-auto">
                                <div class="relative">
                                    <label for="information" class="leading-7 text-sm text-gray-600">商品情報 ※必須</label>
                                    <textarea id="information" name="information" rows="10" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{
                                        old('information', $product->information)
                                    }}</textarea>
                                </div>
                            </div>

                            {{-- 価格 --}}
                            <div class="p-2 w-1/2  mx-auto">
                                <div class="relative">
                                    <label for="price" class="leading-7 text-sm text-gray-600">価格 ※必須</label>
                                    <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                            </div>

                            {{-- 表示順 --}}
                            <div class="p-2 w-1/2  mx-auto">
                                <div class="relative">
                                    <label for="sort_order" class="leading-7 text-sm text-gray-600">表示順</label>
                                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $product->sort_order) }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                            </div>

                            {{-- 元の数量を表示とhiddenで送信 --}}
                            <div class="p-2 w-1/2  mx-auto">
                                <div class="relative">
                                    <label for="current_quantity" class="leading-7 text-sm text-gray-600">現在在庫数</label>
                                    <input type="hidden" id="current_quantity" name="current_quantity" value="{{ $quantity }}">
                                    <div class="w-full bg-gray-100 bg-opacity-50 rounded b text-base text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $quantity }}</div>
                                </div>
                            </div>

                            {{-- (数量を)追加/削減 --}}
                            <div class="p-2 w-1/2  mx-auto">
                                <div class="relative flex justify-around">
                                    <label class="hover:opacity-60">
                                        <input type="radio" name="type" value="1" @if (old('type', '1') === '1') { checked } @endif class="mr-2">追加
                                    </label>
                                    <label class="hover:opacity-60">
                                        <input type="radio" name="type" value="2" @if (old('type') === '2') { checked } @endif class="mr-2">削減
                                    </label>
                                </div>
                            </div>

                            {{-- 追加or削減する数量　stocksテーブルにinsertする --}}
                            <div class="p-2 w-1/2  mx-auto">
                                <div class="relative">
                                    <label for="quantity" class="leading-7 text-sm text-gray-600">数量　※必須</label>
                                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 0) }}"  required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    <span class="text-sm">0~99の範囲で入力してください</span>
                                </div>
                            </div>

                            {{-- 店舗一覧 --}}
                            <div class="p-2 w-1/2  mx-auto">
                                <label for="shop_id" class="leading-7 text-sm text-gray-600">販売する店舗</label>
                                <select name="shop_id" id="shop_id" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                @foreach ($shops as $shop)
                                    <option value="{{ $shop->id }}" @if ($shop->id === $product->shop->id) checked @endif>
                                        {{ $shop->name }}
                                    </option>
                                @endforeach
                                </select>
                            </div>

                            {{-- カテゴリ一覧 --}}
                            <div class="p-2 w-1/2  mx-auto">
                                <label for="category" class="leading-7 text-sm text-gray-600">カテゴリ</label>
                                <select name="category" id="category" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                @foreach ($categories as $category)
                                    <optgroup label="{{ $category->name }}">
                                    @foreach ($category->secondaries as $secondary)
                                        <option value="{{ $secondary->id }}" @if ($secondary->id === $product->secondary_category_id) selected  @endif>
                                            {{ $secondary->name }}
                                        </option>
                                    @endforeach
                                    </optgroup>
                                @endforeach
                                </select>
                            </div>

                            {{-- 画像１〜４選択のモーダルウィンドウ --}}
                            <div class="p-2 w-1/2  mx-auto">
                                <x-select-image name="image1" :images="$images" currentId="{{ $product->image1 }}" currentImage="{{ $product->imageFirst->filename ?? '' }}" />
                                <x-select-image name="image2" :images="$images" currentId="{{ $product->image2 }}" currentImage="{{ $product->imageSecond->filename ?? '' }}" />
                                <x-select-image name="image3" :images="$images" currentId="{{ $product->image3 }}" currentImage="{{ $product->imageThird->filename ?? '' }}" />
                                <x-select-image name="image4" :images="$images" currentId="{{ $product->image4 }}" currentImage="{{ $product->imageForth->filename ?? '' }}" />
                            </div>

                            {{-- 販売中/停止中 --}}
                            <div class="p-2 w-1/2  mx-auto">
                                <div class="relative flex justify-around">
                                    <label class="hover:opacity-60">
                                        <input type="radio" name="is_selling" value="1" @if (old('is_selling', (string)$product->is_selling) === '1') { checked } @endif class="mr-2">販売中
                                    </label>
                                    <label class="hover:opacity-60">
                                        <input type="radio" name="is_selling" value="0" @if (old('is_selling', (string)$product->is_selling) === '0') { checked } @endif class="mr-2">停止中
                                    </label>
                                </div>
                            </div>

                            {{-- 戻るボタン、更新するボタン --}}
                            <div class="p-2 w-full mt-4 flex justify-around">
                                <button type="button" onclick="location.href='{{ route('owner.products.index') }}'" class="bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">戻る</button>
                                <button type="submit" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">更新する</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        'use strict';

        MicroModal.init({
            disableScroll: true
        });

        const images = document.querySelectorAll('.image');
        images.forEach(image => {
            image.addEventListener('click', e => {
                const imageName = e.target.dataset.name;
                const imageId = e.target.dataset.id;
                const imagefile = e.target.dataset.file;
                const imageModal = e.target.dataset.modal;
                const imageSrc = e.target.src;

                document.getElementById(imageName + '_thumbnail').src = imageSrc;
                document.getElementById(imageName + '_hidden').value = imageId;

                document.getElementById(imageModal + '_close').click();
                // MicroModal.close(imageModal); //モーダルを閉じる
            });
        });


    </script>
</x-app-layout>
