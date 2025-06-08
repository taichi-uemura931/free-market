<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\ProductImage;

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
        $userId = Auth::id();

        $products = Product::when($query, function ($q) use ($query) {
            return $q->where('product_name', 'like', "%{$query}%");
        })
        ->when($userId, function ($q) use ($userId) {
            return $q->where('seller_id', '!=', $userId);
        })
        ->latest()
        ->get();

        $soldProductIds = Order::pluck('product_id')->toArray();

        return view('products.index', compact('products', 'query','soldProductIds'));
    }

    public function mylist(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();

        $favoriteProductIds = $user->favorites()->pluck('product_id');

        $products = Product::whereIn('id', $favoriteProductIds)
        ->when($query, function ($q) use ($query) {
            $q->where('product_name', 'LIKE', '%' . $query . '%');
        })
        ->get();

        $soldProductIds = Order::pluck('product_id')->toArray();

        return view('products.mylist', compact('products','soldProductIds'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('product_name', 'like', "%{$query}%")->latest()->get();

        $soldProductIds = Order::pluck('product_id')->toArray();

        return view('products.index', compact('products', 'query','soldProductIds'));
    }

    public function show($id)
    {
        $product = Product::with(['categories', 'favorites', 'comments.user'])->findOrFail($id);

        $transaction = null;

        if (Auth::check()) {
            $transaction = Transaction::where('product_id', $id)
                ->where(function ($q) {
                    $q->where('buyer_id', Auth::id())
                    ->orWhere('seller_id', Auth::id());
                })->first();
        }

        return view('products.show', compact('product', 'transaction'));
    }

    public function create()
    {
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
            'description' => 'required|string|max:255',
            'price' => 'required|integer|min:120',
            'condition' => 'required',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'product_name.required' => '商品名を入力してください',
            'product_name.max' => '商品名は255文字以内で入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください',
            'price.required' => '価格を入力してください',
            'price.integer' => '価格は数値で入力してください',
            'price.min' => '価格は120円以上で入力してください',
            'condition.required' => '商品の状態を選択してください',
            'categories.required' => 'カテゴリーを1つ以上選択してください',
            'categories.*.exists' => '選択されたカテゴリーが不正です',
            'image.required' => '商品画像を選択してください',
            'image.image' => 'アップロードされたファイルは画像ではありません',
            'image.mimes' => '画像はjpegまたはpng形式でアップロードしてください',
            'image.max' => '画像のサイズは2MB以下にしてください',
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
        ]);

        $product->categories()->attach($request->categories);

        ProductImage::create([
            'product_id' => $product->id,
            'image_url' => $path,
        ]);

        return redirect()->route('products.index')->with('success', '商品を出品しました');
    }
}
