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
    <header class="header">
        <div class="header__container">
            <div class="header__brand">
                <a href="/" class="header__logo">
                    <img src="{{ asset('storage/img/logo.svg') }}" alt="coachtechロゴ">
                </a>
            </div>

            @if (!Request::is('login') && !Request::is('register') && !Request::is('email/verify*'))
                <div class="header__search">
                    <form action="{{ url('/') }}" method="GET" class="header__search-form">
                        <input type="text" name="keyword" class="header__search-input" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
                    </form>
                </div>

                <nav class="header__menu">
                    @auth
                        <form action="/logout" method="post">
                            @csrf
                            <button type="submit" class="header__button header__button--logout">ログアウト</button>
                        </form>
                    @endauth

                    @guest
                        <a href="/login" class="header__button header__button--login">ログイン</a>
                    @endguest

                    <a href="{{ route('mypage.show') }}" class="header__button header__button--mypage">マイページ</a>
                    <a href="{{ route('sell.show') }}" class="header__button header__button--sell">出品</a>
                </nav>
            @endif
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>
