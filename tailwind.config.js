import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                'playfair': ['Playfair Display', 'serif'],
            },
        },
    },

    plugins: [forms],
    safelist: [
        // Asegúrate de que Tailwind NO purgue estas clases
        'from-slate-950',
        'via-purple-950',
        'to-slate-950',
        'from-purple-600/20',
        'from-purple-300',
        'via-pink-300',
        'to-purple-300',
        'text-emerald-400',
        'border-emerald-500/50',
        'text-emerald-300',
        'from-slate-800/80',
        'to-slate-900/80',
        'border-slate-700/50',
        'from-purple-600',
        'to-pink-600',
        'from-purple-600/30',
        'to-pink-600/30',
        'border-purple-500/50',
        'from-purple-300',
        'to-pink-300',
    ],

};
