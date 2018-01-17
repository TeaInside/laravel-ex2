<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<?php echo Lang::get('email_messages.confirm_withdraw', array('username' => $user->username, 'company_name_domain' => Config::get('config_custom.company_name_domain'), 'amount' => $amount, 'wallet_type' => $wallet->type, 'withdraw_link' => URL::to('user/withdraw-confirm', array($withdraw_id,$confirmation_code)) )); ?>
	</body>
</html>