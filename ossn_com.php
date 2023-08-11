<?php
define('__OSSN_REPORT__', ossn_route()->com . 'OssnReport/');
require_once(__OSSN_REPORT__ . 'classes/OssnReport.php');
  ossn_add_hook('notification:view', 'ossnreport:report', 'ossn_report_notification');    
  ossn_add_hook('wall', 'post:menu', 'ossn_wall_post_menu_report');
function ossn_report() {
    ossn_extend_view('css/ossn.default', 'css/report');
    if (ossn_isLoggedin()) {
        ossn_register_action('report/user', __OSSN_REPORT__ . 'actions/user/report.php');
    }
}
function ossn_wall_post_menu_report($name, $type, $params) {		
		$user = ossn_get_page_owner_guid();
		$report = ossn_site_url("action/report/user?user={$user}", true);
				ossn_unregister_menu('report', 'wallpost');	
				ossn_register_menu_item('wallpost', array(
						'name'      => 'report',						
						'text'      => ossn_print('report'),
						'href'      => $report,
			));				
} 
function ossn_user_report_menu($name, $type, $params) {
    $user = ossn_get_page_owner_guid();
    $report = ossn_site_url("action/report/user?user={$user}", true);
    ossn_register_menu_link('report', ossn_print('report'), $report, 'profile_extramenu');
}  
function ossn_report_notification($name, $type, $return, $params) {
    $notif = $params;
    $baseurl = ossn_site_url();
    $user = ossn_user_by_guid($notif->poster_guid);
    $user->fullname = "<strong>{$user->fullname}</strong>";
    $img = "<div class='notification-image'><img src='{$baseurl}avatar/{$user->username}/small' /></div>";
    $type = 'report';
    $type = "<div class='ossn-notification-icon-report'></div>";
    if ($notif->viewed !== NULL) {
        $viewed = '';
    } elseif ($notif->viewed == NULL) {
        $viewed = 'class="ossn-notification-unviewed"';
    }
    $url = $user->profileURL();
    $notification_read = "{$baseurl}notification/read/{$notif->guid}?notification=" . urlencode($url);
    return "<a href='{$notification_read}'>
	       <li {$viewed}> {$img} 
		   <div class='notfi-meta'> {$type}
		   <div class='data'>" . ossn_print("ossn:notifications:{$notif->type}", array($user->fullname)) . '</div>
		   </div></li></a>';
}
 ossn_register_callback('page', 'load:profile', 'ossn_user_report_menu', 1);
 ossn_register_callback('ossn', 'init', 'ossn_report');
 
