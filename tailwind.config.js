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
          '0%': { backgroundPosition: '0% 50%' },
          '100%': { backgroundPosition: '100% 50%' }
        },
        floatUp: {
          '0%': { 
            transform: 'translateY(20px)', 
            opacity: '0' 
          },
          '100%': { 
            transform: 'translateY(0)', 
            opacity: '1' 
          }
        },
        slideBg: {
          '0%': { backgroundPosition: '0 0' },
          '100%': { backgroundPosition: '100% 100%' }
        }
      },
      animation: {
        gradient: 'gradient 3s linear infinite',
        'float-up': 'floatUp 0.8s ease-out forwards',
        'slide-bg': 'slideBg 20s linear infinite',
        'pulse-slow': 'pulse 2s ease-in-out infinite'
      }
    },
  },
  plugins: [],
}