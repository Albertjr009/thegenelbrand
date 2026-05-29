/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./index.html', './src/**/*.{js,jsx,ts,tsx}'],
  theme: {
    extend: {
      colors: {
        primary: '#ff6b6b',
        accent: '#ff8c42',
        dark: '#0f172a',
        ivory: '#f8f3ec',
        ink: '#1b1816',
        clay: '#9b4a32'
      }
    }
  },
  plugins: [],
}
