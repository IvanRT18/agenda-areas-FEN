/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#1B3257',
        secondary: {
          100: '#E2E2D5',
          200: '#888883',
          300: '#4A5568',
          400: '#2D3748',
          500: '#1A202C',
        },
      },
    },
  },
  plugins: [],
}

