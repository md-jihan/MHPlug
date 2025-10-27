<?php

/**
 * Add an Icon Picker button and hidden input field to each menu item.
 *
 * @param string|int $item_id Menu item ID (can be string like 'pending-0').
 * @param object $item    Menu item data object.
 * @param int    $depth   Depth of menu item.
 * @param array  $args    An array of arguments.
 * @param int    $id      Nav menu ID (Added hook supports 5 args in WP 5.4+).
 */
function mh_plug_add_menu_item_icon_field( $item_id, $item, $depth, $args, $id = 0 ) { // Added $id for WP 5.4+ compatibility
    // Use the actual DB ID of the menu item for unique IDs/names
    $menu_item_db_id = $item->ID;
    $input_id = "edit-menu-item-icon-" . esc_attr( $menu_item_db_id );
    $input_name = "menu-item-icon[" . esc_attr( $menu_item_db_id ) . "]";
    $icon_class = get_post_meta( $menu_item_db_id, '_menu_item_icon', true );
    ?>
    <div class="field-icon description description-wide mh-menu-icon-picker-wrapper">
        <label for="<?php echo $input_id; ?>">
            <?php esc_html_e( 'Icon', 'mh-plug' ); ?>
        </label>
        <div class="mh-menu-icon-picker-container">
            <button type="button" class="button button-secondary mh-menu-icon-picker-button" data-target-input="#<?php echo $input_id; ?>">
                <span class="mh-menu-icon-preview">
                    <?php if ($icon_class): // Display icon if one is already saved ?>
                        <i class="<?php echo esc_attr($icon_class); ?>"></i>
                    <?php else: // Display default text ?>
                        <?php esc_html_e( 'Select Icon', 'mh-plug' ); ?>
                    <?php endif; ?>
                </span>
            </button>
            <?php // Keep the input, but hide it visually ?>
            <input type="text" id="<?php echo $input_id; ?>" class="widefat edit-menu-item-icon mh-menu-icon-hidden-input" name="<?php echo $input_name; ?>" value="<?php echo esc_attr( $icon_class ); ?>" />
             <?php // Add a button to clear the selected icon ?>
             <button type="button" class="button button-link mh-menu-icon-clear" data-target-input="#<?php echo $input_id; ?>" style="<?php echo empty($icon_class) ? 'display:none;' : ''; // Hide if no icon is set ?>">
                <?php esc_html_e( 'Clear', 'mh-plug' ); ?>
             </button>
        </div>
        <span class="description"><?php esc_html_e( 'Click the button to select an icon.', 'mh-plug' ); ?></span>
    </div>
    <?php
}