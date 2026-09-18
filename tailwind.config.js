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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                indigo: {
                    50: '#fdf3f2',
                    100: '#fae3e1',
                    200: '#f6cbc7',
                    300: '#f0a8a1',
                    400: '#ea786e',
                    500: '#e14a3e',
                    600: '#da1705',
                    700: '#b81002',
                    800: '#981005',
                    900: '#7e130a',
                    950: '#440501',
                },
            },
        },
    },

    plugins: [forms],
};
