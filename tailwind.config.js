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
    },
    extend: {},
  },
  plugins: [
    require('flowbite/plugin') // add the flowbite plugin
  ],
}
