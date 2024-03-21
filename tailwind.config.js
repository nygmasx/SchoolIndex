/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    fontFamily: {
      'epilogue': ["Epilogue", "sans-serif"],
      'noto': ["Noto Sans", "sans-serif"],
    },
    extend: {
      colors: {
        customBlue: '#1B3168',
        customGrey: '#666666' ,
        customGreen: '#52B788',
        darkGreen: '#40916c',
        lightGrey: '#DFE3E8',
        
      },
    },
  },
  plugins: [],
}
