<div class="flex flex-col h-full">
    <!-- Logo -->
    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 px-6 h-16 border-b border-line/50 flex-shrink-0"
        @if($isMobile ?? false) @click="sidebarOpen = false" @endif>
        <div class="relative">
            <div class="absolute inset-0 bg-gold/20 rounded-full blur-md"></div>
            <div class="relative p-2 bg-gradient-to-br from-ink-raised2 to-ink-raised border border-line rounded-full">
                <svg class="w-5 h-5 text-gold" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                </svg>
            </div>
        </div>
        <span class="text-bone font-bold text-lg tracking-tight">BUGGXIT<span class="text-gold">.</span>admin</span>
    </a>

    <!-- Nav links -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        @foreach ($adminNavLinks as $link)
            <a href="{{ route($link['route']) }}"
                @if($isMobile ?? false) @click="sidebarOpen = false" @endif
                class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-300 border
                      {{ request()->routeIs($link['match']) ? 'bg-gold/10 text-gold border-gold/30' : 'text-bone-dim border-transparent hover:text-gold hover:bg-ink-raised2/50' }}">
                <i class="fas {{ $link['icon'] }} mr-3 w-4 text-center"></i>{{ $link['label'] }}
            </a>
        @endforeach
    </nav>

    <!-- User info + logout -->
    <div class="border-t border-line/50 p-4 flex-shrink-0">
        <div class="flex items-center space-x-3 mb-3 px-2">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gold to-gold-dim flex items-center justify-center flex-shrink-0">
                <span class="text-xs font-bold text-ink">{{ substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1) }}</span>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-medium text-bone truncate">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-bone-dim truncate">{{ Auth::guard('admin')->user()->email ?? '' }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center px-3 py-2.5 rounded-lg text-sm text-bone-dim hover:text-bad hover:bg-bad/10 transition-all duration-200">
                <i class="fas fa-sign-out-alt mr-3 w-4 text-center"></i> Logout
            </button>
        </form>
    </div>
</div>
