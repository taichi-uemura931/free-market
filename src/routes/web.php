<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Auth::routes(['verify' => true]);

// 会員登録・ログイン関連
Route::get('/register', [RegisterController::class, 'registerForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
Route::get('/login', [LoginController::class, 'loginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// メール認証
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('profile');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::post('/verification/redirect-send', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return redirect('http://localhost:8025'); // Mailhog/確認用
})->middleware(['auth'])->name('verification.redirect-send');

// プロフィール設定画面（初回用）
Route::get('/profile', [ProfileController::class, 'edit'])->middleware(['auth', 'verified'])->name('profile');

// 商品一覧・検索・詳細
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/mylist', [ProductController::class, 'mylist'])->middleware('auth')->name('products.mylist');
Route::get('/products/{product_id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [ProductController::class, 'search'])->name('search');

// ログイン後の機能グループ
Route::middleware(['auth'])->group(function () {
    // プロフィール更新
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // 商品出品
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');

    // マイページ
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
    Route::get('/mypage/edit', [MypageController::class, 'edit'])->name('mypage.edit');
    Route::post('/mypage/update', [MypageController::class, 'update'])->name('mypage.update');

    // いいね機能
    Route::post('/favorite/{productId}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');

    // 商品購入
    Route::get('/purchase/{id}', [PurchaseController::class, 'index'])->name('purchase');
    Route::post('/purchase/{id}', [PurchaseController::class, 'process'])->name('purchase.process');

    // 配送先住所変更
    Route::get('/address/edit', [AddressController::class, 'edit'])->name('address.edit');
    Route::post('/address/update', [AddressController::class, 'update'])->name('address.update');

    // Stripe決済 成功・キャンセル
    Route::get('/stripe/success/{product_id}', [PurchaseController::class, 'stripeSuccess'])->name('stripe.success');
    Route::get('/stripe/cancel/{product_id}', [PurchaseController::class, 'stripeCancel'])->name('stripe.cancel');

    // コメント投稿
    Route::post('/comment/{id}', [CommentController::class, 'store'])->name('comment.store');
});
