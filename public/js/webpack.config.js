const path = require('path');

module.exports = {
    mode: "development", // "production" | "development" | "none",
    devtool: "source-map",
    watch: true,
    module: {
        rules: [
          { test: /\.js$/, exclude: /node_modules/, loader: "babel-loader" }
        ]
    }
}
