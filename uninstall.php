<?php
if ( ! defined( 'ABSPATH' ) ) exit;


if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$option_name = 'cni_site_settings';
delete_option( $option_name );
delete_site_option( $option_name );
