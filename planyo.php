<?php
/*
Plugin Name: Planyo online reservation system
Plugin URI: http://www.planyo.com/wordpress-reservation-system
Description: This plugin embeds the Planyo.com online reservation system. Before using it, you'll need to create an account at planyo.com. Please see <a href='http://www.planyo.com/wordpress-reservation-system'>http://www.planyo.com/wordpress-reservation-system</a> for more info.
Version: 3.0
Author: Xtreeme GmbH
Author URI: http://www.planyo.com/
*/

/*  Copyright 2015 Xtreeme GmbH  (email : planyo@xtreeme.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


function planyo_menu() {
  add_options_page('Planyo Options', 'Planyo', 'administrator', 'planyo', 'planyo_options');
  add_action('admin_init', 'register_planyo_settings');
}

function register_planyo_settings() {
  register_setting('planyo-settings-group', 'site_id');
  register_setting('planyo-settings-group', 'extra_search_fields');
  register_setting('planyo-settings-group', 'sort_fields');
  register_setting('planyo-settings-group', 'planyo_language');
  register_setting('planyo-settings-group', 'default_mode');
  register_setting('planyo-settings-group', 'seo_friendly');
  register_setting('planyo-settings-group', 'use_login');
  register_setting('planyo-settings-group', 'login_integration_code');
  register_setting('planyo-settings-group', 'resource_ordering');
  register_setting('planyo-settings-group', 'js_framework');
}

function planyo_output_select_option ($value, $text, $option, $selected = false) {
  echo "<option value='$value' ";
  echo (get_option($option)==$value || ($selected && !get_option($option))) ? "selected='selected'" : "";
  echo ">$text</option>";
}

function planyo_options() {
?>
<div class="wrap">
<h2>Planyo Plugin Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'planyo-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Planyo site ID</th>
        <td><input type="text" name="site_id" value="<?php echo get_option('site_id') ? get_option('site_id') : 'demo'; ?>" /><br/>
        <span class='description'>ID of your planyo site. If you don't have a planyo site yet, create one first at www.planyo.com. The default value (demo) will use a demonstration site.</span>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Default language of Planyo interface</th>
        <td><select name='planyo_language'>
		    <?php planyo_output_select_option('0', 'Auto-detect', 'planyo_language', true);?>
		    <?php planyo_output_select_option('EN', 'English', 'planyo_language');?>
		    <?php planyo_output_select_option('FR', 'French', 'planyo_language');?>
		    <?php planyo_output_select_option('IT', 'Italian', 'planyo_language');?>
		    <?php planyo_output_select_option('ES', 'Spanish', 'planyo_language');?>
		    <?php planyo_output_select_option('DE', 'German', 'planyo_language');?>
		    <?php planyo_output_select_option('PL', 'Polish', 'planyo_language');?>
		    <?php planyo_output_select_option('SV', 'Swedish', 'planyo_language');?>
		    <?php planyo_output_select_option('NO', 'Norwegian', 'planyo_language');?>
		    <?php planyo_output_select_option('FI', 'Finnish', 'planyo_language');?>
		    <?php planyo_output_select_option('IS', 'Icelandic', 'planyo_language');?>
		    <?php planyo_output_select_option('DA', 'Danish', 'planyo_language');?>
		    <?php planyo_output_select_option('RO', 'Romanian', 'planyo_language');?>
		    <?php planyo_output_select_option('RU', 'Russian', 'planyo_language');?>
		    <?php planyo_output_select_option('NL', 'Dutch', 'planyo_language');?>
		    <?php planyo_output_select_option('EL', 'Greek', 'planyo_language');?>
		    <?php planyo_output_select_option('BR', 'Portuguese (Brazil)', 'planyo_language');?>
		    <?php planyo_output_select_option('PT', 'Portuguese (Portugal)', 'planyo_language');?>
		    <?php planyo_output_select_option('CS', 'Czech', 'planyo_language');?>
		    <?php planyo_output_select_option('CA', 'Catalan', 'planyo_language');?>
		    <?php planyo_output_select_option('ET', 'Estonian', 'planyo_language');?>
		    <?php planyo_output_select_option('JA', 'Japanese', 'planyo_language');?>
		    <?php planyo_output_select_option('HR', 'Croatian', 'planyo_language');?>
		    <?php planyo_output_select_option('KL', 'Greenlandic', 'planyo_language');?>
		    <?php planyo_output_select_option('HU', 'Hungarian', 'planyo_language');?>
		    <?php planyo_output_select_option('SK', 'Slovak', 'planyo_language');?>
		    <?php planyo_output_select_option('SL', 'Slovenian', 'planyo_language');?>
		    <?php planyo_output_select_option('UA', 'Ukrainian', 'planyo_language');?>
		    <?php planyo_output_select_option('KO', 'Korean', 'planyo_language');?>
		    <?php planyo_output_select_option('FO', 'Faroese', 'planyo_language');?>
		    <?php planyo_output_select_option('EU', 'Basque', 'planyo_language');?>
		    <?php planyo_output_select_option('LT', 'Lithuanian', 'planyo_language');?>
		    <?php planyo_output_select_option('VI', 'Vietnamese', 'planyo_language');?>
		    <?php planyo_output_select_option('TW', 'Chinese (Traditional)', 'planyo_language');?>
		    <?php planyo_output_select_option('BG', 'Bulgarian', 'planyo_language');?>
		    <?php planyo_output_select_option('AF', 'Afrikaans', 'planyo_language');?>
		    <?php planyo_output_select_option('TH', 'Thai', 'planyo_language');?>
		    <?php planyo_output_select_option('AR', 'Arabic', 'planyo_language');?>

        </select><br/>
        <span class='description'>Choose one of the supported languages. You can also modify the templates (in your planyo administration panel) to display the language choice to the user or pass the language as shortcode parameter language (e.g. [planyo language='FR']) or a parameter in the URL (&lang=FR).</span>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Default mode</th>
        <td><select name='default_mode'>
		    <?php planyo_output_select_option('search', 'Search box', 'default_mode');?>
		    <?php planyo_output_select_option('resource_list', 'Resource list', 'default_mode', true);?>
		    <?php planyo_output_select_option('upcoming_availability', 'Upcoming availability', 'default_mode');?>
		    <?php planyo_output_select_option('reserve', 'Reservation form', 'default_mode');?>
		    <?php planyo_output_select_option('empty', 'Do nothing', 'default_mode');?>
        </select><br/>
        <span class='description'>Choose the initial (default) mode: 'Search box' to allow clients to search for available dates or 'Resource list' to display a list of all resources (in such case search must be initiated by embedding an extra search box -- see last step of integration in Planyo's admin panel). Reservation form show directly the reservation form. Choosing 'Do nothing' will not display anything by default but will require you to either pass the resource ID to the module as shortcode parameter resource_id (e.g. [planyo resource_id='xxx']) or a parameter in the URL (resource_id) or add an external search box or calendar preview.</span>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Additional fields of the search box (search mode)</th>
        <td><input type="text" name="extra_search_fields" value="<?php echo get_option('extra_search_fields'); ?>" /><br/>
        <span class='description'>Comma-separated extra fields of the search box. Can be left empty. Example: 'Number of persons'. You first need to define these fields in settings/custom resource properties.</span>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Sort-by field choices (search mode)</th>
        <td><input type="text" name="sort_fields" value="<?php echo get_option('sort_fields') ? get_option('sort_fields') : 'name,price'; ?>" /><br/>
        <span class='description'>Comma-separated possible sort fields. A single value will hide this parameter, more than one value will give the user a choice in form of a drop-down box. Allowed values: name, price, prop_res_xxx (custom resource properties). Can be left empty.</span>
        </td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Ordering of resources (resource list mode)</th>
        <td><input type="text" name="resource_ordering" value="<?php echo get_option('resource_ordering') ? get_option('resource_ordering') : 'name'; ?>" /><br/>
        <span class='description'>Sorting criterium for the listing of resources in the resource list view. This can be set to name (this is the default) which sorts by resource name, or one of prop_res_xxx (custom resource property defined in Planyo). Can be left empty.</span>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">SEO friendly</th>
        <td><select name='seo_friendly'>
		    <?php planyo_output_select_option('1', 'Yes', 'seo_friendly', true);?>
		    <?php planyo_output_select_option('0', 'No', 'seo_friendly');?>
        </select><br/>
        <span class='description'>Choose whether the plugin in the resource list and resource details modes should be SEO friendly (information retrieved from the server when loading the page) or not (information retrieved using Javascript/AJAX). Choosing yes will add a slight delay to the loading time of the page but will let search engines index the resource names, descriptions and photos.</span>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Integrate with wordpress login</th>
        <td><select name='use_login'>
		    <?php planyo_output_select_option('1', 'Yes', 'use_login', true);?>
		    <?php planyo_output_select_option('0', 'No', 'use_login');?>
        </select><br/>
        <span class='description'>Choose whether the plugin should use the login information from this wordpress site. If used, the reservation form items will be automatically prefilled with known values and subsequent reservations will use previously entered data.</span>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Login integration code</th>
        <td><input type="text" name="login_integration_code" value="<?php echo get_option('login_integration_code'); ?>" /><br/>
        <span class='description'>If integration with wordpress login is switched on, you'll need to enter the login integration code which you'll find in <a href='https://www.planyo.com/integration-settings.php' target='_blank'>advanced integration settings</a> in the Planyo backend.</span>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">jQuery framework</th>
        <td><select name='js_framework'>
        <?php planyo_output_select_option('jquery', 'jQuery will be included by planyo', 'js_framework', true);?>
		    <?php planyo_output_select_option('jquery-noinclude', 'jQuery is already used on this website', 'js_framework');?>
        </select><br/>
        <span class='description'>Specify if jQuery (which is required) should be included by this plugin or if your website already includes jQuery.</span>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"></th>
        <td><span class='description'><b>To embed Planyo on a page, simply enter the [planyo] shortcode anywhere in the body of a page. See also our <a href='http://www.planyo.com/wordpress-reservation-system/' target='_blank'>step-by-step tutorial</a>.</b></span></td>
        </tr>

    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php
}

function planyo_init() {
  add_action('admin_menu', 'planyo_menu');
}

add_action('init', 'planyo_init');

$planyo_directory = str_replace(basename(__FILE__),"",plugin_basename(__FILE__));
require_once(WP_PLUGIN_DIR.'/'.$planyo_directory.'planyo-plugin-impl.php');

function planyo_code($atts) {
  global $planyo_always_use_ajax, $planyo_site_id, $planyo_default_mode, $planyo_files_location, $planyo_language, $planyo_sort_fields, $planyo_extra_search_fields, $planyo_resource_id, $planyo_directory, $planyo_js_library_used, $planyo_include_js_library, $planyo_login_info, $planyo_resource_ordering, $planyo_attribs;

  ob_start();

  // change the following values to match your settings
  $planyo_site_id = get_option('site_id');  // ID of your planyo site. It can be a number or the default value ('demo') to see demonstration of the plugin
  $wp_plugin_url = WP_PLUGIN_URL;
  if (strpos($wp_plugin_url, "://".$_SERVER ['SERVER_NAME']) === false && strpos($wp_plugin_url, "://") !== false) { // make sure the current domain is used for $wp_plugin_url
    $before_domain_pos = strpos($wp_plugin_url, "://");
    $after_domain_pos = strpos($wp_plugin_url, "/", $before_domain_pos + 3);
    if ($after_domain_pos !== false) {
      $wp_plugin_url = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['SERVER_NAME'] . substr($wp_plugin_url, $after_domain_pos);
    }
  }
  $planyo_files_location = $wp_plugin_url.'/'.$planyo_directory; // relative or absolute directory where the planyo files are kept
  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
    $planyo_files_location = str_replace("http:","https:",$planyo_files_location);
  if (isset($atts) && isset($atts['language'])) {
    $planyo_language = $atts['language'];
  }
  else {
    $planyo_language = get_option('planyo_language');  // you can optionally change the language here, e.g. 'FR' or 'ES' or pass the languge in the 'lang' parameter
    if (!$planyo_language) {
      $locale = get_locale();
      if ($locale)
        $planyo_language = strtoupper(substr($locale, 0, 2));
      if (!$planyo_language)
        $planyo_language = 'EN';
    }
  }
  $planyo_always_use_ajax = get_option('seo_friendly')=='1' ? false : true;  // set to true to use AJAX to display resource list and resource details views
  $planyo_sort_fields = get_option('sort_fields');  // comma-separated sort fields -- a single field will hide the sort dropdown box
  $planyo_resource_ordering = get_option('resource_ordering');
  $planyo_extra_search_fields = get_option('extra_search_fields');  // comma-separated extra fields in the search box
  if (isset($atts) && isset($atts['mode']))
    $planyo_default_mode = $atts['mode'];
  else
    $planyo_default_mode = get_option('default_mode');  // initial (defualt) plugin mode; one of: 'resource_list', 'search', 'empty', 'upcoming_availability'
  if (isset($atts) && isset($atts['attribute_string']))
    $planyo_attribs = strip_tags(html_entity_decode($atts['attribute_string']));
  $planyo_js_library_used = (!get_option('js_framework') || get_option('js_framework') == 'jquery' || get_option('js_framework') == 'jquery-noinclude') ? 'jquery' : 'mootools';  // jquery or mootools
  $planyo_include_js_library = !get_option('js_framework') || get_option('js_framework') == 'jquery' || get_option('js_framework') == 'mootools'; // set to false if you already include jQuery on your site
  if (isset($atts) && isset($atts['resource_id']))
    $planyo_resource_id = $atts['resource_id'];
  else
    $planyo_resource_id = null;  // optional: ID of the resource being reserved
  $login_integration_code = get_option('login_integration_code');
  if (get_option('use_login')=='1') {
    $wp_login_info = wp_get_current_user();
    if ($wp_login_info && $wp_login_info->ID) {
      $planyo_login_info = array('first_name'=>$wp_login_info->user_firstname, 'last_name'=>$wp_login_info->user_lastname, 'email'=>$wp_login_info->user_email, 'login_cs'=>$login_integration_code ? sha1($wp_login_info->user_email . $login_integration_code) : 'nocode');
    }
  }

?>
<div id='planyo_plugin_code' class='planyo_wp planyo'>
<?php planyo_setup();?>
</div>
<?php

  $str = ob_get_contents();
  ob_end_clean();
  return $str;
}

add_shortcode('planyo', 'planyo_code');

?>