<?php

namespace App\Providers;

use App\Models\User;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Responses\LoginResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
         // 会員登録の表示
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // ログインの表示
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // 会員登録の処理
        Fortify::createUsersUsing(\App\Actions\Fortify\CreateNewUser::class);

        // ログインの処理
        Fortify::authenticateUsing(function (Request $request) {
            $user = \App\Models\User::where('email', $request->email)->first();

            if ($user && \Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

        Fortify::redirects(
            'login','/products'
        );//ログイン後のリダイレクト先を商品一覧へ変更

        Fortify::redirects(
            'logout','/login'
        );// ログアウト後のリダイレクト先をログインページへ変更

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        Fortify::authenticateUsing(function ($request) {
            if ($request->user()->hasVerifiedEmail()) {
                return Redirect::to('/home');
            } else {
                return Redirect::to('/email/verify');
            }
        });
    }
}
