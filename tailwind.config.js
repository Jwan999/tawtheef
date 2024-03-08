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
                'dark': '#202020',
                'light': '#EAEAEA',
            },
        },
    },
    plugins: [
        require('flowbite/plugin')
    ],
}
