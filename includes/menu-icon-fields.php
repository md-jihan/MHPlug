<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
/**
 * ===================================================================
 * Add Icon Field to WordPress Menu Items
 * ===================================================================
 */

// Action hook to add custom fields to menu item editor
add_action( 'wp_nav_menu_item_custom_fields', 'mh_plug_add_menu_item_icon_field', 10, 5 ); // Use 5 arguments for WP 5.4+ compatibility

/**
 * Add an Icon Picker button and hidden input field to each menu item.
 *
 * @param string|int $item_id Menu item ID (can be string like 'pending-0' for new items).
 * @param object $item    Menu item data object.
 * @param int    $depth   Depth of menu item.
 * @param array  $args    An array of arguments.
 * @param int    $id      Nav menu ID (Available in WP 5.4+).
 */
function mh_plug_add_menu_item_icon_field( $item_id, $item, $depth, $args, $id = 0 ) {
    // Use the actual DB ID ($item->ID) for saving/retrieving meta, as $item_id might be temporary for new items.
    $menu_item_db_id = $item->ID;
    
    // --- Icon Picker Code ---
    $icon_input_id = "edit-menu-item-icon-" . esc_attr( $menu_item_db_id );
    $icon_input_name = "menu-item-icon[" . esc_attr( $menu_item_db_id ) . "]";
    $icon_class = get_post_meta( $menu_item_db_id, '_menu_item_icon', true );


    // --- Hide Label Switch Code ---
    $hide_label_input_id = "edit-menu-item-hide-label-" . esc_attr( $menu_item_db_id );
    $hide_label_input_name = "menu-item-hide-label[" . esc_attr( $menu_item_db_id ) . "]";
    $hide_label_value = get_post_meta( $menu_item_db_id, '_menu_item_hide_label', true );
    $hide_label_checked = ! empty( $hide_label_value ) ? 'checked="checked"' : ''; // Check if meta value exists
    ?>
    <?php // <-- DIAGNOSTIC COMMENT ?>
    <div class="field-icon description description-wide mh-menu-icon-picker-wrapper">
        <label for="<?php echo $icon_input_id; ?>">
            <?php esc_html_e( 'Icon', 'mh-plug' ); ?>
        </label>
        <div class="mh-menu-icon-picker-container">
            <button type="button" class="button button-secondary mh-menu-icon-picker-button" 
            data-target-input="#<?php echo $icon_input_id; ?>">
                <span class="mh-menu-icon-preview">
                    <?php if ($icon_class): // Display icon if one is already saved ?>
                        <i class="<?php echo esc_attr($icon_class); ?>"></i>
                    <?php else: // Display default text ?>
                        <?php esc_html_e( 'Select Icon', 'mh-plug' ); ?>
                    <?php endif; ?>
                </span>
            </button>
            <?php // Keep the input, but hide it visually ?>
            <input type="text" id="<?php echo $icon_input_id; ?>" class="widefat edit-menu-item-icon mh-menu-icon-hidden-input" name="<?php echo $icon_input_name; ?>" value="<?php echo esc_attr( $icon_class ); ?>" />
             <?php // Add a button to clear the selected icon ?>
             <button type="button" class="button button-link mh-menu-icon-clear" data-target-input="#<?php echo $icon_input_id; ?>" style="<?php echo empty($icon_class) ? 'display:none;' : ''; // Hide if no icon is set ?>">
                <?php esc_html_e( 'Clear', 'mh-plug' ); ?>
             </button>
        </div>
        <span class="description"><?php esc_html_e( 'Click the button to select an icon.', 'mh-plug' ); ?></span>
    </div>

    <?php // --- ADD THIS NEW FIELD --- ?>
    <div class="field-hide-label description description-wide">
         <label for="<?php echo $hide_label_input_id; ?>">
            <input type="checkbox" id="<?php echo $hide_label_input_id; ?>" class="edit-menu-item-hide-label" name="<?php echo $hide_label_input_name; ?>" value="1" <?php echo $hide_label_checked; ?> />
            <?php esc_html_e( 'Hide Navigation Label', 'mh-plug' ); ?>
            <span class="description"><?php esc_html_e( '(Only show icon)', 'mh-plug' ); ?></span>
        </label>
    </div>
    <?php // --- END OF NEW FIELD --- ?>
    <?php
}

// Action hook to save custom menu item fields
add_action( 'wp_update_nav_menu_item', 'mh_plug_save_menu_item_icon_field', 10, 3 );

/**
 * Save the Icon Class custom field for a menu item.
 *
 * @param int   $menu_id         ID of the menu being updated.
 * @param int   $menu_item_db_id ID of the menu item being updated.
 * @param array $args            An array of arguments used to update a menu item.
 */
function mh_plug_save_menu_item_icon_field( $menu_id, $menu_item_db_id, $args ) {
    // Check if our icon class field was submitted using the correct input name structure.
    if ( isset( $_POST['menu-item-icon'][ $menu_item_db_id ] ) ) {
        // Sanitize the input (remove potentially harmful code/tags and extra spaces).
        $sanitized_icon_class = sanitize_text_field( trim( $_POST['menu-item-icon'][ $menu_item_db_id ] ) );

        // Save the sanitized value as post meta data for this menu item.
        // Use '_menu_item_icon' as the meta key.
        update_post_meta( $menu_item_db_id, '_menu_item_icon', $sanitized_icon_class );
    } else {
        // If the field was empty or not submitted, delete any existing meta data.
        delete_post_meta( $menu_item_db_id, '_menu_item_icon' );
    }
    // --- ADD THIS BLOCK TO SAVE THE SWITCH ---
    // Save Hide Label Switch
    if ( isset( $_POST['menu-item-hide-label'][ $menu_item_db_id ] ) && $_POST['menu-item-hide-label'][ $menu_item_db_id ] === '1' ) {
        // If checkbox is checked, save '1' (or true, or any non-empty value)
        update_post_meta( $menu_item_db_id, '_menu_item_hide_label', '1' );
    } else {
        // If checkbox is not checked (not present in $_POST), delete the meta key
        delete_post_meta( $menu_item_db_id, '_menu_item_hide_label' );
    }
    // --- END OF ADDED BLOCK ---
}

// Filter hook to modify the menu item title output on the frontend
add_filter( 'nav_menu_item_title', 'mh_plug_display_menu_item_icon', 10, 4 );

/**
 * Prepend the icon HTML to the menu item title if an icon class is saved.
 *
 * @param string $title   The menu item's title.
 * @param object $item    The current menu item object (WP_Post).
 * @param object $args    An object of wp_nav_menu() arguments.
 * @param int    $depth   Depth of menu item. Used for padding.
 * @return string Modified menu item title.
 */
function mh_plug_display_menu_item_icon( $title, $item, $args, $depth ) {
    // Check if $item is a valid object and has an ID property
    if ( ! is_object( $item ) || ! isset( $item->ID ) ) {
        return $title;
    }

    // Get the saved icon class for this menu item using its database ID.
    $icon_class = get_post_meta( $item->ID, '_menu_item_icon', true );
    // --- GET THE HIDE LABEL VALUE ---
    $hide_label = get_post_meta( $item->ID, '_menu_item_hide_label', true );
    $icon_html = '';
    if ( ! empty( $icon_class ) ) {
        $sanitized_icon_class = esc_attr( $icon_class );
        // Add a class for potential styling of icon-only items
        $icon_only_class = ! empty( $hide_label ) ? ' mh-menu-icon-only' : '';
        $icon_html = '<i class="' . $sanitized_icon_class . $icon_only_class . '" aria-hidden="true"></i> ';
    }
// --- MODIFY THE RETURN LOGIC ---
    if ( ! empty( $hide_label ) ) {
        // If hide_label is set, return ONLY the icon HTML (might be empty if no icon was set)
        return $icon_html;
    } else {
        // Otherwise, return the icon (if any) prepended to the original title
        return $icon_html . $title;
    }
    // --- END OF MODIFIED LOGIC ---
}

?>