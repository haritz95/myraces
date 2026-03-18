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
                sans: ['Public Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary:       '#C8FA5F',
                'bg-app':      'var(--color-bg-app)',
                'bg-card':     'var(--color-bg-card)',
                'bg-elevated': 'var(--color-bg-elevated)',
                'bg-surface':  'var(--color-bg-surface)',
                'bg-input':    'var(--color-bg-input)',
                'bg-dark':     'var(--color-bg-app)',
                'bg-warm':     'var(--color-bg-app)',
            },
            boxShadow: {
                'card':    '0 1px 3px rgba(0,0,0,0.4), 0 1px 2px rgba(0,0,0,0.3)',
                'card-up': '0 4px 16px rgba(0,0,0,0.5), 0 2px 4px rgba(0,0,0,0.3)',
                'fab':     '0 8px 24px rgba(200,250,95,0.35)',
                'glow':    '0 0 20px rgba(200,250,95,0.25)',
            },
        },
    },

    plugins: [forms],
};
