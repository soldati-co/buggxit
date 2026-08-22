@extends('layouts.app')

@section('title', 'Size Guide | Buggxit Couture Traditional Wear')

@section('meta_description', 'Find your perfect fit with the Buggxit Couture size guide. Measure at home and shop ready-made traditional ceremony wear with confidence.')

@section('content')
    @php
        $whatsappNumber = \Illuminate\Support\Facades\Schema::hasTable('settings')
            ? \App\Models\Setting::get('whatsapp_number')
            : null;
        $whatsappDigits = $whatsappNumber ? preg_replace('/\D/', '', $whatsappNumber) : null;
    @endphp

    <section class="relative mb-16 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-gold/10 rounded-full blur-3xl"></div>
        <div class="relative z-10 bg-gradient-to-br from-ink-raised via-ink-raised2 to-ink-raised border-y border-line/50 py-14 md:py-20">
            <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto text-center max-w-2xl">
                <h1 class="text-4xl md:text-5xl font-bold text-bone mb-3">Find Your Fit</h1>
                <p class="text-bone-dim">
                    Every woman is different. Use this guide to find your size before you order — and reach out
                    @if($whatsappDigits)
                        via <a href="https://wa.me/{{ $whatsappDigits }}" target="_blank" rel="noopener noreferrer" class="text-gold hover:text-gold-bright transition-colors">WhatsApp</a>
                    @else
                        via <a href="{{ route('contact') }}" class="text-gold hover:text-gold-bright transition-colors">WhatsApp</a>
                    @endif
                    if you need help.
                </p>
            </div>
        </div>
    </section>

    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20">
        <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-3xl p-8 md:p-12 space-y-10 text-bone-dim leading-relaxed">

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">How to measure yourself</h2>
                <p class="mb-4">Use a soft tape measure and measure over light clothing, keeping the tape level and snug but not tight.</p>
                <ul class="space-y-3">
                    <li class="flex items-start gap-2">
                        <i class="fas fa-ruler text-gold mt-1"></i>
                        <span><strong class="text-bone">Bust</strong> — measure around the fullest part of your bust, keeping the tape level across your back.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-ruler text-gold mt-1"></i>
                        <span><strong class="text-bone">Waist</strong> — measure around the narrowest part of your natural waistline, just above your belly button.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-ruler text-gold mt-1"></i>
                        <span><strong class="text-bone">Hips</strong> — measure around the fullest part of your hips, roughly 20cm below your waist.</span>
                    </li>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Size chart</h2>
                <p class="mb-4">A general guide across our ready-made collections, in centimetres. Fit can vary slightly by style — check the product page for any specific notes before ordering.</p>
                <div class="overflow-x-auto rounded-xl border border-line">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-ink-raised2/50 text-bone">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Size</th>
                                <th class="px-4 py-3 font-semibold">Bust (cm)</th>
                                <th class="px-4 py-3 font-semibold">Waist (cm)</th>
                                <th class="px-4 py-3 font-semibold">Hips (cm)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @php
                                $sizeChart = [
                                    ['size' => 'XS', 'bust' => '80–84', 'waist' => '61–65', 'hips' => '87–91'],
                                    ['size' => 'S', 'bust' => '85–92', 'waist' => '66–73', 'hips' => '92–99'],
                                    ['size' => 'M', 'bust' => '93–100', 'waist' => '74–81', 'hips' => '100–107'],
                                    ['size' => 'L', 'bust' => '101–108', 'waist' => '82–89', 'hips' => '108–115'],
                                    ['size' => 'XL', 'bust' => '109–116', 'waist' => '90–97', 'hips' => '116–123'],
                                ];
                            @endphp
                            @foreach ($sizeChart as $row)
                                <tr>
                                    <td class="px-4 py-3 text-gold font-semibold">{{ $row['size'] }}</td>
                                    <td class="px-4 py-3">{{ $row['bust'] }}</td>
                                    <td class="px-4 py-3">{{ $row['waist'] }}</td>
                                    <td class="px-4 py-3">{{ $row['hips'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-bone mb-3">Between sizes, or need a custom fit?</h2>
                <p>
                    If your measurements fall between two sizes, we generally recommend sizing up. For a piece
                    built entirely around your own measurements, custom sizing is available for made-to-measure
                    orders — select "Custom Order Enquiry" on our
                    <a href="{{ route('contact') }}" class="text-gold hover:text-gold-bright transition-colors">Contact page</a>
                    and we'll take it from there.
                </p>
            </div>

        </div>
    </section>
@endsection
