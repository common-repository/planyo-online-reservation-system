<?php

if (!isset($planyo_set_content_type))
  @header("Content-Type: text/html; charset=UTF-8");  
$header_written = true;

require_once(dirname(__FILE__).'/ulap.php');

function planyo_get_root() {
  if (defined('USE_SANDBOX') && constant('USE_SANDBOX')) {
    return "https://sandbox.planyo.com";
  }
  if (defined('ALT_PLANYO_ROOT'))
    return constant('ALT_PLANYO_ROOT');
  return "https://www.planyo.com";
}

function planyo_get_attribs_as_array ($str) {
  $array = array ();
  $str = ltrim($str, '? ');
  $pairs = explode('&', $str);  
  if ($pairs && is_array ($pairs)) {  
    foreach($pairs as $pair) {
      if (is_string($pair) && strpos($pair, '=') !== false) { 
        list($name, $value) = explode('=', $pair, 2);
        $name = trim ($name);
        $value = trim ($value);
        if (is_string ($name) && strlen ($name) > 0)
          $array [$name] = $value;
      }
    }
  }
  return $array;
}

function planyo_should_use_attribs() {
  return !(isset($_GET['mode']) || isset($_GET['ppp_mode']) || isset($_GET['presentation_mode']) || isset($_GET['prefill']) || isset($_GET['submitted']) || isset($_POST['mode']) || isset($_POST['ppp_mode']) || isset($_POST['presentation_mode']) || isset($_POST['prefill']) || isset($_POST['submitted']));
}

function planyo_disable_mode_in_attribs(&$attribs) {
  $attribs = str_replace("mode=", "mode_orig=", $attribs);
  $attribs = str_replace("ppp_mode=", "ppp_mode_orig=", $attribs);
  $attribs = str_replace("&resource_id=", "&resource_id_orig=", $attribs);
  $attribs = str_replace("?resource_id=", "?resource_id_orig=", $attribs);
}

function planyo_get_param($name) {
  global $_GET;
  global $_POST;
  global $planyo_attribs;
  if (isset ($_GET [$name]))
    return $_GET [$name];
  if (isset ($_POST [$name]))
    return $_POST [$name];
  if ($planyo_attribs && is_string($planyo_attribs)) {
    if (!planyo_should_use_attribs())
      planyo_disable_mode_in_attribs($planyo_attribs);
    $attrib_array = planyo_get_attribs_as_array(strip_tags(html_entity_decode($planyo_attribs)));
    if (isset ($attrib_array [$name]))
      return $attrib_array [$name];
  }
  return null;
}

function planyo_get_contents($url, $params) {
  return send_http_post($url, $params);
}

function planyo_get_language() {
  global $planyo_language;
  $language = $planyo_language;
  if (planyo_get_param('planyo_lang'))
    $language = planyo_get_param('planyo_lang');
  else if (planyo_get_param('lang') && strlen(planyo_get_param('lang') == 2))
    $language = planyo_get_param('lang');
  return $language;
}

function planyo_add_other_params($params) {
  global $planyo_attribs;
  $other_params = $_GET;
  if ($planyo_attribs && is_string($planyo_attribs)) {
    if (!planyo_should_use_attribs())
      planyo_disable_mode_in_attribs($planyo_attribs);
    $attrib_array = planyo_get_attribs_as_array(strip_tags(html_entity_decode($planyo_attribs)));
    $other_params = array_merge($attrib_array, $other_params);
  }
  if ($other_params && count($other_params) > 0) {
    foreach($other_params as $name=>$value) {
      if ((strpos ($name, 'ppp_') === 0 || strpos ($name, 'prop_res_') === 0) && !array_key_exists($name, $params)) {
        $params [$name] = $value;
      }
    }
  }
  return $params;
}

function planyo_output_resource_list() {
  global $planyo_site_id, $planyo_metasite_id, $planyo_feedback_url, $planyo_default_mode, $planyo_language, $planyo_files_location, $planyo_resource_ordering, $planyo_login_info, $planyo_js_library_used;
  $planyo_default_mode = 'empty';
  $language = planyo_get_language();
  if ($planyo_metasite_id && planyo_get_param('site_id')) {
    $site_id_used = planyo_get_param('site_id');
    $metasite_id_used = $planyo_metasite_id;
  }
  else if ($planyo_site_id && !$planyo_metasite_id) {
    $site_id_used = $planyo_site_id;
    $metasite_id_used = '';
  }
  else {
    $site_id_used = '';
    $metasite_id_used = $planyo_metasite_id;
  }
  $params = array('modver'=>'2.7','site_id'=>$site_id_used,'metasite_id'=>$metasite_id_used, 'mode'=>'display_resource_list_code','feedback_url'=>$planyo_feedback_url, 'language'=>$language ? $language : '', 'sort'=>planyo_get_param('sort') ? planyo_get_param('sort') : $planyo_resource_ordering, 'res_filter_name'=>planyo_get_param('res_filter_name'), 'res_filter_value'=>planyo_get_param('res_filter_value'), 'planyo_files_location'=>$planyo_files_location);
  if (is_array($planyo_login_info)) {
    $params['login_cs'] = $planyo_login_info['login_cs'];
    $params['login_email'] = $planyo_login_info['email'];
  }
  if (isset($_COOKIE['planyo_cart_id']) && isset($_COOKIE['planyo_first_reservation_id'])) {
    $params['cart_id'] = $_COOKIE['planyo_cart_id'];
    $params['first_reservation_id'] = $_COOKIE['planyo_first_reservation_id'];
  }

  $params['plugin_mode'] = "10"; // jQuery
  $params['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
  echo planyo_get_contents(planyo_get_root() . "/rest/planyo-reservations.php", planyo_add_other_params($params));
  echo "<script type='text/javascript'>\nvar planyo_force_mode='empty';\n</script>\n";
}

function planyo_output_site_list() {
  global $planyo_metasite_id, $planyo_feedback_url, $planyo_default_mode, $planyo_language, $planyo_files_location, $planyo_login_info, $planyo_js_library_used;
  $planyo_default_mode = 'empty';
  $language = planyo_get_language();
  $params = array('modver'=>'2.7','site_id'=>"",'metasite_id'=> $planyo_metasite_id, 'mode'=>'display_site_list_code','feedback_url'=>$planyo_feedback_url, 'language'=>$language ? $language : '', 'sort'=>planyo_get_param('sort'), 'cal_filter_name'=>planyo_get_param('cal_filter_name'), 'cal_filter_value'=>planyo_get_param('cal_filter_value'), 'planyo_files_location'=>$planyo_files_location);
  if (is_array($planyo_login_info)) {
    $params['login_cs'] = $planyo_login_info['login_cs'];
    $params['login_email'] = $planyo_login_info['email'];
  }
  if (isset($_COOKIE['planyo_cart_id']) && isset($_COOKIE['planyo_first_reservation_id'])) {
    $params['cart_id'] = $_COOKIE['planyo_cart_id'];
    $params['first_reservation_id'] = $_COOKIE['planyo_first_reservation_id'];
  }
  $params['plugin_mode'] = "10"; // jQuery
  $params['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
  echo planyo_get_contents(planyo_get_root() . "/rest/planyo-reservations.php", planyo_add_other_params($params));
  echo "<script type='text/javascript'>\nvar planyo_force_mode='empty';\n</script>\n";
}

function planyo_output_resource_details() {
  global $planyo_site_id, $planyo_metasite_id, $planyo_feedback_url, $planyo_default_mode, $planyo_language, $planyo_files_location, $planyo_resource_id, $planyo_js_library_used, $planyo_login_info;
  $planyo_default_mode = 'empty';
  $language = planyo_get_language();
  $params = array('modver'=>'2.7','site_id'=>($planyo_site_id && !$planyo_metasite_id ? $planyo_site_id : ""),'metasite_id'=>($planyo_metasite_id ? $planyo_metasite_id : ""), 'resource_id'=>($planyo_resource_id ? $planyo_resource_id : planyo_get_param('resource_id')), 'mode'=>'display_single_resource_code','feedback_url'=>$planyo_feedback_url, 'language'=>$language ? $language : '', 'user_agent'=>$_SERVER['HTTP_USER_AGENT'], 'planyo_files_location'=>$planyo_files_location);
  if (is_array($planyo_login_info)) {
    $params['login_cs'] = $planyo_login_info['login_cs'];
    $params['login_email'] = $planyo_login_info['email'];
  }
  if (isset($_COOKIE['planyo_cart_id']) && isset($_COOKIE['planyo_first_reservation_id'])) {
    $params['cart_id'] = $_COOKIE['planyo_cart_id'];
    $params['first_reservation_id'] = $_COOKIE['planyo_first_reservation_id'];
  }
  $params['plugin_mode'] = "10"; // jQuery
  echo planyo_get_contents(planyo_get_root() . "/rest/planyo-reservations.php", planyo_add_other_params($params));
  echo "<script type='text/javascript'>\nvar planyo_force_mode='empty';\n</script>\n";
}

function planyo_setup() {
  global $planyo_feedback_url, $planyo_always_use_ajax, $planyo_site_id, $planyo_metasite_id, $planyo_default_mode, $planyo_resource_id, $planyo_files_location, $planyo_language, $planyo_sort_fields, $planyo_extra_search_fields, $planyo_js_library_used, $planyo_include_js_library, $planyo_login_info, $planyo_resource_ordering, $planyo_attribs;

  $planyo_metasite_id = null;
  if (!$planyo_site_id || $planyo_site_id == 'demo')
    $planyo_site_id = 11;  // demo site
  else if (substr($planyo_site_id, 0, 1) == 'M')
    $planyo_metasite_id = substr($planyo_site_id, 1);  // metasite ID: set only for metasites

  if (planyo_get_param('ppp_mode')) {
    $planyo_default_mode = planyo_get_param('ppp_mode');
  }
  else if (planyo_get_param('mode')) {
    $planyo_default_mode = planyo_get_param('mode');
  }
  else {
    $resource_id = planyo_get_param('resource_id');
    $presentation_off = (planyo_get_param('presentation_mode') == '0' || (!planyo_get_param('presentation_mode') && $planyo_default_mode == 'search'));
    if ($resource_id && (planyo_get_param('prefill') || planyo_get_param('submitted')))
      $planyo_default_mode = 'reserve';
    else if ($presentation_off)
      $planyo_default_mode = 'search';
    else if ($resource_id)
      $planyo_default_mode = 'resource_desc';
    else if (planyo_get_param('presentation_mode') == '1' || !$planyo_default_mode)
      $planyo_default_mode = 'resource_list';
  }

  if (!$planyo_always_use_ajax) {
    $planyo_feedback_url = planyo_get_param('feedback_url');
    if (!$planyo_feedback_url)
      $planyo_feedback_url = ($_SERVER['SERVER_NAME'] === 'localhost' ? "http://" : "https://") . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    
    if ($planyo_default_mode == 'resource_desc' && (planyo_get_param('resource_id') || $planyo_resource_id))
        planyo_output_resource_details();
    else if ($planyo_metasite_id && $planyo_default_mode == 'site_list' && !planyo_get_param('site_id'))
      planyo_output_site_list();
    else if ($planyo_default_mode == 'resource_list')
      planyo_output_resource_list();
  }

?>
<script type="text/javascript">
/* these values are copied from the PHP settings; please update the original values above and leave these unchanged */
var ulap_script="ulap.php"; /* file name to one of the ULAP scripts, e.g. ulap.php, OR "jsonp" for a pure javascript implementation */
var planyo_site_id="<?php echo str_replace("\"", "", $planyo_site_id);?>"; /* ID of your planyo site */
var planyo_resource_id="<?php if ($planyo_resource_id) echo str_replace("\"", "", $planyo_resource_id);?>"; /* optional: ID of the resource being reserved */
var planyo_files_location="<?php echo str_replace("\"", "", $planyo_files_location);?>"; /* relative or absolute directory where the planyo files are kept (usually '/planyo-files') */
var planyo_language="<?php echo str_replace("\"", "", $planyo_language);?>"; /* you can optionally change the language here, e.g. 'FR' or 'ES' or pass the languge in the 'planyo_lang' parameter */
var sort_fields="<?php echo str_replace("\"", "", $planyo_sort_fields);?>"; /* comma-separated sort fields -- a single field will hide the sort dropdown box */
var planyo_resource_ordering="<?php if (isset($planyo_resource_ordering)) echo str_replace("\"", "", $planyo_resource_ordering);?>"; /* optional sort criterium for resource list */
var extra_search_fields="<?php echo str_replace("\"", "", $planyo_extra_search_fields);?>"; /* comma-separated extra fields in the search box */
var planyo_default_mode="<?php echo str_replace("\"", "", $planyo_default_mode);?>";
var planyo_use_https=true; /* set this to true if embedding planyo on a secure website (SSL) */
var planyo_attribs='<?php
if ($planyo_attribs && is_string($planyo_attribs)) {
  if (!planyo_should_use_attribs())
    planyo_disable_mode_in_attribs($planyo_attribs);
  echo '?'.strip_tags(html_entity_decode(str_replace("\"", "", $planyo_attribs)));
}
?>'; /* optional attributes for non-default modes */
<?php
    if (is_array($planyo_login_info)) {
      echo "var planyo_login=new Array();\n";
      foreach($planyo_login_info as $key=>$val) {
      if ($val)
        echo "planyo_login['".trim($key)."']=\"".str_replace("\n", "", str_replace("\"", "'", trim($val)))."\";\n";
      }
    }
?>
</script>

<script type="text/javascript">
function get_param (name) {name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");var regexS = "[\\?&]"+name+"=([^&#]*)";var regex = new RegExp (regexS);var results = regex.exec (window.location.href);if (results == null) return null;else  return results[1];}
if (get_param('ppp_mode')) planyo_embed_mode = get_param('ppp_mode'); else if (get_param('mode')) planyo_embed_mode = get_param('mode');
function get_full_planyo_file_path(name) {if(planyo_files_location.length==0||planyo_files_location.lastIndexOf('/')==planyo_files_location.length-1)return planyo_files_location+name; else return planyo_files_location+'/'+name;}
</script>
<link rel='stylesheet' href='<?php echo planyo_get_root();?>/schemes/?calendar=<?php echo $planyo_site_id;?>&detect_mobile=auto&sel=scheme_css' type='text/css' />
<div id='planyo_content' class='planyo'><img src='https://www.planyo.com/images/hourglass.gif' align='middle' /></div>
<?php
if ($planyo_include_js_library) {
?>
<script type='text/javascript' src='https://www.planyo.com/Plugins/PlanyoFiles/jquery-3.6.4.min.js'></script>
<?php
}
?>
<script src='https://www.planyo.com/Plugins/PlanyoFiles/booking-utils.js' type='text/javascript'></script>
<noscript>
<a href='https://www.planyo.com/about-calendar.php?calendar=<?php echo $planyo_site_id;?>'>Make a reservation</a><br/><br/><a href='https://www.planyo.com/'>Reservation
  system powered by Planyo</a>
</noscript>
<?php
}
?>