<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\Favorite;

class ProductController extends Controller

{
    public function rules()
    {
        return [
            'product_name'     => ['required', 'string'],
            'description'      => ['required', 'string', 'max:255'],
            'image'            => ['required', 'image', 'mimes:jpeg,png'],
            'categories'       => ['required', 'array', 'min:1'],
            'categories.*'     => ['integer'],
            'condition'        => ['required', 'string'],
            'price'            => ['required', 'numeric', 'min:0'],
        ];
    }

    public function index(Request $request)
    {
        $query = $request->input('query');

        $products = Product::when($query, function ($q) use ($query) {
            return $q->where('product_name', 'like', "%{$query}%");
        })->latest()->get();

        $soldProductIds = \App\Models\Order::pluck('product_id')->toArray();

        return view('products.index', compact('products', 'query','soldProductIds'));
    }

    public function mylist(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();

        $favoriteProductIds = $user->favorites()->pluck('product_id');

        $products = Product::whereIn('product_id', $favoriteProductIds)
        ->when($query, function ($q) use ($query) {
            $q->where('product_name', 'LIKE', '%' . $query . '%');
        })
        ->get();

        $soldProductIds = \App\Models\Order::pluck('product_id')->toArray();

        return view('products.mylist', compact('products','soldProductIds'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('product_name', 'like', "%{$query}%")->latest()->get();

        $soldProductIds = \App\Models\Order::pluck('product_id')->toArray();

        return view('products.index', compact('products', 'query','soldProductIds'));
    }

    public function show($product_id)
    {
        $product = Product::where('product_id', $product_id)->firstOrFail();

        return view('products.show', compact('product'));
    }

    public function create() {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'price' => mb_convert_kana($request->price, 'n', 'UTF-8')
        ]);

        $request->validate([
            'product_name' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:120',
            'condition' => 'required',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,category_id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('image')->store('products', 'public');

        $product = Product::create([
            'seller_id' => Auth::id(),
            'product_name' => $request->product_name,
            'brand_name' => $request->brand_name,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'img_url' => $path,
            'category' => '未分類',
        ]);

        $product->categories()->attach($request->categories);

        \App\Models\ProductImage::create([
            'product_id' => $product->product_id,
            'image_url' => $path,
        ]);

        return redirect()->route('products.index')->with('success', '商品を出品しました');
    }

}
