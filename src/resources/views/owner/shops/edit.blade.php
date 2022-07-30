<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
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

                    <form action="{{ route('owner.shops.update', ['shop' => $shop->id]) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="-m-2">

                            {{-- 店名 --}}
                            <div class="p-2 w-1/2  mx-auto">
                                <div class="relative">
                                    <label for="name" class="leading-7 text-sm text-gray-600">店名 ※必須</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $shop->name) }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                            </div>

                            {{-- 店舗情報 --}}
                            <div class="p-2 w-1/2  mx-auto">
                                <div class="relative">
                                    <label for="information" class="leading-7 text-sm text-gray-600">店舗情報 ※必須</label>
                                    <textarea id="information" name="information" rows="10" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{
                                        old('information', $shop->information)
                                    }}</textarea>
                                </div>
                            </div>

                            {{-- 店舗サムネイル画像 --}}
                            {{-- これはowner/shops/indexと同じだからコンポーネントにして統一した --}}
                            <div class="p-2 w-1/2  mx-auto">
                                <div class="relative">
                                    <div class="w-32">
                                        <x-shop-thumbnail :filename="$shop->filename" />
                                    </div>
                                </div>
                            </div>

                            {{-- 画像フォーム --}}
                            <div class="p-2 w-1/2  mx-auto">
                                <div class="relative">
                                    <label for="image" class="leading-7 text-sm text-gray-600">画像</label>
                                    <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/jpg" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                            </div>

                            {{-- 販売中/停止中 --}}
                            <div class="p-2 w-1/2  mx-auto">
                                <div class="relative flex justify-around">
                                    <label class="hover:opacity-60">
                                        <input type="radio" name="is_selling" value="1" @if ($shop->is_selling === 1) { checked } @endif class="mr-2">販売中
                                    </label>
                                    <label class="hover:opacity-60">
                                        <input type="radio" name="is_selling" value="0" @if ($shop->is_selling === 0) { checked } @endif class="mr-2">停止中
                                    </label>
                                </div>
                            </div>

                            {{-- 戻るボタン、更新するボタン --}}
                            <div class="p-2 w-full mt-4 flex justify-around">
                                <button type="button" onclick="location.href='{{ route('owner.shops.index') }}'" class="bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">戻る</button>
                                <button type="submit" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">更新する</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
