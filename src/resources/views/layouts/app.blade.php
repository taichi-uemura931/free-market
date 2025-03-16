<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '会員登録サイト')</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>

<body style="overflow: auto;">
    <div class="wrapper">

        <header class="header">
            <div class="header-container">

                <a href="{{ route('products.index') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="COACHTECHロゴ" class="logo">
                </a>

                @if (!request()->is('register') && !request()->is('login') && !request()->is('email/verify'))
                    <form class="search-form" action="{{ route('search') }}" method="GET">
                        <input type="text" name="query" value="{{ request()->input('query') }}" placeholder="なにをお探しですか？">
                    </form>


                    <nav class="nav-menu">
                        @if(Auth::check())
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a href="{{ route('mypage') }}">マイページ</a>
                            <a href="{{ route('product.create') }}" class="sell-button">出品</a>
                        @else
                            <a href="{{ route('login') }}">ログイン</a>
                            <a href="{{ route('register') }}">新規登録</a>
                        @endif
                    </nav>
                @endif
            </div>
        </header>

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    @yield('scripts')
    @stack('scripts')

</body>

</html>