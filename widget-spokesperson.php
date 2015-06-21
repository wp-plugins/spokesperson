<?php

/*
Plugin Name: SpokesPerson
Plugin URI:  http://wordpress.org/spokesperson
Description: Spokesperson is for showing bloggers info publically in widget area.
Version:     1.0
Author:      EvenFly
Author URI:  https://profiles.wordpress.org/evenfly
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: spokesperson
*/

defined('ABSPATH') or die('No script kiddies please!');

include 'usermeta.php';
include 'scripts.php';

global $wpdb;

$wp_users = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users WHERE ID IN(1,2,3,4,5) ORDER BY ID");


/**
 * Adds Spokesperson_Widget widget.
 */
class Spokesperson_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'spokesperson_widget', // Base ID
            __('Spokesperson', 'spokesperson'), // Name
            array('description' => __('A Spokesperson Widget', 'spokesperson'),) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        array_shift($instance);

        foreach ($instance as $person):
            if(!empty($person)) :
                $user_data = get_userdata($person); ?>
                    <li class="spokesperson">
                        <a href="<?php echo site_url(). '/author/' . $user_data->user_login; ?>">
                            <?php echo get_avatar($person, 44); ?>
                        </a>

                         <a href="<?php echo site_url(). '/author/' . $user_data->user_login; ?>">
                             <?php echo $user_data->display_name; ?>
                         </a> <br>

                        <?php if($user_data->position): ?>
                            <div><?php echo $user_data->position ?></div>
                        <?php endif; ?>

                        <a href="<?php echo 'mailto:'.$user_data->user_email ?>">
                            <?php echo $user_data->user_email; ?>
                        </a> <br>

                        <?php if($user_data->twitter) : ?>
                            <a href="<?php echo 'https://twitter.com/'.$user_data->twitter ?>" class="twitter fa fa-twitter">
                                <?php echo '@'.$user_data->twitter; ?>
                            </a>
                        <?php endif; ?>

                     </li>
        <?php
            endif;
        endforeach;

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        global $wp_users;
        $title = !empty($instance['title']) ? $instance['title'] : __('Spokesperson', 'spokesperson');

        ?>
        <div class="spokesperson">
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
                <input class="widefat title-field" id="<?php echo $this->get_field_id('title'); ?>"
                       name="<?php echo $this->get_field_name('title'); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>">

                <h4>Choose whom you want to show</h4>
                <?php
                foreach ($wp_users as $wp_user): ?>

                    <label for="<?php echo $this->get_field_id('user') . '-' . $wp_user->ID; ?>" class="field-label">
                        <?php echo get_avatar($wp_user->ID, 36) . $wp_user->display_name ?>
                    </label>

                    <input <?php echo (!empty($instance[$wp_user->ID])) ? 'checked' : '' ?>
                        id="<?php echo $this->get_field_id('user') . '-' . $wp_user->ID; ?>" class="widefat"
                        name="<?php echo $this->get_field_name($wp_user->ID); ?>" type="checkbox"
                        value="<?php echo $wp_user->ID ?>"><br><br>

                <?php
                endforeach;
                ?>
            </p>

        </div>
    <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        global $wp_users;
        $instance = array();
        $instance = $old_instance;
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

        foreach ($wp_users as $wp_user):
            $instance[$wp_user->ID] = (!empty($new_instance[$wp_user->ID])) ? strip_tags($new_instance[$wp_user->ID]) : '';
        endforeach;

        return $instance;
    }

} // class Spokesperson_Widget


// register Spokesperson_Widget widget
add_action('widgets_init', function () {
    register_widget('Spokesperson_Widget');
});