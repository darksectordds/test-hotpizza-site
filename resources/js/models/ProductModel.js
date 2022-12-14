const ProductObject = {};

/*----------------------------------------------------
 | Properties
 |--------------------------------------------
 */

ProductObject.id = function(product) {
    return product.id;
};
ProductObject.name = function(product) {
    return product.name;
};
ProductObject.description = function(product) {
    return product.description;
};
ProductObject.price = function(product) {
    return product.price;
};

/*----------------------------------------------------
 | Attributes Extend
 |--------------------------------------------
 */

/*----------------------------------------------------
 | Relationship
 |--------------------------------------------
 */

ProductObject.hasRelationship = function(relationship) {
    return !!relationship && typeof relationship !== 'undefined';
};

ProductObject.relationshipPhoto = function(product) {
    return product.photo;
};
ProductObject.images = function(product) {
    const relationship = ProductObject.relationshipPhoto(product);
    return (ProductObject.hasRelationship(relationship)) ? relationship : [];
};
ProductObject.firstImage = function(product) {
    const images = ProductObject.images(product);
    return images[0];
};

const ProductModel = {
    install(Vue, options) {
        Vue.prototype.$models.$product = ProductObject;
    },
};

export default ProductModel;