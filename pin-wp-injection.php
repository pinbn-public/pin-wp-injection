<?php

/**
 * Plugin Name: PIN WP Injection
 * Plugin URI: https://pinbn.com/
 * Description: This plugin embeds PIN's custom tags in a Wordpress site.
 * Version: 2.5.0
 * Author: PIN Business Network
 * Author URI: https://pinbn.com/
 * Text Domain: code-injection
 */

// Prevent direct access to the plugin file
defined('ABSPATH') or die;

function pin_wp_tags_render() {
    $pit_server = get_option('pin_wp_tags_pit_server', 'pitai.io');
    $pin_company_id = get_option('pin_wp_tags_pin_company_id');
    $pit_options = get_option('pin_wp_tags_pit_options', '');
    $tpin_server = get_option('pin_wp_tags_tpin_server', 'trackingpin.com');
    $tpin_site = get_option('pin_wp_tags_tpin_site');
    if (!empty($pit_options) && !str_starts_with($pit_options,'&')) $pit_options = '&'.$pit_options;

    $content = "";
    if (!empty($pin_company_id)) {
        $content .= "<script async src=\"https://".$pit_server."/pit/?c=".$pin_company_id.$pit_options."\"></script>";
    }
    if (!empty($tpin_site)) {
        $content .= "<!-- TrackingPin -->
        <script>
          var _paq = window._paq = window._paq || [];
          _paq.push(['trackPageView']);
          _paq.push(['enableLinkTracking']);
          (function() {
            var u='https://".$tpin_server."/';
            _paq.push(['setTrackerUrl', u+'trackingpin.php']);
            _paq.push(['setSiteId', '".$tpin_site."']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.async=true; g.src=u+'trackingpin.js'; s.parentNode.insertBefore(g,s);
          })();
        </script>
        <!-- End TrackingPin Code -->";
    }
    echo $content;
}
add_action('wp_head', 'pin_wp_tags_render');

function pin_wp_tags_settings_init() {
	register_setting('general', 'pin_wp_tags_pin_company_id');
	register_setting('general', 'pin_wp_tags_pit_server');
	register_setting('general', 'pin_wp_tags_pit_options');
	register_setting('general', 'pin_wp_tags_tpin_site');
	register_setting('general', 'pin_wp_tags_tpin_server');

	add_settings_section('pin_wp_tags_settings_section', 'PIN WP Tags', 'pin_wp_tags_settings_section_render', 'general');

	add_settings_field('pin_wp_tags_pin_company_id', 'PIN Company ID', 'pin_wp_tags_pin_company_id_render', 'general', 'pin_wp_tags_settings_section');
	add_settings_field('pin_wp_tags_pit_server', 'PIT Server', 'pin_wp_tags_pit_server_render', 'general', 'pin_wp_tags_settings_section');
	add_settings_field('pin_wp_tags_pit_options', 'PIT Custom Query', 'pin_wp_tags_pit_options_render', 'general', 'pin_wp_tags_settings_section');
	add_settings_field('pin_wp_tags_tpin_site', 'TrackingPIN Site ID', 'pin_wp_tags_tpin_site_render', 'general', 'pin_wp_tags_settings_section');
	add_settings_field('pin_wp_tags_tpin_server', 'TrackingPIN Server', 'pin_wp_tags_tpin_server_render', 'general', 'pin_wp_tags_settings_section');
}
add_action('admin_init', 'pin_wp_tags_settings_init');

function pin_wp_tags_settings_section_render() {
  echo '<p>Only PIN Company ID and TrackingPIN Site ID are required.</p>';
}

function pin_wp_tags_pin_company_id_render() {
	$setting = get_option('pin_company_id');
	?> <input type="text" name="pin_wp_tags_pin_company_id" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>"> <i>(required)</i> <?php
}
function pin_wp_tags_pit_server_render() {
	$setting = get_option('pit_server');
	?> <input type="text" name="pin_wp_tags_pit_server" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>"> <i>(default: pitai.io)</i> <?php
}
function pin_wp_tags_pit_options_render() {
	$setting = get_option('pit_options');
	?> <input type="text" name="pin_wp_tags_pit_options" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>"> <i>(only used to customize PIT with options)</i> <?php
}
function pin_wp_tags_tpin_site_render() {
	$setting = get_option('tpin_site');
	?> <input type="text" name="pin_wp_tags_tpin_site" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>"> <i>(required)</i> <?php
}
function pin_wp_tags_tpin_server_render() {
	$setting = get_option('tpin_server');
	?> <input type="text" name="pin_wp_tags_tpin_server" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>"> <i>(default: trackingpin.com)</i> <?php
}
