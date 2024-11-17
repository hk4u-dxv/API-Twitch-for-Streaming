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
          'purple-light': '#a970ff',
          'gray-dark': '#0e0e10',
          'gray-medium': '#18181b',
          'gray-light': '#1f1f23',
        }
      },
      keyframes: {
        gradient: {
          '0%, 100%': { backgroundPosition: '0% 50%' },
          '50%': { backgroundPosition: '100% 50%' },
        },
        floatUp: {
          '0%': { transform: 'translateY(20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        pulse: {
          '0%, 100%': { transform: 'scale(1)' },
          '50%': { transform: 'scale(1.05)' },
        },
        shimmer: {
          '0%': { backgroundPosition: '-1000px 0' },
          '100%': { backgroundPosition: '1000px 0' },
        }
      },
      animation: {
        gradient: 'gradient 15s ease infinite',
        'float-up': 'floatUp 0.8s ease-out forwards',
        'pulse-slow': 'pulse 3s infinite',
        shimmer: 'shimmer 2s infinite linear'
      }
    },
  },
  plugins: [],
}