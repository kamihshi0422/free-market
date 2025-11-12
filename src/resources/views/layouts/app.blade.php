<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>
<body>
    <header class="layout-header">
        <div class="layout-header-container">
            <div class="layout-header-brand">
                <a href="/" class="layout-header-logo"><img src="{{ asset('storage/img/logo.svg') }}" alt="coachtechロゴ"></a>
            </div>

            @if (!Request::is('login') && !Request::is('register') && !Request::is('email/verify*'))
                <div class="layout-header-search">
                    <form action="{{ url('/') }}" method="GET" class="layout-header-search-form">
                        <input type="text" name="keyword" class="layout-header-search-input" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
                    </form>
                </div>

                <nav class="layout-header-menu">
                    @auth
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="layout-header-button layout-header-button-logout">ログアウト</button>
                        </form>
                    @endauth

                    @guest
                        <a href="{{ route('login.show') }}" class="layout-header-button layout-header-button-login">ログイン</a>
                    @endguest

                    <a href="{{ route('profile.show') }}" class="layout-header-button layout-header-button-mypage">マイページ</a>
                    <a href="{{ route('exhibition.show') }}" class="layout-header-button layout-header-button-sell">出品</a>
                </nav>
            @endif
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>