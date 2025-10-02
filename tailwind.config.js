/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  safelist: [
    // Color theme classes for form header
    "bg-blue-50",
    "bg-green-50",
    "bg-purple-50",
    "bg-red-50",
    "bg-yellow-50",
    "bg-indigo-50",
    // Ring classes for color picker buttons
    "ring-blue-500",
    "ring-green-500",
    "ring-purple-500",
    "ring-red-500",
    "ring-yellow-500",
    "ring-indigo-500",
    // Background classes for color picker buttons
    "bg-blue-500",
    "bg-green-500",
    "bg-purple-500",
    "bg-red-500",
    "bg-yellow-500",
    "bg-indigo-500",
    // Hover ring classes
    "hover:ring-blue-500",
    "hover:ring-green-500",
    "hover:ring-purple-500",
    "hover:ring-red-500",
    "hover:ring-yellow-500",
    "hover:ring-indigo-500",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
