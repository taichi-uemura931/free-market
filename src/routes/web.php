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
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReviewController;

Auth::routes(['verify' => true]);

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
    return redirect('http://localhost:8025');
})->middleware(['auth'])->name('verification.redirect-send');

Route::get('/profile', [ProfileController::class, 'edit'])->middleware(['auth', 'verified'])->name('profile');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/mylist', [ProductController::class, 'mylist'])->middleware('auth')->name('products.mylist');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [ProductController::class, 'search'])->name('search');

Route::middleware(['auth'])->group(function () {
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');

    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
    Route::get('/mypage/edit', [MypageController::class, 'edit'])->name('mypage.edit');
    Route::post('/mypage/update', [MypageController::class, 'update'])->name('mypage.update');
    Route::get('/seller/{id}', [MypageController::class, 'sellerPage'])->name('seller.page');

    Route::post('/favorite/{productId}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');

    Route::get('/purchase/{id}', [PurchaseController::class, 'index'])->name('purchase');
    Route::post('/purchase/{id}', [PurchaseController::class, 'process'])->name('purchase.process');

    Route::get('/address/edit', [AddressController::class, 'edit'])->name('address.edit');
    Route::post('/address/update', [AddressController::class, 'update'])->name('address.update');

    Route::get('/stripe/success/{id}', [PurchaseController::class, 'stripeSuccess'])->name('stripe.success');
    Route::get('/stripe/cancel/{id}', [PurchaseController::class, 'stripeCancel'])->name('stripe.cancel');

    Route::post('/comment/{id}', [CommentController::class, 'store'])->name('comment.store');

    Route::get('/transaction/start/{product}', [TransactionController::class, 'start'])->name('transaction.start');

    Route::get('/chat/{transaction}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{transaction}', [ChatController::class, 'store'])->name('chat.store');

    Route::patch('/messages/{id}', [MessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('messages.destroy');

    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
});
