/** @type {import('tailwindcss').Config} */
export default {
    // INI ADALAH KUNCI UTAMANYA
    darkMode: "class",

    // Ini adalah path dari baris @source Anda,
    // tapi dalam format yang benar
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
    ],

    // Ini adalah @theme Anda, dalam format yang benar
    theme: {
        extend: {
            fontFamily: {
                sans: [
                    '"Instrument Sans"',
                    "ui-sans-serif",
                    "system-ui",
                    "sans-serif",
                    '"Apple Color Emoji"',
                    '"Segoe UI Emoji"',
                    '"Segoe UI Symbol"',
                    '"Noto Color Emoji"',
                ],
            },
        },
    },

    plugins: [],
};
