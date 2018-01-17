<?php

return array(
	'ip_notification' => '<h2>Hi :username,</h2><div>This email is to notify you of a successful login on your account at :company_name_domain.<br /><br />You just login from different IP (:ip) with last login from IP (:lastlogin_ip).<br />Date and time: :date_and_time<br /><br /><p>If you did not request this action, contact support immediately</p>:company_name_domain</div>',
	'confirm_withdraw' => '<h2>Hi :username, you have requested to withdraw :amount :wallet_type, </h2><div>To complete your withdrawals, click below link: <br><a href=":withdraw_link">:withdraw_link</a><p>If you did not request this action, contact support immediately</p>:company_name_domain</div>',
	'password_reminder' =>'		<h2>Password Reset</h2><div>To reset your password, please click on the following link :password_reset_link.<p>If you did not request this action then just ignore this e-mail.</p>:company_name_domain</div>'
);
