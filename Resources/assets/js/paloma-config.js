const config =  {

    account: {

        customRoutes: [],

        /**
         * If true, the "download invoice PDF" button will be displayed in the order details view.
         */
        orderInvoicePdfDownloadAvailable: false
    },

    catalog: {

        /**
         * If true, the cart is opened after an item was added (instead of the PalomaCartItemAdded component).
         */
        showCartAfterItemAdded: false,

        /**
         * Which image size to use to display product card images.
         */
        productCardImageSize: 'medium',
    },

    checkout: {

        /**
         * If true, users are allowed to make purchases as guest (not logged in).
         */
        allowGuests: true,
    },

    customer: {

        requireGender: false,

        requireDateOfBirth: false,

        /**
         * If true, an email address confirmation field is displayed during customer registration.
         */
        confirmEmailAddress: false,
    }

};

export default config;