<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Season;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Product::query()->with('seasons');

        $keyword = $request->input('keyword');
        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        $sort = $request->input('sort');
        if ($sort === 'high') {
            $query->orderByDesc('price');
        } elseif ($sort === 'low') {
            $query->orderBy('price');
        } else {
            $query->orderBy('id');
        }

        $products = $query->paginate(6)->withQueryString();

        return view('products.index', [
            'products' => $products,
            'keyword' => $keyword,
            'sort' => $sort,
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('products.create', [
            'seasons' => Season::query()->orderBy('id')->get(),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProductRequest $request)
    {
        $imagePath = $request->file('image')->store('products', 'public');

        $product = Product::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'image' => $imagePath,
            'description' => $request->input('description'),
        ]);

        $product->seasons()->sync($request->input('seasons', []));

        return redirect()->route('products.index');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        $product->load('seasons');

        return view('products.show', [
            'product' => $product,
            'seasons' => Season::query()->orderBy('id')->get(),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $imagePath = $request->file('image')->store('products', 'public');

        $product->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'image' => $imagePath,
            'description' => $request->input('description'),
        ]);

        $product->seasons()->sync($request->input('seasons', []));

        return redirect()->route('products.index');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->seasons()->detach();
        $product->delete();

        return redirect()->route('products.index');
    }
}
