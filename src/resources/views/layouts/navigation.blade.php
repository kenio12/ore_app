<nav class="navbar" x-data="{ showNavbar: true, lastScrollY: 0 }" 
    @scroll.window="
        currentScrollY = window.scrollY;
        showNavbar = currentScrollY <= 0 || currentScrollY < lastScrollY;
        lastScrollY = currentScrollY;
    "
    :class="{ 'navbar-hidden': !showNavbar }"
>
    <div class="container">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-text">
                <span class="brand-full-text">俺のアプリ</span>
                <button class="mobile-brand-btn">
                    <span class="mobile-brand-text">ア</span>
                </button>
            </span>
        </a>

        <div class="nav-buttons">
            @auth
                <a href="{{ route('my-apps') }}">
                    <button class="btn btn-primary">
                        <span class="btn-text">MyApps</span>
                        <span class="mobile-text">M</span>
                    </button>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-red">
                        <span class="btn-text">ログアウト</span>
                        <span class="mobile-text">出</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}">
                    <button class="btn btn-blue">
                        <span class="btn-text">ログイン</span>
                        <span class="mobile-text">入</span>
                    </button>
                </a>
                <a href="{{ route('register') }}">
                    <button class="btn btn-green">
                        <span class="btn-text">新規登録</span>
                        <span class="mobile-text">新</span>
                    </button>
                </a>
            @endauth
        </div>
    </div>
</nav>

<style>
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #1a1c2c, #4a5568);
    padding: 0.75rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
    z-index: 1000;
}

.navbar-hidden {
    transform: translateY(-100%);
}

.container {
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 0.5rem;
}

.brand {
    text-decoration: none;
    transition: opacity 0.2s;
}

.brand:hover {
    opacity: 0.9;
}

.brand-text {
    font-weight: bold;
    font-size: 1.1rem;
    color: white;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.brand-full-text {
    font-weight: bold;
    font-size: 1.25rem;
    color: white;
}

.nav-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-weight: 500;
    cursor: pointer;
    border: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.btn-green {
    background-color: #48BB78;
    color: white;
}

.btn-blue {
    background-color: #3182CE;
    color: white;
}

.btn-red {
    background-color: #E53E3E;
    color: white;
}

.btn-primary {
    background: linear-gradient(135deg, #FF6B6B, #FF8E53);
    color: white;
    font-weight: bold;
    padding: 0.5rem 1rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #FF8E53, #FF6B6B);
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
}

.mobile-brand-btn {
    display: none;
    background-color: #4A5568;
    color: white;
    border: none;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    font-weight: bold;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.2s;
}

.mobile-text {
    display: none;
}

@media (max-width: 640px) {
    .brand-full-text {
        display: none;
    }

    .mobile-brand-btn {
        display: flex;
    }

    .btn-text {
        display: none;
    }

    .mobile-text {
        display: block;
    }

    .btn {
        padding: 0.5rem;
        min-width: 2.5rem;
        justify-content: center;
    }

    .nav-buttons {
        gap: 0.5rem;
    }
}
</style> 