
define(['uiComponent', 'ko'], (Component, ko) => {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Magento_Catalog/input-counter',
            maxQty: 1,
            qty: 1,
        },
        initialize: function () {
            this._super();
            this.qty = ko.observable(1);
        },
        /**
         * Validates if the quantity is a valid number.
         * @returns {void}
         */
        validateNumber: function () {
            let value = this.qty();
            if (typeof value == 'string') this.qty(+value);
            if (+value > this.maxQty) this.qty(this.maxQty);
            if (+value < 1) this.qty(1);
        },

        /**
         * Decreases the quantity by one.
         * @returns {void}
         */
        decreaseQty: function () {
            let newQty = this.qty() - 1;
            this.qty(newQty <= 0 ? this.qty() : newQty);
            this.validateNumber()
        },

        /**
         * Increases the quantity by one.
         * @returns {void}
         */
        increaseQty: function () {
            this.qty(this.qty() + 1);
            this.validateNumber()
        },
    });
});
