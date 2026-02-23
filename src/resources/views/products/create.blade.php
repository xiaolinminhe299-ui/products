<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品登録</title>
    <link rel="stylesheet" href="{{ asset('css/products/create.css') }}">
</head>
<body>
<div class="topbar"><span class="logo">mogitate</span></div>
<div class="container">
    <h1>商品登録</h1>
    {{-- 商品登録フォーム: 入力値とバリデーションエラーを再表示する --}}
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf
        {{-- 商品名 --}}
        <div class="field">
            <label class="label" for="name"><span>商品名</span><span class="req">必須</span></label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="商品名を入力">
            @error('name')<p class="error">{{ $message }}</p>@enderror
        </div>

        {{-- 価格 --}}
        <div class="field">
            <label class="label" for="price"><span>値段</span><span class="req">必須</span></label>
            <input id="price" type="number" name="price" value="{{ old('price') }}" min="0" max="10000" placeholder="値段を入力">
            @error('price')<p class="error">{{ $message }}</p>@enderror
        </div>

        {{-- 商品画像 --}}
        <div class="field">
            <label class="label" for="image"><span>商品画像</span><span class="req">必須</span></label>
            <div class="file-line">
                <input id="image" type="file" name="image" accept="image/*">
            </div>
            @error('image')<p class="error">{{ $message }}</p>@enderror
        </div>

        {{-- 季節: 複数選択チェックボックス --}}
        <div class="field">
            <label class="label"><span>季節</span><span class="req">必須</span></label>
            <div class="season-list">
                @foreach ($seasons as $season)
                    <label class="season-item">
                        <input type="checkbox" name="seasons[]" value="{{ $season->id }}" {{ in_array($season->id, old('seasons', [])) ? 'checked' : '' }}>
                        {{ $season->name }}
                    </label>
                @endforeach
            </div>
            @error('seasons')<p class="error">{{ $message }}</p>@enderror
            @error('seasons.*')<p class="error">{{ $message }}</p>@enderror
        </div>

        {{-- 商品説明 --}}
        <div class="field">
            <label class="label" for="description"><span>商品説明</span><span class="req">必須</span></label>
            <textarea id="description" name="description" placeholder="商品の説明を入力">{{ old('description') }}</textarea>
            @error('description')<p class="error">{{ $message }}</p>@enderror
        </div>

        {{-- 操作ボタン --}}
        <div class="actions">
            <a class="btn btn-back" href="{{ route('products.index') }}">戻る</a>
            <button class="btn btn-submit" type="submit">登録</button>
        </div>
    </form>
</div>
</body>
</html>
