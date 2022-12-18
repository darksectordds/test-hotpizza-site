const CartProduct = {};

/*----------------------------------------------------
 | Properties
 |--------------------------------------------
 */

CartProduct.productId = function(cart) {
    return cart.product_id;
};
CartProduct.name = function(cart) {
    return cart.name;
};
CartProduct.price = function(cart) {
    return Number(cart.price);
};
CartProduct.count = function(cart) {
    return Number(cart.count);
};
CartProduct.date = function(cart) {
    return cart.date;
};

/*----------------------------------------------------
 | Attributes Extend
 |--------------------------------------------
 */

/*----------------------------------------------------
 | Relationship
 |--------------------------------------------
 */

const CartModel = {
    install(Vue, options) {
        Vue.prototype.$models.$cart = CartProduct;
    },
};

export default CartModel;