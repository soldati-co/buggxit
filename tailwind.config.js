import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Manrope', ...defaultTheme.fontFamily.sans],
                display: ['"Fraunces Variable"', 'Georgia', 'serif'],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                ink: {
                    DEFAULT: '#0b0908',
                    raised: '#17130f',
                    raised2: '#1e1811',
                },
                line: {
                    DEFAULT: '#2e2620',
                    soft: '#211b16',
                },
                gold: {
                    dim: '#8a6530',
                    DEFAULT: '#b8873f',
                    bright: '#e3b968',
                },
                bone: {
                    DEFAULT: '#f3ede3',
                    dim: '#a89a87',
                    faint: '#6f6455',
                },
                good: '#7c9473',
                warn: '#c99a4a',
                bad: '#b0503a',
                info: '#6b8299',
            },
            screens: {
                'sm': '640px',
                'md': '768px',
                'tablet': '777px', // Custom breakpoint for your specific screen size
                'lg': '1024px',
                'xl': '1280px',
                '2xl': '1536px',
            },
            animation: {
                'slide-down': 'slideDown 0.3s ease-out',
                'slide-up': 'slideUp 0.2s ease-in',
                'fade-in': 'fadeIn 0.2s ease-out',
                'fade-out': 'fadeOut 0.15s ease-in',
            },
            keyframes: {
                slideDown: {
                    '0%': { opacity: '0', transform: 'translateY(-10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideUp: {
                    '0%': { opacity: '1', transform: 'translateY(0)' },
                    '100%': { opacity: '0', transform: 'translateY(-10px)' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeOut: {
                    '0%': { opacity: '1' },
                    '100%': { opacity: '0' },
                },
            },
        },
    },

    plugins: [forms],
};