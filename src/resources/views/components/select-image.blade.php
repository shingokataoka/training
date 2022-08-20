@php
    if ($name === 'image1') { $modal = 'modal-1'; }
    elseif ($name === 'image2') { $modal = 'modal-2'; }
    elseif ($name === 'image3') { $modal = 'modal-3'; }
    elseif ($name === 'image4') { $modal = 'modal-4'; }
    $no = substr($name, 5);

    // 新規登録時のビューからはCurrent系の値がないので以下処理が必要
    $currentId = $currentId ?? '';
    $currentImageSrc =(empty($currentImage))? '' : asset('storage/products/' . $currentImage);
@endphp

{{-- 画像１〜４選択のモーダルウィンドウ --}}
<div class="modal micromodal-slide" id="{{ $modal }}" aria-hidden="true">
    <div class="modal__overlay z-50" tabindex="-1" data-micromodal-close>
      <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="{{ $modal }}-title">
        <header class="modal__header">
          <h2 class="text-xl text-gray-700" id="{{ $modal }}-title">
            画像{{ $no }}のファイルを選択してください
          </h2>
          <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>
        <main class="modal__content" id="{{ $modal }}-content">
            <div class="flex flex-wrap">
                @foreach ($images as $image)
                {{-- 広すぎるので1/4幅にする --}}
                <div class="w-1/3 md:w-1/4 p-2 md:p-4">

                    <div class="border rounded-md p-2 md:p-4">
                        <img class="image"
                            data-id="{{ $image->id }}"
                            data-name="{{ $name }}"
                            data-file="{{ $image->filename }}"
                            data-modal="{{ $modal }}"
                            src="{{ asset('storage/products/' . $image->filename) }}">
                    </div>
                </div>
                @endforeach
            </div>
        </main>
        <footer class="modal__footer">
          <button class="modal__btn" id="{{ $modal }}_close" data-micromodal-close aria-label="閉じる">閉じる</button>
        </footer>
      </div>
    </div>
</div>

{{-- 「画像1〜4を選択」ボタンと「選択中サムネイル画像」 --}}
<div class="flex justify-between items-center mb-4 bg-gray-100 p-4">
    <a data-micromodal-trigger="{{ $modal }}" href='javascript:;' class="p-2 bg-gray-200 rounded-md">画像{{ $no }}を選択</a>
    <div class="w-1/4">
        <img id="{{ $name }}_thumbnail" src="{{ $currentImageSrc }}">
        <input id="{{ $name }}_hidden" type="hidden" name="{{ $name }}" value="{{ $currentId }}">
    </div>
</div>

