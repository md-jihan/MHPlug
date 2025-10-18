// Use a safe wrapper for jQuery to avoid conflicts with other libraries.
jQuery(document).ready(function($) {

    // Attach a click event listener to all elements with the class 'mh-accordion-header'.
    $('.mh-accordion-header').on('click', function() {

        // Find the next sibling element with the class 'mh-accordion-content'. This is the panel to show/hide.
        var content = $(this).next('.mh-accordion-content');

        // Close all other accordion panels that are currently open.
        // The .not(content) selector ensures we don't close the one we are about to open.
        $('.mh-accordion-content').not(content).slideUp();

        // Change the icon text of all other headers back to '+'.
        $('.mh-accordion-header').not(this).find('.mh-accordion-icon').text('+');

        // Toggle the visibility of the current content panel with a sliding animation.
        content.slideToggle();

        // Change the icon for the currently clicked header.
        var icon = $(this).find('.mh-accordion-icon');
        // A simple ternary operator to check the current text and switch it.
        icon.text(icon.text() === '+' ? '-' : '+');
    });

    // Optional: To make the dashboard more user-friendly, we can open the first accordion item by default.
    // We select the first item and programmatically trigger a click event on its header.
    $('.mh-accordion-item:first-child .mh-accordion-header').trigger('click');
});