/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/themes/drivespot/views/**/*.blade.php',
    './resources/views/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',

    './packages/Webkul/Shop/src/Resources/views/**/*.blade.php',
    './packages/Webkul/Shop/src/Resources/**/*.js',
    './packages/Webkul/Shop/src/Resources/**/*.vue',

    './resources/views/vendor/**/*.blade.php',
  ],

  theme: {
    extend: {},
  },

  plugins: [],
};