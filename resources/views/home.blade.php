@extends('layouts.app')

@section('title', 'GameLink - Marketplace de Videojuegos')

@section('content')

    {{-- ===== HERO BANNER ===== --}}
    <section class="relative rounded-xl overflow-hidden mb-10 mt-4" style="background: linear-gradient(135deg, #0d1520 0%, #1a2433 50%, #0d2030 100%); min-height: 320px;">
        <div class="absolute inset-0 opacity-50" style="background: url('https://plus.unsplash.com/premium_vector-1725298133648-3ab96f885592?fm=jpg&q=60&w=3000&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') center/cover no-repeat;"></div>
        <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(13,21,32,0.95) 10%, rgba(13,21,32,0.3) 100%);"></div>

        <div class="relative z-10 flex flex-col items-center justify-center text-center px-8 py-20">
            <h1 class="text-5xl font-bold text-white mb-4 leading-tight">
                Encuentra tu próximo juego<br>al mejor precio
            </h1>
            <p class="text-gray-400 text-base mb-8 max-w-xl">
                La mayor comunidad de compra y venta de videojuegos. Únete a miles de jugadores y ahorra en tus títulos favoritos.
            </p>

            {{-- Barra de búsqueda hero --}}
            <form action="{{ route('home') }}" method="GET" class="w-full max-w-2xl">
                @if(request('platform'))
                    <input type="hidden" name="platform" value="{{ request('platform') }}">
                @endif
                <div class="flex items-center bg-gray-800/80 backdrop-blur border border-gray-600 rounded-xl px-4 py-3 gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input
                        id="hero-search-input"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="¿Qué quieres jugar hoy?"
                        class="bg-transparent text-sm text-white placeholder-gray-400 outline-none flex-1"
                        autocomplete="off"
                    />
                </div>
            </form>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('hero-search-input');
                const gameGrid = document.querySelector('.grid');
                if (!searchInput || !gameGrid) return;
                const allGames = Array.from(gameGrid.children);
                searchInput.addEventListener('input', function() {
                    const value = this.value.trim().toLowerCase();
                    if (value === '') {
                        allGames.forEach(el => el.style.display = '');
                        return;
                    }
                    allGames.forEach(el => {
                        const title = el.querySelector('h3')?.textContent?.toLowerCase() || '';
                        el.style.display = title.includes(value) ? '' : 'none';
                    });
                });
            });
            </script>
        </div>
    </section>

    {{-- ===== LAYOUT PRINCIPAL: SIDEBAR + CONTENIDO ===== --}}
    <div class="flex gap-7">

        {{-- ===== SIDEBAR IZQUIERDA ===== --}}
        <aside class="hidden md:flex flex-col gap-6 w-52 flex-shrink-0 sticky top-4 self-start h-fit max-h-screen overflow-y-auto">

            {{-- Plataforma --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Plataforma</h3>
                <div class="flex flex-col gap-1">
                    <a href="{{ route('home', array_filter(['search' => request('search')])) }}"
                       data-platform=""
                       class="filter-link flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ !request('platform') ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Todas
                    </a>
                    <a href="{{ route('home', array_filter(['platform' => 'PC', 'search' => request('search')])) }}"
                       data-platform="PC"
                       class="filter-link flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ request('platform') == 'PC' ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                        </svg>
                        PC
                    </a>
                    <a href="{{ route('home', array_filter(['platform' => 'PS5', 'search' => request('search')])) }}"
                       data-platform="PS5"
                       class="filter-link flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ request('platform') == 'PS5' ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="currentColor">
                            <path d="M8.984 2.596v17.547l3.915 1.261V6.688c0-.69.304-1.151.794-.991.636.18.76.814.76 1.505v5.875c2.441 1.193 4.362-.002 4.362-3.152 0-3.237-1.126-4.675-4.438-5.827-1.307-.448-3.728-1.186-5.39-1.502zm4.656 16.241l6.296-2.275c.715-.258.826-.625.246-.818-.586-.192-1.637-.139-2.357.123l-4.205 1.5V14.98l.24-.085s1.201-.42 2.913-.615c1.696-.18 3.785.03 5.437.661 1.848.601 2.04 1.472 1.576 2.072-.465.6-1.622 1.036-1.622 1.036l-8.544 3.107V18.86zM1.807 18.6c-1.9-.545-2.214-1.668-1.352-2.32.801-.586 2.16-1.052 2.16-1.052l5.615-2.013v2.313L4.205 17c-.705.271-.825.632-.239.826.586.195 1.637.15 2.343-.12L8.247 17v2.074c-.12.03-.256.044-.39.073-1.939.331-3.996.196-6.038-.479z"/>
                        </svg>
                        PS5
                    </a>
                    <a href="{{ route('home', array_filter(['platform' => 'Xbox', 'search' => request('search')])) }}"
                       data-platform="Xbox"
                       class="filter-link flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ request('platform') == 'Xbox' ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="currentColor">
                            <path d="m24 12c0-.001 0-.001 0-.002 0-3.618-1.606-6.861-4.144-9.054l-.015-.013c-1.91 1.023-3.548 2.261-4.967 3.713l-.004.004c.044.046.087.085.131.132 3.719 4.012 7.106 9.73 6.546 12.471 1.53-1.985 2.452-4.508 2.452-7.246 0-.002 0-.004 0-.006z"></path>
                            <path d="m12.591 3.955c1.68-1.104 3.699-1.833 5.872-2.022l.048-.003c-1.837-1.21-4.09-1.929-6.511-1.929-2.171 0-4.207.579-5.962 1.591l.058-.031c.658.567 2.837.781 5.484 2.4.143.089.316.142.502.142.189 0 .365-.055.513-.149l-.004.002z"></path>
                            <path d="m9.166 6.778c.046-.049.093-.09.138-.138-1.17-1.134-2.446-2.174-3.806-3.1l-.099-.064c-.302-.221-.681-.354-1.091-.354-.146 0-.288.017-.425.049l.013-.002c-2.398 2.198-3.896 5.344-3.896 8.84 0 2.909 1.037 5.576 2.762 7.651l-.016-.02c-1.031-2.547 2.477-8.672 6.419-12.862z"></path>
                            <path d="m12.084 9.198c-3.962 3.503-9.477 8.73-8.632 11.218 2.174 2.213 5.198 3.584 8.542 3.584 3.493 0 6.637-1.496 8.826-3.883l.008-.009c.486-2.618-4.755-7.337-8.744-10.91z"></path>
                        </svg>
                        Xbox
                    </a>
                    <a href="{{ route('home', array_filter(['platform' => 'Nintendo Switch', 'search' => request('search')])) }}"
                       data-platform="Nintendo Switch"
                       class="filter-link flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ request('platform') == 'Nintendo Switch' ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="currentColor">
                            <path d="M18.901 32h4.901c4.5 0 8.198-3.698 8.198-8.198v-15.604c0-4.5-3.698-8.198-8.198-8.198h-5c-0.099 0-0.203 0.099-0.203 0.198v31.604c0 0.099 0.099 0.198 0.302 0.198zM25 14.401c1.802 0 3.198 1.5 3.198 3.198 0 1.802-1.5 3.198-3.198 3.198-1.802 0-3.198-1.396-3.198-3.198-0.104-1.797 1.396-3.198 3.198-3.198zM15.198 0h-7c-4.5 0-8.198 3.698-8.198 8.198v15.604c0 4.5 3.698 8.198 8.198 8.198h7c0.099 0 0.203-0.099 0.203-0.198v-31.604c0-0.099-0.099-0.198-0.203-0.198zM12.901 29.401h-4.703c-3.099 0-5.599-2.5-5.599-5.599v-15.604c0-3.099 2.5-5.599 5.599-5.599h4.604zM5 9.599c0 1.698 1.302 3 3 3s3-1.302 3-3c0-1.698-1.302-3-3-3s-3 1.302-3 3z"></path>
                        </svg>
                        Nintendo Switch
                    </a>
                    <a href="{{ route('home', array_filter(['platform' => 'Retro', 'search' => request('search')])) }}"
                       data-platform="Retro"
                       class="filter-link flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ request('platform') == 'Retro' ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="currentColor">
                            <path d="M11.923 0a3.59 3.59 0 0 0-1.531 6.839c.04.475.18 2.156.315 3.874a1.356 1.356 0 0 1-.126.016c-.25.016-.499-.027-.748-.007-.32.024-.59.197-.914.197-.298 0-.608-.006-.88.136-.446.232-1.106.086-1.474.467-.298.308-.859.153-1.199.475-.088.083-.101.222-.213.26-.126.043-.257.07-.383.113-.247.083-.51.226-.607.486-.061.166.022.188-.146.257a1.38 1.38 0 0 0-.33.182c-.182.141-.231.336-.258.568-.002.017.003.315.003.314a.918.918 0 0 0-.221.256c-.133.235-.107.484-.009.728.107.264.198.364.209.636-.012.13.05.266.098.383.192.47.307.835.831.884l1.48 3.964C6.564 23.015 9.25 24 11.949 24c2.72 0 5.448-1.001 6.204-2.986l1.522-4.002c.327-.06.603-.178.726-.538.086-.189.174-.393.202-.6a.674.674 0 0 0-.01-.249c.062-.08.123-.15.167-.243.211-.445.162-.964-.268-1.25.114-.407-.014-.695-.385-.91-.188-.109-.29-.091-.347-.296-.053-.19-.14-.339-.307-.437-.215-.126-.458-.15-.684-.243-.093-.242-.33-.385-.565-.462-.195-.064-.398-.073-.594-.126-.203-.054-.317-.242-.524-.318-.225-.081-.463-.089-.698-.113-.253-.027-.43-.18-.669-.243-.253-.066-.502-.05-.758-.065-.258-.015-.476-.15-.73-.182-.202-.026-.403-.009-.606-.001a2.01 2.01 0 0 1-.474-.053c.136-1.721.266-3.391.3-3.843A3.59 3.59 0 0 0 11.924 0zm.95.826c.292-.007.684.158 1.009.518.518.573.59 1.257.332 1.397-.261.145-.741-.145-1.187-.529-.438-.388-.721-.863-.536-1.193.068-.123.207-.19.382-.193zM10.766 6.99a3.584 3.584 0 0 0 2.312 0c-.197 2.54-.459 5.87-.486 6.08-.014.083-.098.176-.218.242a1 1 0 0 1-.464.102c-.027 0-.664-.005-.68-.409-.032-.825-.342-4.563-.464-6.015z"/>
                        </svg>
                        Retro
                    </a>
                </div>
            </div>

            {{-- Separador --}}
            <div class="border-t border-gray-700"></div>

            {{-- Géneros --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Géneros</h3>
                <div class="flex flex-col gap-1">

                    {{-- Todos --}}
                    <a href="{{ route('home', array_filter(['platform' => request('platform'), 'search' => request('search')])) }}"
                       data-genre=""
                       class="filter-link flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ !request('genre') ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>
                        Todos
                    </a>

                    {{-- Acción --}}
                    <a href="{{ route('home', array_filter(['platform' => request('platform'), 'genre' => 'ACTION', 'search' => request('search')])) }}"
                       data-genre="ACTION"
                       class="filter-link flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ request('genre') == 'ACTION' ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                        </svg>
                        Acción
                    </a>
                    <a href="{{ route('home', array_filter(['platform' => request('platform'), 'genre' => 'RPG', 'search' => request('search')])) }}"
                       data-genre="RPG"
                       class="filter-link flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ request('genre') == 'RPG' ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                        </svg>
                        RPG
                    </a>
                    <a href="{{ route('home', array_filter(['platform' => request('platform'), 'genre' => 'SPORTS', 'search' => request('search')])) }}"
                       data-genre="SPORTS"
                       class="filter-link flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ request('genre') == 'SPORTS' ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5" />
                        </svg>
                        Deportes
                    </a>
                    <a href="{{ route('home', array_filter(['platform' => request('platform'), 'genre' => 'STRATEGY', 'search' => request('search')])) }}"
                       data-genre="STRATEGY"
                       class="filter-link flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ request('genre') == 'STRATEGY' ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                        </svg>
                        Estrategia
                    </a>
                </div>
            </div>

            {{-- Separador --}}
            <div class="border-t border-gray-700"></div>

            {{-- Rango de precio --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Rango de Precio</h3>
                <input
                    type="range"
                    min="0"
                    max="200"
                    value="{{ request('max_price', 200) }}"
                    id="price-range"
                    class="w-full accent-[#009194]"
                />
                <div class="flex justify-between text-xs text-gray-400 mt-1">
                    <span>$0</span>
                    <span id="price-value">${{ request('max_price', 200) }}{{ request('max_price', 200) == 200 ? '+' : '' }}</span>
                </div>
            </div>

            {{-- Separador --}}
            <div class="border-t border-gray-700"></div>

            {{-- Rating --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Valoración mínima</h3>
                <input
                    type="range"
                    min="0"
                    max="5"
                    step="0.1"
                    value="{{ request('rating', 0) }}"
                    id="rating-range"
                    class="w-full accent-[#009194]"
                />
                <div class="flex justify-between text-xs text-gray-400 mt-1">
                    <span>★ 0</span>
                    <span id="rating-value">★ {{ number_format(request('rating', 0), 1) }}</span>
                </div>
            </div>

        </aside>

        {{-- ===== CONTENIDO PRINCIPAL ===== --}}
        <div id="game-grid-wrapper" class="flex-1 min-w-0">

            {{-- Cabecera sección --}}
            <div class="flex items-center justify-between mb-6">
    <h2 class="text-white font-bold text-lg">Explorar Juegos</h2>

    <form id="sort-form" action="{{ route('home') }}" method="GET" class="flex items-center gap-3">
        {{-- Mantener los filtros actuales como inputs ocultos --}}
        @foreach(request()->except('sort') as $k => $v)
            @if(is_array($v))
                @foreach($v as $vv)
                    <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endif
        @endforeach

        <label class="text-sm text-gray-400 mr-2">Ordenar por:</label>
        <select name="sort" onchange="document.getElementById('sort-form').submit()"
                class="bg-gray-800 border border-gray-700 text-xs text-white px-3 py-2 rounded">
            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Más populares</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Más barato</option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Más caro</option>
            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre</option>
        </select>
    </form>
</div>

            {{-- Grid de juegos --}}
            <div class="grid grid-cols-3 md:grid-cols-4 gap-4">
                @forelse($games as $game)
                    {{-- Wrapper relativo --}}
                    <div class="relative group">

                        <a href="{{ route('games.show', $game->id) }}"
                           class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-[#009194] transition-colors block">

                            {{-- Imagen --}}
                            <div class="relative bg-gray-700 flex items-center justify-center" style="height: 300px;">
                                @if($game->cover_image)
                                    <img src="{{ $game->cover_image }}"
                                         alt="{{ $game->title }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <span class="text-4xl">🎮</span>
                                @endif

                                {{-- Badge plataforma --}}
                                @if(!empty($game->platforms))
                                    @php $platforms = is_array($game->platforms) ? $game->platforms : [$game->platforms]; @endphp
                                    <div class="absolute top-2 left-2 flex flex-wrap gap-1 pr-8">
                                        @foreach($platforms as $platform)
                                            <span class="text-xs text-white px-2 py-0.5 rounded font-medium inline-block"
                                                style="background-color: {{ ['PS5' => '#1a4fc4', 'Xbox' => '#107c10', 'Nintendo Switch' => '#e4000f', 'PC' => '#111111', 'Retro' => '#b45309'][$platform] ?? '#009194' }}">
                                                {{ $platform }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-3">
                                <p class="text-gray-500 text-xs mb-0.5">{{ $game->genre }}</p>
                                <h3 class="text-white text-sm font-semibold mb-2 truncate" title="{{ $game->title }}">
                                    {{ $game->title }}
                                </h3>

                                @if($game->rating)
                                    <div class="flex items-center gap-1 mb-2">
                                        <span class="text-yellow-400 text-xs">★</span>
                                        <span class="text-gray-400 text-xs">{{ number_format($game->rating, 1) }}</span>
                                    </div>
                                @endif

                                <div class="flex items-center justify-between">
                                    <span class="text-white font-bold text-sm">
                                        @if($game->getLowestPrice())
                                            {{ number_format($game->getLowestPrice(), 2) }}€
                                            <span class="text-gray-500 font-normal text-xs ml-1">
                                                {{ $game->game_ads_count }} {{ $game->game_ads_count == 1 ? 'oferta' : 'ofertas' }}
                                            </span>
                                        @else
                                            <span class="text-gray-500 font-normal text-xs">Sin stock</span>
                                        @endif
                                    </span>
                                    @if($game->getLowestPrice())
                                        <span class="text-xs bg-[#009194]/20 text-[#3bb1a5] px-2 py-0.5 rounded font-medium">Ver Ofertas</span>
                                    @endif
                                </div>
                            </div>
                        </a>

                        {{-- Corazón --}}
                        @auth
                            <button
                                class="favorite-btn absolute top-3 right-3 z-10 bg-gray-900/70 rounded-full w-7 h-7 flex items-center justify-center text-sm leading-none transition-all hover:scale-110"
                                style="color: {{ in_array($game->id, $favoriteIds) ? '#f87171' : '#d1d5db' }};"
                                data-game-id="{{ $game->id }}"
                                data-favorited="{{ in_array($game->id, $favoriteIds) ? 'true' : 'false' }}"
                                onclick="toggleFavorite(this)">
                                {{ in_array($game->id, $favoriteIds) ? '♥' : '♡' }}
                            </button>
                        @else
                            <a href="{{ route('login') }}"
                               class="absolute top-3 right-3 z-10 text-gray-300 hover:text-red-400 bg-gray-900/70 rounded-full w-7 h-7 flex items-center justify-center text-sm leading-none transition-colors">
                                ♡
                            </a>
                        @endauth

                    </div>
                @empty
                    <div class="col-span-4 text-center text-gray-500 py-16">
                        <p class="text-5xl mb-4">🎮</p>
                        <p class="text-sm">No hay juegos disponibles de momento.</p>
                    </div>
                @endforelse
            </div>

            {{-- Paginación --}}
            <div class="mt-8">
                {{ $games->links() }}
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
function toggleFavorite(btn) {
    event.preventDefault();
    event.stopPropagation();

    const gameId = btn.getAttribute('data-game-id');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/favorites/${gameId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 401) alert('Debes iniciar sesión');
            throw new Error('Error en la petición');
        }
        return response.json();
    })
    .then(data => {
        if (data.favorited) {
            btn.textContent = '♥';
            btn.style.color = '#f87171';
        } else {
            btn.textContent = '♡';
            btn.style.color = '#d1d5db';
        }
    })
    .catch(error => console.error('Error:', error));
}

(function () {
    const ROUTE_HOME = '{{ route('home') }}';
    let debounceTimer = null;

    function debounce(fn, delay) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fn, delay);
    }

    async function loadFiltered(params) {
        const url = ROUTE_HOME + (params.toString() ? '?' + params.toString() : '');
        const wrapper = document.getElementById('game-grid-wrapper');
        if (!wrapper) return;

        wrapper.style.opacity = '0.5';
        wrapper.style.pointerEvents = 'none';

        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) return;
            const html = await res.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const newWrapper = doc.getElementById('game-grid-wrapper');
            if (newWrapper) {
                wrapper.innerHTML = newWrapper.innerHTML;
            }
            history.pushState({}, '', url);
            updateSidebarActive(params);
        } catch (e) {
            console.error('Filter error:', e);
        } finally {
            wrapper.style.opacity = '';
            wrapper.style.pointerEvents = '';
        }
    }

    function updateSidebarActive(params) {
        const platform = params.get('platform') ?? '';
        const genre = params.get('genre') ?? '';

        document.querySelectorAll('[data-platform]').forEach(el => {
            const isActive = el.dataset.platform === platform;
            el.classList.toggle('bg-[#009194]/20', isActive);
            el.classList.toggle('text-[#3bb1a5]', isActive);
            el.classList.toggle('font-medium', isActive);
            el.classList.toggle('text-gray-400', !isActive);
            el.classList.toggle('hover:text-white', !isActive);
            el.classList.toggle('hover:bg-gray-800', !isActive);
        });

        document.querySelectorAll('[data-genre]').forEach(el => {
            const isActive = el.dataset.genre === genre;
            el.classList.toggle('bg-[#009194]/20', isActive);
            el.classList.toggle('text-[#3bb1a5]', isActive);
            el.classList.toggle('font-medium', isActive);
            el.classList.toggle('text-gray-400', !isActive);
            el.classList.toggle('hover:text-white', !isActive);
            el.classList.toggle('hover:bg-gray-800', !isActive);
        });
    }

    document.addEventListener('click', function (e) {
        const filterLink = e.target.closest('.filter-link');
        if (filterLink) {
            e.preventDefault();
            const params = new URLSearchParams(window.location.search);
            params.delete('page');

            if ('platform' in filterLink.dataset) {
                if (filterLink.dataset.platform === '') {
                    params.delete('platform');
                } else {
                    params.set('platform', filterLink.dataset.platform);
                }
            }
            if ('genre' in filterLink.dataset) {
                if (filterLink.dataset.genre === '') {
                    params.delete('genre');
                } else {
                    params.set('genre', filterLink.dataset.genre);
                }
            }

            loadFiltered(params);
            return;
        }

        const wrapper = document.getElementById('game-grid-wrapper');
        if (wrapper) {
            const paginationLink = e.target.closest('a[href]');
            if (paginationLink && wrapper.contains(paginationLink)) {
                e.preventDefault();
                const newParams = new URL(paginationLink.href, window.location.origin).searchParams;
                loadFiltered(newParams);
            }
        }
    });

    const priceRange = document.getElementById('price-range');
    const priceValue = document.getElementById('price-value');
    if (priceRange && priceValue) {
        priceRange.addEventListener('input', function () {
            priceValue.textContent = '$' + this.value + (this.value == 200 ? '+' : '');
            const params = new URLSearchParams(window.location.search);
            params.delete('page');
            if (this.value == 200) {
                params.delete('max_price');
            } else {
                params.set('max_price', this.value);
            }
            debounce(() => loadFiltered(params), 500);
        });
    }

    const ratingRange = document.getElementById('rating-range');
    const ratingValue = document.getElementById('rating-value');
    if (ratingRange && ratingValue) {
        ratingRange.addEventListener('input', function () {
            ratingValue.textContent = '★ ' + parseFloat(this.value).toFixed(1);
            const params = new URLSearchParams(window.location.search);
            params.delete('page');
            if (parseFloat(this.value) === 0) {
                params.delete('rating');
            } else {
                params.set('rating', this.value);
            }
            debounce(() => loadFiltered(params), 500);
        });
    }

    window.addEventListener('popstate', function () {
        const params = new URLSearchParams(window.location.search);
        if (priceRange) priceRange.value = params.get('max_price') ?? 200;
        if (ratingRange) ratingRange.value = params.get('rating') ?? 0;
        if (priceValue) priceValue.textContent = '$' + (params.get('max_price') ?? '200') + (!params.get('max_price') ? '+' : '');
        if (ratingValue) ratingValue.textContent = '★ ' + parseFloat(params.get('rating') ?? 0).toFixed(1);
        updateSidebarActive(params);
        loadFiltered(params);
    });
})();
</script>
@endpush
