<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<?php echo Lang::get('email_messages.ip_notification', array('username' => $user->username, 'company_name_domain' => Config::get('config_custom.company_name_domain'), 'ip' => $ip, 'lastlogin_ip' => $user->ip_lastlogin, 'date_and_time' => date('Y M d, H:i:s'))); ?>
	</body>
</html>
