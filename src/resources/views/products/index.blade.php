<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <link rel="stylesheet" href="{{ asset('css/products/index.css') }}">
</head>
<body>
<div class="topbar"><span class="logo">mogitate</span></div>
<div class="container">
    <div class="heading-row">
        <h1 class="heading">{{ filled($keyword) ? '“' . $keyword . '”の商品一覧' : '商品一覧' }}</h1>
        <a class="add-btn" href="{{ route('products.create') }}">+ 商品を追加</a>
    </div>

    <div class="content">
        <aside class="side">
            <form method="GET" action="{{ route('products.search') }}">
                <input class="search-input" type="text" name="keyword" value="{{ $keyword }}" placeholder="商品名で検索">
                <button class="search-btn" type="submit">検索</button>
                <div class="sort-wrap">
                    <p class="side-label">価格順で表示</p>
                    <select class="sort-select" name="sort">
                        <option value="" {{ empty($sort) ? 'selected' : '' }}>価格で並び替え</option>
                        <option value="high" {{ $sort === 'high' ? 'selected' : '' }}>高い順に表示</option>
                        <option value="low" {{ $sort === 'low' ? 'selected' : '' }}>低い順に表示</option>
                    </select>
                </div>
            </form>
            @if (in_array($sort, ['high', 'low'], true))
                <a
                    class="sort-chip"
                    href="{{ route('products.search', request()->except(['sort', 'page'])) }}"
                    aria-label="並び替えをリセット"
                >
                    <span>{{ $sort === 'high' ? '高い順に表示' : '低い順に表示' }}</span>
                    <span class="sort-chip-close" aria-hidden="true">×</span>
                </a>
            @endif
        </aside>

        <main>
            <div class="cards">
                @foreach ($products as $product)
                    <a class="card" href="{{ route('products.show', $product) }}">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        <div class="card-footer">
                            <p class="card-name">{{ $product->name }}</p>
                            <p class="card-price">¥{{ number_format($product->price) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($products->hasPages())
                <div class="pagi">
                    @if ($products->onFirstPage())
                        <span>‹</span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}">‹</a>
                    @endif

                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if ($page === $products->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}">›</a>
                    @else
                        <span>›</span>
                    @endif
                </div>
            @endif
        </main>
    </div>
</div>
</body>
</html>
