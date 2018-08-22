// http://aerendir.me/2018/04/06/managin-static-images-webpack-encore/
const imagesContext = require.context('../images', true, /\.(png|jpg|jpeg|gif|ico|svg|webp)$/);
imagesContext.keys().forEach(imagesContext);
