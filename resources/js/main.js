(function ($) {
    'use strict';

    let wcNotificationWrapper = $('.product_cart_notification_wrapper');
    $(document).on('added_to_cart', function (event, fragments, cart_hash, button) {
        $(document).trigger('hide_notification');
        // collect data from the object and fragments response.
        let template = wcNotificationObject.template,
            productName = fragments.product_notification_widget.product_name,
            productLink = fragments.product_notification_widget.product_url,
            productImage = fragments.product_notification_widget.product_image_url,
            productPrice = fragments.product_notification_widget.product_price_html,
            cardTitle = wcNotificationObject.i18n.title,
            cartButtonLabel = wcNotificationObject.i18n.cart_button_label,
            cartUrl = wcNotificationObject.cart_url,
            priceLabel = wcNotificationObject.i18n.price_label;

        // replace the placeholders with actual data
        template = template.replace(/{{product_name}}/g, productName);
        template = template.replace(/{{product_link}}/g, productLink);
        template = template.replace(/{{product_image_url}}/g, productImage);
        template = template.replace(/{{product_price}}/g, productPrice);
        template = template.replace(/{{title}}/g, cardTitle);
        template = template.replace(/{{cart_button}}/g, cartButtonLabel);
        template = template.replace(/{{price}}/g, priceLabel);
        template = template.replace(/{{cart_url}}/g, cartUrl);

        // Open the notification
        setTimeout(function () {
            $(document).trigger('open_notification', [template]);
        }, 700)

    });

    // Open the notification
    $(document).on('open_notification', function (e, html) {
        wcNotificationWrapper.html(html);
        // Slide in the notification div
        wcNotificationWrapper.addClass('open');
        // Pause countdown when mouse enters the div
        wcNotificationWrapper.on('mouseenter', function () {
            clearTimeout(timer);
        });
        // Resume countdown when mouse leaves the div
        wcNotificationWrapper.on('mouseleave', function () {
            startSlideOut();
        });
        let timer;
        // Slide out the notification div after number of seconds
        function startSlideOut() {
            timer = setTimeout(function () {
                $(document).trigger('hide_notification');
            }, wcNotificationObject.options.close_after * 1000);
        }
        // Start the slide out process
        startSlideOut();
    });

    // Hide the notification
    $(document).on('hide_notification', function (e) {
        wcNotificationWrapper.removeClass('open');
    });

    // Close the notification
    $(document).on('click','.product_cart_notification header .close' ,function (e) {
        $(document).trigger('hide_notification');
    });

})(jQuery)