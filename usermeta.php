<?php

defined('ABSPATH') or die('No script kiddies please!');

function add_spokesperson_profile_fields( $user ) {
    ?>
    <h3><?php _e('Spokesperson Profile Information', 'spokesperson'); ?></h3>

    <table class="form-table">
        <tr>
            <th>
                <label for="position"><?php _e('Position', 'spokesperson'); ?>
                </label></th>
            <td>
                <input type="text" name="position" id="position" value="<?php echo esc_attr( get_the_author_meta( 'position', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Enter your position.', 'spokesperson'); ?></span>
            </td>
        </tr>

        <tr>
            <th>
                <label for="twitter"><?php _e('Twitter Username', 'spokesperson'); ?>
                </label></th>
            <td>
                <input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Enter your twitter username.', 'spokesperson'); ?></span>
            </td>
        </tr>

    </table>
<?php }

function save_spokespersons_profile_fields( $user_id ) {

    if ( !current_user_can( 'edit_user', $user_id ) )
        return FALSE;

    update_user_meta( $user_id, 'twitter', $_POST['twitter'] );
    update_user_meta( $user_id, 'position', $_POST['position'] );
}

add_action( 'show_user_profile', 'add_spokesperson_profile_fields' );
add_action( 'edit_user_profile', 'add_spokesperson_profile_fields' );

add_action( 'personal_options_update', 'save_spokespersons_profile_fields' );
add_action( 'edit_user_profile_update', 'save_spokespersons_profile_fields' );