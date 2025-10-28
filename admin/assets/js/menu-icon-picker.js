jQuery(document).ready(function($) {

    var currentTargetInput = null; // Store the input field selector that triggered the modal

    // --- Define Your Icons Here ---
    // You should ideally generate this list dynamically from your CSS/JSON
    // For now, list the icons from your style.css
    var mhIconList = [
        'mhi-menu',
        'mhi-post',
        'mhi-post-slider',
        'mhi-image-carousel',
        'mhi-text',
        'mhi-site-title',
        'mhi-site-logo'
        // Add all other mhi- icons here
    ];
// --- Define Font Awesome Icons (Add more as needed) ---
    // Using Font Awesome 5 Free classes (Solid, Regular, Brands)
    var faIconList = [
        // Solid (fas)
        'fas fa-home', 'fas fa-star', 'fas fa-user', 'fas fa-cog', 'fas fa-envelope',
        'fas fa-heart', 'fas fa-search', 'fas fa-bars', 'fas fa-times', 'fas fa-check',
        'fas fa-arrow-left', 'fas fa-arrow-right', 'fas fa-arrow-up', 'fas fa-arrow-down',
        'fas fa-chevron-left', 'fas fa-chevron-right', 'fas fa-chevron-up', 'fas fa-chevron-down',
        'fas fa-plus', 'fas fa-minus', 'fas fa-info-circle', 'fas fa-question-circle', 'fas fa-exclamation-triangle',
        'fas fa-phone', 'fas fa-map-marker-alt', 'fas fa-calendar-alt', 'fas fa-camera', 'fas fa-image',
        'fas fa-file-alt', 'fas fa-folder', 'fas fa-shopping-cart', 'fas fa-credit-card', 'fas fa-download',
        'fas fa-upload', 'fas fa-link', 'fas fa-paper-plane',

        // Regular (far) - Note: Fewer icons available in free version
        'far fa-star', 'far fa-heart', 'far fa-comment', 'far fa-comments', 'far fa-envelope',
        'far fa-user', 'far fa-calendar-alt', 'far fa-image', 'far fa-file-alt', 'far fa-folder',
        'far fa-credit-card',

        // Brands (fab)
        'fab fa-wordpress', 'fab fa-facebook-f', 'fab fa-twitter', 'fab fa-linkedin-in',
        'fab fa-instagram', 'fab fa-youtube', 'fab fa-github', 'fab fa-pinterest',
        'fab fa-whatsapp', 'fab fa-slack', 'fab fa-skype', 'fab fa-vimeo-v'
    ];
    // --- Modal HTML (Created Dynamically) ---
    function createIconPickerModal() {
        if ($('#mh-icon-picker-modal').length) {
            return; // Don't create if it already exists
        }

        var modalHTML = `
            <div class="mh-icon-picker-modal-backdrop"></div>
            <div id="mh-icon-picker-modal" class="mh-icon-picker-modal" style="display: flex;">
                <div class="mh-icon-picker-modal-header">
                    <h2>${'Select Icon'}</h2>
                    <button type="button" class="mh-icon-picker-modal-close">&times;</button>
                </div>
                <div class="mh-icon-picker-modal-body">
                    <h3>${'MH Icons'}</h3>
                    <div class="icon-grid mh-icon-grid">
                        ${generateIconGrid(mhIconList)}
                    </div>
                    <?php /* You could add other sections here e.g., for Font Awesome */ ?>
                    <?php // Section for Font Awesome Icons ?>
                    <h3 class="mh-icon-picker-section-title" style="margin-top: 20px;">${'Font Awesome'}</h3>
                    <div class="icon-grid fa-icon-grid">
                         ${generateIconGrid(faIconList)}
                    </div>
                </div>
                <div class="mh-icon-picker-modal-footer">
                    <button type="button" class="button button-secondary mh-icon-picker-modal-close">${'Cancel'}</button>
                </div>
            </div>
        `;
        $('body').append(modalHTML);

        // Add event listeners for the new modal
        $('#mh-icon-picker-modal .mh-icon-picker-modal-close').on('click', closeIconPickerModal);
        $('.mh-icon-picker-modal-backdrop').on('click', closeIconPickerModal);


        $('#mh-icon-picker-modal .mh-icon-grid').on('click', '.icon-item', function() {
            var selectedIconClass = $(this).data('icon-class');
            if (currentTargetInput) {
                updateIconSelection(currentTargetInput, selectedIconClass);
            }
            closeIconPickerModal();
        });

// Search Functionality
        $('#mh-icon-picker-search').on('keyup', function() {
            var searchTerm = $(this).val().toLowerCase().trim();
            $('#mh-icon-picker-modal .icon-item').each(function() {
                var iconClass = $(this).data('icon-class').toLowerCase();
                // Simple search: check if class name contains search term
                if (iconClass.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
             // Show/hide section titles if all icons within are hidden
            $('#mh-icon-picker-modal .mh-icon-picker-modal-body').each(function(){
                $(this).find('h3, .icon-grid').show(); // Show all first
                $(this).find('.icon-grid').each(function(){
                     if ($(this).children(':visible').length === 0) {
                          $(this).prev('h3').hide(); // Hide title if grid empty
                          $(this).hide(); // Hide grid if empty
                     }
                });
            });
        });
        

    }
    

    function generateIconGrid(iconList) {
        var gridHTML = '';
        iconList.forEach(function(iconClass) {
            var titleName = iconClass.replace(/^(fas|far|fab|mhi)\sfa-/,'').replace(/^mhi-/,'').replace(/-/g, ' ');
            gridHTML += `<span class="icon-item" data-icon-class="${iconClass}" title="${titleName}">
                           <i class="${iconClass}"></i>
                         </span>`;
        });
        return gridHTML;
    }

    function openIconPickerModal(inputSelector) {
        currentTargetInput = inputSelector; // Store which input we're editing
        createIconPickerModal(); // Ensure modal exists
        $('#mh-icon-picker-search').val('').trigger('keyup'); // Clear search on open
        $('#mh-icon-picker-modal, .mh-icon-picker-modal-backdrop').fadeIn(200);
        $('#mh-icon-picker-search').trigger('focus'); // Focus search input
    }

    function closeIconPickerModal() {
        $('#mh-icon-picker-modal, .mh-icon-picker-modal-backdrop').fadeOut(200);
        currentTargetInput = null; // Reset target
    }

    // Function to update the button preview and hidden input
    function updateIconSelection(inputSelector, iconClass) {
        var $input = $(inputSelector);
        if (!$input.length) return; // Exit if input not found

        var $container = $input.closest('.mh-menu-icon-picker-container'); // Find container relative to input
        var $button = $container.find('.mh-menu-icon-picker-button');
        var $preview = $button.find('.mh-menu-icon-preview');
        var $clearButton = $container.find('.mh-menu-icon-clear');

        $input.val(iconClass).trigger('change'); // Update input and trigger WP change detection

        if (iconClass) {
            $preview.html('<i class="' + iconClass + '"></i>'); // Show icon
            $clearButton.show();
        } else {
            $preview.text('Select Icon'); // Show default text
            $clearButton.hide();
        }
    }
// --- Event Handlers ---

    // Click handler for the main "Select Icon" button
    // Use event delegation on a static parent (#menu-management)
    $(document).on('click', '#menu-management .mh-menu-icon-picker-button', function() {
        var targetInputSelector = $(this).data('target-input');
        if (targetInputSelector) {
            openIconPickerModal(targetInputSelector);
        } else {
            console.error('MH Icon Picker: Target input selector not found.');
        }
    });

    // Click handler for the "Clear" button
    // Use event delegation
    $(document).on('click', '#menu-management .mh-menu-icon-clear', function() {
        var targetInputSelector = $(this).data('target-input');
         if (targetInputSelector) {
            updateIconSelection(targetInputSelector, ''); // Clear the selection
        } else {
             console.error('MH Icon Picker: Target input selector not found for clear button.');
        }
    });

    // Make sure the rest of your functions (createIconPickerModal, openIconPickerModal, etc.) are inside the main jQuery(document).ready() block.

});