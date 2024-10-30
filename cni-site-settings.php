<?php
/*
Plugin Name: CNI Site Settings
Plugin URI:  https://wordpress.org/plugins/cni-site-settings/
Text Domain: cni-site-settings
Domain Path: /languages
Description: It makes easier to manege your site with this plugin.
Version:     0.0.3
Author:      CYBER NETWORKS Inc.
Author URI:  http://seo.cni.jp/
License:     GPLv2
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class cni_site_settings {
    private $options;
    private $success;
    private $errors;
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    // Add menu
    public function add_plugin_page() {
        add_options_page( __( "CNI Site Settings", "cni-site-settings" ), __( "CNI Site Settings", "cni-site-settings" ), 'manage_options', 'cni_site_settings', array( $this, 'create_admin_page' ) );
    }

    // Setting page initialization
    public function page_init() {
        if( !function_exists( 'wp_get_current_user' ) ) {
            include( ABSPATH . "wp-includes/pluggable.php" ); 
        }
        if( current_user_can( 'manage_options' ) ) {
            // Get the setting of the DB
            $this->options = get_option( 'cni_site_settings' );
            $this->errors = array();
            $this->success = "";
            // If the data is transmitted
            if( isset( $_POST['cni_site_settings'] ) ) {
                $input = $_POST['cni_site_settings'];
                $this->options['major_core_updates'] = trim($input['major_core_updates']);
                $this->options['update_theme'] = trim( $input['update_theme'] );
                $this->options['update_plugin'] = trim( $input['update_plugin'] );
                $this->options['autosave'] = trim( $input['autosave'] );
                $this->options['visual_editor'] = trim( $input['visual_editor'] );
                // Error If there is no value
                if( !isset( $this->options['major_core_updates'] ) || $this->options['major_core_updates'] === '' ) {
                    $this->errors['major_core_updates'] = __( "Please select the item of \"Major update\".", "cni-site-settings" );
                }
                if( !isset( $this->options['update_theme'] ) || $this->options['update_theme'] === '' ) {
                    $this->errors['update_theme'] = __( "Please select the item of \"Update Themes\".", "cni-site-settings" );
                }
                if( !isset( $this->options['update_plugin'] ) || $this->options['update_plugin'] === '' ) {
                    $this->errors['update_plugin'] = __( "Please select the item of \"Update Plugins\".", "cni-site-settings" );
                }
                if( !isset( $this->options['autosave'] ) || $this->options['autosave'] === '' ) {
                    $this->errors['autosave'] = __( "Please select the item of \"Autosave\".", "cni-site-settings" );
                }
                if( !isset( $this->options['visual_editor'] ) || $this->options['visual_editor'] === '' ) {
                    $this->errors['visual_editor'] = __( "Please select the item of \"Use Visual editor\".", "cni-site-settings" );
                }
    
                // Save the value if there is no error
                if( !$this->errors && check_admin_referer( 'cni_site_settings', 'nonce_cni_site_settings' ) ) {
                    update_option( 'cni_site_settings', $this->options );
                    $this->success = __( "Your settings have been saved.", "cni-site-settings" );
                }
            }
        }
    }

    // Setting page's HTML
    public function create_admin_page() {
        $major_core_updates = isset( $this->options['major_core_updates'] ) ? $this->options['major_core_updates'] : "manual";
        $update_theme = isset( $this->options['update_theme'] ) ? $this->options['update_theme'] : "manual";
        $update_plugin = isset( $this->options['update_plugin'] ) ? $this->options['update_plugin'] : "manual";
        $autosave = isset( $this->options['autosave'] ) ? $this->options['autosave'] : "on";
        $visual_editor = isset( $this->options['visual_editor'] ) ? $this->options['visual_editor'] : "on";

        echo "<h1>" . __( "CNI Site Settings", "cni-site-settings" ) . "</h1>\n";
        echo "<div style=\"font-size:1.1em;margin-bottom:1em;\">\n" . __( "Manage your site settings easily.", "cni-site-settings" ) ."\n</div>";

        // Display success message
        if( $this->success ) {
            echo "<div class=\"updated\"><p><strong>";
            esc_html_e($this->success);
            echo "</strong></p></div>\n";
        }
        // Display error message
        if( $this->errors ) {
            foreach ($this->errors as $err) {
                echo "<div class=\"error\"><p><strong>";
                esc_html_e($err);
                echo "</strong></p></div>\n";
            }
        }

?>
<style>
input[type="radio"] {
    margin-left:1em;
}
</style>
<form method="post">
<?php wp_nonce_field( 'cni_site_settings', 'nonce_cni_site_settings' ); ?>
<div style="padding:1em 0 1em 2em;">
<h2><?php _e( "Major update", "cni-site-settings" ); ?></h2>
<div>
<?php _e( "In WordPress, Minor updates (Example:\"4.4.1\" to \"4.4.2\") is done automatically.", "cni-site-settings" ); ?><br />
<?php _e( "If you want to be done Major updates (Example:\"4.4.x\" to \"4.5\") too, please select \"Automatically\".", "cni-site-settings" ); ?><br />
<?php _e( "Default is \"Manually\".", "cni-site-settings" ); ?>
</div>
<div style="padding:1em 0;">
<input type="radio" name="cni_site_settings[major_core_updates]" value="auto"<?php if( $major_core_updates == "auto" ) echo " checked=\"checked\"" ?> /><?php _e( "Automatically", "cni-site-settings" ); ?>
<input type="radio" name="cni_site_settings[major_core_updates]" value="manual"<?php if( $major_core_updates != "auto" ) echo " checked=\"checked\"" ?> /><?php _e( "Manually", "cni-site-settings" ); ?>
</div>
<h2><?php _e( "Update Themes", "cni-site-settings" ); ?></h2>
<div>
<?php _e( "If you want to be done Themes' updates automatically, please select \"Automatically\".", "cni-site-settings" ); ?><br />
<?php _e( "Default is \"Manually\".", "cni-site-settings", "cni-site-settings" ); ?>
</div>
<div style="padding:1em 0;">
<input type="radio" name="cni_site_settings[update_theme]" value="auto"<?php if( $update_theme == "auto" ) echo " checked=\"checked\"" ?> /><?php _e( "Automatically", "cni-site-settings" ); ?>
<input type="radio" name="cni_site_settings[update_theme]" value="manual"<?php if( $update_theme != "auto" ) echo " checked=\"checked\"" ?> /><?php _e( "Manually", "cni-site-settings" ); ?>
</div>
<h2><?php _e( "Update Plugins", "cni-site-settings" ); ?></h2>
<div>
<?php _e( "If you want to be done Plugins' updates automatically, please select \"Automatically\".", "cni-site-settings" ); ?><br />
<?php _e( "Default is \"Manually\".", "cni-site-settings" ); ?>
</div>
<div style="padding:1em 0;">
<input type="radio" name="cni_site_settings[update_plugin]" value="auto"<?php if( $update_plugin == "auto" ) echo " checked=\"checked\"" ?> /><?php _e( "Automatically", "cni-site-settings" ); ?>
<input type="radio" name="cni_site_settings[update_plugin]" value="manual"<?php if( $update_plugin != "auto" ) echo " checked=\"checked\"" ?> /><?php _e( "Manually", "cni-site-settings" ); ?>
</div>
<h2><?php _e( "Autosave", "cni-site-settings" ); ?></h2>
<div>
<?php _e( "If you want to cancel the editor's autosave, please select \"Off\".", "cni-site-settings" ); ?><br />
<?php _e( "Default is \"On\".", "cni-site-settings" ); ?>
</div>
<div style="padding:1em 0;">
<input type="radio" name="cni_site_settings[autosave]" value="on"<?php if( $autosave != "off" ) echo " checked=\"checked\"" ?> /><?php _e( "On", "cni-site-settings" ); ?>
<input type="radio" name="cni_site_settings[autosave]" value="off"<?php if( $autosave == "off" ) echo " checked=\"checked\"" ?> /><?php _e( "Off", "cni-site-settings" ); ?>
</div>
<h2><?php _e( "Use the visual editor", "cni-site-settings" ); ?></h2>
<div>
<?php _e( "If you want to use the text editor only, please select \"Off\".", "cni-site-settings" ); ?><br />
<?php _e( "Default is \"On\".", "cni-site-settings" ); ?>
</div>
<div style="padding:1em 0;">
<input type="radio" name="cni_site_settings[visual_editor]" value="on"<?php if( $visual_editor != "off" ) echo " checked=\"checked\"" ?> /><?php _e( "On", "cni-site-settings" ); ?>
<input type="radio" name="cni_site_settings[visual_editor]" value="off"<?php if( $visual_editor == "off" ) echo " checked=\"checked\"" ?> /><?php _e( "Off", "cni-site-settings" ); ?>
</div>
</div>
<?php submit_button(); ?>
</form>
<?php
    }
}

// Run only in the management screen.
if( is_admin() ) {
    $cni_site_settings = new cni_site_settings();
}


// Major update
function get_cni_major_core_updates() {
    $o = get_option( 'cni_site_settings' );
    if( isset( $o['major_core_updates'] ) ) return $o['major_core_updates'];
}
if( get_cni_major_core_updates() ) {
    if( get_cni_major_core_updates() == "auto" ) {
      add_filter( 'allow_major_auto_core_updates', '__return_true' );
    } else {
      add_filter( 'allow_major_auto_core_updates', '__return_false' );
    }
}

// Update Themes
function get_cni_update_theme() {
    $o = get_option( 'cni_site_settings' );
    if( isset( $o['update_theme'] ) ) return $o['update_theme'];
}
if( get_cni_update_theme() ) {
    if( get_cni_update_theme() == "auto" ) {
      add_filter( 'auto_update_theme', '__return_true' );
    } else {
      add_filter( 'auto_update_theme', '__return_false' );
    }
}

// Update Plugins
function get_cni_update_plugin() {
    $o = get_option( 'cni_site_settings' );
    if( isset( $o['update_plugin'] ) ) return $o['update_plugin'];
}
if( get_cni_update_plugin() ) {
    if( get_cni_update_plugin() == "auto" ) {
      add_filter( 'auto_update_plugin', '__return_true' );
    } else {
      add_filter( 'auto_update_plugin', '__return_false' );
    }
}

// Autosave
function get_cni_autosave() {
    $o = get_option( 'cni_site_settings' );
    if( isset( $o['autosave'] ) ) return $o['autosave'];
}
if( get_cni_autosave() ) {
    if( get_cni_autosave() == "off" ) {
        function cni_disable_autosave() {
            wp_deregister_script( 'autosave' );
        }
        add_action( 'wp_print_scripts', 'cni_disable_autosave' );
    }
}

// Use the visual editor
function get_cni_visual_editor() {
    $o = get_option( 'cni_site_settings' );
    if( isset( $o['visual_editor'] ) ) return $o['visual_editor'];
}
if( get_cni_visual_editor() ) {
    if( get_cni_visual_editor() == "off" ) {
        function cni_disable_visual_editor_mypost() {
            add_filter( 'user_can_richedit', 'cni_disable_visual_editor_filter' );
        }
        function cni_disable_visual_editor_filter() {
            return false;
        }
        add_action( 'load-post.php', 'cni_disable_visual_editor_mypost' );
        add_action( 'load-post-new.php', 'cni_disable_visual_editor_mypost' );
    }
}

add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'cni-site-settings', false, basename( dirname( __FILE__ ) ) . '/languages' );
} );
