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
        "./node_modules/flowbite/**/*.js",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: "#fdf5fe",
                    100: "#fae9fe",
                    200: "#f5d3fb",
                    300: "#f0b0f7",
                    400: "#e880f2",
                    500: "#d850e5",
                    600: "#be30c9",
                    700: "#9f24a5",
                    800: "#842088",
                    900: "#6e1f70",
                    950: "#49084a",
                },
            },
        },
    },

    plugins: [forms, typography, require('flowbite/plugin')],
};
