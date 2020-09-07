<?php

$mailto = 'couch@romanvinilov.ru';
$subject = 'Центр Романа Винилова';

$form_data = array(
	'Заголовок'		=> 'Перезвоните мне',
	'E-mail'			=> $_POST['lead_email'] ?: 'Не указан',
	'Телефон' 		=> $_POST['lead_phone'] ?: 'Не указан',
);

$c = true;
foreach ($form_data as $key => $value) {
	if ( $value != "" ) {
		$message .= "
		" . ( ($c = !$c) ? '<tr>':'<tr style="background-color: #f8f8f8;">' ) . "
			<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$key</b></td>
			<td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
			</tr>
		";
	}
}

$message = "<table style='width: 100%;'>$message</table>";

function send_mail($to, $subject, $message, $base64 = false) {
	$separator = md5(time());
	$eol = "\r\n";

	$headers = "From: " . $subject . " <" . $to . ">" . $eol;
	$headers .= "MIME-Version: 1.0" . $eol;
	$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;

	$body = "--" . $separator . $eol;
	$body .= "Content-Type: text/html; charset=utf-8" . $eol;
	$body .= "Content-Transfer-Encoding: 8bit" . $eol;
	$body .= $eol . $message . $eol . $eol;

	if ($base64) {
		$filename = 'myfile.png';
		$content = chunk_split(preg_replace('#^data:image/[^;]+;base64,#', '', $base64));

		$body .= "--" . $separator . $eol;
		$body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
		$body .= "Content-Transfer-Encoding: base64" . $eol;
		$body .= "Content-Disposition: attachment" . $eol;
		$body .= $eol . $content . $eol . $eol;
		$body .= "--" . $separator . "--";
	}

	return mail($to, $subject, $body, $headers);
}

send_mail($mailto, $subject, $message);
