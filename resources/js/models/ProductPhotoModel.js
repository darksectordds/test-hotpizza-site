const ProductPhotoObject = {};

/*----------------------------------------------------
 | Properties
 |--------------------------------------------
 */

ProductPhotoObject.id = function(product_photo) {
    return product_photo.id;
};
ProductPhotoObject.url = function(product_photo) {
    return product_photo.url;
};
ProductPhotoObject.productId = function(product_photo) {
    return product_photo.product_id;
};

/*----------------------------------------------------
 | Attributes Extend
 |--------------------------------------------
 */

/*----------------------------------------------------
 | Relationship
 |--------------------------------------------
 */

const ProductPhotoModel = {
    install(Vue, options) {
        Vue.prototype.$models.$productPhoto = ProductPhotoObject;
    },
};

export default ProductPhotoModel;