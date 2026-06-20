import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';



/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                'tenor': ['"Tenor Sans"', 'sans-serif'],
                'raleway': ['"Raleway"', 'sans-serif'],
                'basker': [ '"Baskervville"', 'sans-serif'],
            },
            colors: {
                'solar-gray': '#E0D6C3',
                'solar-blue': '#0D3A43',
                'solar-brown': '#67412A',
                'solar-orange': '#E36415',
                'solar-yellow': '#F9A826',
            }
        },
    },

    plugins: [forms, typography],
};
