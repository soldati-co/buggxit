@extends('layouts.app')

@section('title', 'Redirecting to PayFast – BUGGXIT Couture')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-16">
        <div class="max-w-md w-full text-center bg-ink-raised/90 backdrop-blur-sm border border-line rounded-2xl p-10">
            <div class="w-16 h-16 mx-auto mb-6 border-4 border-gold/30 border-t-gold rounded-full animate-spin"></div>
            <h1 class="text-2xl font-bold text-bone mb-2">Redirecting to PayFast&hellip;</h1>
            <p class="text-bone-dim mb-6">Please wait while we securely redirect you to complete payment for order
                <span class="font-mono text-bone">{{ $order->order_number }}</span>.</p>

            <div id="payfast-form">
                {!! $formHtml !!}
            </div>

            <p class="text-xs text-bone-faint mt-6">
                <i class="fas fa-lock mr-1"></i> If you are not redirected automatically, click the button above.
            </p>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', function () {
            var form = document.querySelector('#payfast-form form');
            if (form) {
                form.submit();
            }
        });
    </script>
@endsection
