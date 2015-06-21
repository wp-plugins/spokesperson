<?php

/**
 * some styles in widget page for this widget
 */

add_action('admin_enqueue_scripts', function ($hook) {

    if ('widgets.php' != $hook) {
        return;
    }
    wp_enqueue_style('spokesperson-admin-stylesheet', plugin_dir_url(__FILE__) . 'assets/admin.css');
});


add_action('wp_enqueue_scripts', function(){
    wp_enqueue_style('spokespersons-widget-style', plugin_dir_url(__FILE__).'assets/spokesperson.css');
}, 100);
