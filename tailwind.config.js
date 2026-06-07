/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'DM Sans', 'sans-serif'],
            },
            colors: {
                primary: {
                    50:  '#EEF2FF',
                    100: '#E0E7FF',
                    500: '#6366F1',
                    600: '#4F46E5',
                    700: '#4338CA',
                    800: '#3730A3',
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}