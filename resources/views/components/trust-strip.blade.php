@php
    $trustItems = [
        ['icon' => 'fa-truck', 'title' => 'Nationwide Delivery', 'desc' => 'The Courier Guy & PEP Paxi'],
        ['icon' => 'fa-lock', 'title' => 'Secure Checkout', 'desc' => 'PayFast & Yoco'],
        ['icon' => 'fa-arrows-rotate', 'title' => '7-Day Exchange Policy', 'desc' => null],
        ['icon' => 'fa-flag', 'title' => 'Proudly South African', 'desc' => 'Est. 2018'],
    ];
@endphp

<section class="border-y border-line/50 bg-ink-raised/50">
    <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto py-5">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            @foreach ($trustItems as $item)
                <div class="flex items-center gap-3">
                    <i class="fas {{ $item['icon'] }} text-gold text-lg flex-shrink-0"></i>
                    <div class="leading-tight">
                        <div class="text-bone text-sm font-semibold">{{ $item['title'] }}</div>
                        @if ($item['desc'])
                            <div class="text-bone-faint text-xs">{{ $item['desc'] }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
