<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細</title>
    <link rel="stylesheet" href="{{ asset('css/products/show.css') }}">
</head>
<body>
<div class="topbar"><span class="logo">mogitate</span></div>
<div class="container">
    <div class="breadcrumbs">
        <a href="{{ route('products.index') }}">商品一覧</a> ＞ {{ $product->name }}
    </div>

    <div class="grid">
        <div class="thumb">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
        </div>

        <div>
            <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="field">
                    <label class="label" for="name">商品名</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}">
                    @error('name')<p class="error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label class="label" for="price">値段</label>
                    <input id="price" type="number" name="price" value="{{ old('price', $product->price) }}" min="0" max="10000">
                    @error('price')<p class="error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label class="label" for="image">商品画像</label>
                    <input id="image" type="file" name="image" accept="image/*">
                    @error('image')<p class="error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label class="label">季節</label>
                    @php
                        $selectedSeasons = old('seasons', $product->seasons->pluck('id')->all());
                    @endphp
                    <div class="season-list">
                        @foreach ($seasons as $season)
                            <label>
                                <input type="checkbox" name="seasons[]" value="{{ $season->id }}" {{ in_array($season->id, $selectedSeasons) ? 'checked' : '' }}>
                                {{ $season->name }}
                            </label>
                        @endforeach
                    </div>
                    @error('seasons')<p class="error">{{ $message }}</p>@enderror
                    @error('seasons.*')<p class="error">{{ $message }}</p>@enderror
                </div>

                <div class="field">
                    <label class="label" for="description">商品説明</label>
                    <textarea id="description" name="description">{{ old('description', $product->description) }}</textarea>
                    @error('description')<p class="error">{{ $message }}</p>@enderror
                </div>

                <div class="actions">
                    <a class="btn btn-back" href="{{ route('products.index') }}">戻る</a>
                    <button class="btn btn-submit" type="submit">変更を保存</button>
                </div>
            </form>

            <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('この商品を削除しますか？');" style="margin-top:10px;">
                @csrf
                @method('DELETE')
                <button class="btn-delete" type="submit">削除</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
