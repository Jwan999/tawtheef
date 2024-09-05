/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme');

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
            fontFamily: {
                'sans': ['Dosis', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'orange': '#E26600',
                'orange-50': '#fefbf8',
                'orange-100': '#f6d1b3',
                'orange-400': '#fb923c',
                'orange-500': '#f97316',
                // 'dark': '#202020',
                'dark': '#3F3F46',
                'light': '#F4F4F5',
            },
            borderRadius: {
                'custom': '40%',  // You can replace '15px' with your desired value
            },
        },
    },
    plugins: [
        require('flowbite/plugin')
    ],
}
