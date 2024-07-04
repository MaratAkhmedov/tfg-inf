module.exports = {
    proxy: "http://localhost:8000",
    files: [
        "templates/**/*.html.twig",
        "public/assets/**/*.{css,js,html}",
        "public/build/**/*.{css,js}"
    ],
    port: 3000,
    open: false,
    notify: false
};