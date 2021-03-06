import Vue from "vue";
import PalomaCart from "./cart/PalomaCart";

const cartElem = document.getElementById('paloma-cart');
if (cartElem) {

    const cart = new Vue({
        components: {
            PalomaCart
        }
    });

    cart.$mount(cartElem);
}