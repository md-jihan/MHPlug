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
    }

    function generateIconGrid(iconList) {
        var gridHTML = '';
        iconList.forEach(function(iconClass) {
            gridHTML += `<span class="icon-item" data-icon-class="${iconClass}" title="${iconClass}">
                           <i class="${iconClass}"></i>
                         </span>`;
        });
        return gridHTML;
    }

    function openIconPickerModal(inputSelector) {
        currentTargetInput = inputSelector; // Store which input we're editing
        createIconPickerModal(); // Ensure modal exists
        $('#mh-icon-picker-modal, .mh-icon-picker-modal-backdrop').fadeIn(200);
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
        console.log('MH Icon Picker Button Clicked!'); // Debugging line
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
        console.log('MH Icon Clear Button Clicked!'); // Debugging line
        var targetInputSelector = $(this).data('target-input');
         if (targetInputSelector) {
            updateIconSelection(targetInputSelector, ''); // Clear the selection
        } else {
             console.error('MH Icon Picker: Target input selector not found for clear button.');
        }
    });

    // Make sure the rest of your functions (createIconPickerModal, openIconPickerModal, etc.) are inside the main jQuery(document).ready() block.

});