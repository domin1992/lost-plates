module.exports = {
    purge: [
        "./resources/views/**/*.php",
        "./resources/js/**/*.{js,vue}",
        "./storage/framework/views/*.php",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        // "./node_modules/tw-elements/dist/js/**/*.js",
    ],
    darkMode: "class",
    theme: {
        screens: {
            sm: "640px",
            md: "768px",
            lg: "1024px",
            xl: "1280px",
            "2xl": "1536px",
        },
        container: {
            padding: "16px",
        },
        fontFamily: {
            sans: ["Montserrat", "sans-serif"],
            display: ["Bowlby One SC", "cursive"],
        },
        extend: {
            colors: {
                "mine-shaft": "#272727",
                cyan: "#03E5FA",
                "purple-heart": "#5E48CD",
                success: '#28A745',
                danger: '#DC3545',
                primary: "#007BFF",
            },
        },
    },
    variants: {},
    plugins: [
        require('@tailwindcss/typography'),
        // require('tw-elements/dist/plugin'),
        function ({ addComponents }) {
            addComponents({
                ".container": {
                    maxWidth: "100%",
                    "@screen sm": {
                        maxWidth: "540px",
                    },
                    "@screen md": {
                        maxWidth: "720px",
                    },
                    "@screen lg": {
                        maxWidth: "960px",
                    },
                    "@screen xl": {
                        maxWidth: "1176px",
                    },
                    "@screen 2xl": {
                        maxWidth: "1176px",
                    },
                },
            });
        },
    ]
};
