/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",
    "./assets/**/*.{js,css}"
  ],
  theme: {
    container: {
      center: true,
      padding: '1rem',
    },
    extend: {
      colors: {
        twitch: {
          purple: '#9146FF',
          'purple-dark': '#7a2eff',
        }
      }
    },
  },
  plugins: [],
}