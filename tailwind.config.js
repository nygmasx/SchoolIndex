/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js" // set up the path to the flowbite package
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
        borderGrey: '#F6F6F6',
        darkGrey: '#DADADA',
        fontGrey:'#757575',
<<<<<<< HEAD
        
        
=======

>>>>>>> e4b28c7 (partie admin fini)
      },
    },
  },
  plugins: [
    require('flowbite/plugin') // add the flowbite plugin
  ],
}
