<x-tests.app>
    <x-slot name="header">ヘッダー２</x-slot>

    コンポーネントテスト２
    <x-test-class-base classBaseMessage="メッセージです" />
    <div class="mb-4"></div>
    <x-test-class-base classBaseMessage="メッセージ２" defaultMessage="初期値から変更しています。" />
</x-tests.app>
