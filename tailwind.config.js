/** @type {import('tailwindcss').Config} */

module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js"
    ],
    theme: {
        extend: {
            colors: {
                'orange': '#E26600',
                'orange-50': '#fefbf8',
                'orange-400': '#fb923c',
                'orange-500': '#f97316',
                // 'dark': '#202020',
                'dark': '#3F3F46',
                'light': '#F4F4F5',
            },
        },
    },
    plugins: [
        require('flowbite/plugin')
    ],
}
