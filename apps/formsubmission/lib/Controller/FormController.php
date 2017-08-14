<?php

namespace OCA\formsubmission\Controller;

use Exception;

$ReceivingEmail = "maf@paspx.com"; // the email address you wish to receive these mails through
$thanksPage = ''; // URL to 'thanks for sending mail' page; leave empty to keep message on the same page
$maxPoints = 4; // max points a person can hit before it refuses to submit - recommend 4
$requiredFields = "comments"; // names of the fields you'd like to be required as a minimum, separate each field with a comma


// DO NOT EDIT BELOW HERE
$error_msg = array();
$result = null;

$requiredFields = explode(",", $requiredFields);

function clean($data) {
    $data = trim(stripslashes(strip_tags($data)));
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if ( !empty( $requiredFields ) ) {
        foreach($requiredFields as $field) {
            trim($_POST[$field]);

            if (!isset($_POST[$field]) || empty($_POST[$field]) && array_pop($error_msg) != "Please fill in the content field and submit again.\r\n")
                $error_msg[] = "Please fill in the content field and submit again.";
        }
    }

    if (!empty($_POST['name']) && !preg_match("/^[a-zA-Z-'\s]*$/", stripslashes($_POST['name'])))
        $error_msg[] = "The name field must not contain special characters.\r\n";
    if (!empty($_POST['email']) && !preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', strtolower($_POST['email'])))
        $error_msg[] = "That is not a valid e-mail address.\r\n";

    if ($error_msg == NULL) {
        $subject = "Automatic Form Email";

        $message = "You received this e-mail message through the internal whistleblower form: \n\n";
        foreach ($_POST as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $subval) {
                    $message .= ucwords($key) . ": " . clean($subval) . "\r\n";
                }
            } else {
                $message .= ucwords($key) . ": " . clean($val) . "\r\n";
            }
        }
        $message .= "\r\n";
        $message .= 'IP: '.$_SERVER['REMOTE_ADDR']."\r\n";
        $message .= 'Browser: '.$_SERVER['HTTP_USER_AGENT']."\r\n";

        if (strstr($_SERVER['SERVER_SOFTWARE'], "Win")) {
            $headers   = "From: $ReceivingEmail\r\n";
        } else {
            $headers   = "From: <$ReceivingEmail>\r\n";
        }
        $headers  .= "Reply-To: {$_POST['email']}\r\n";

        if (mail($ReceivingEmail,$subject,$message,$headers)) {
            if (!empty($thanksPage)) {
                header("Location: $thanksPage");
                exit;
            } else {
                $result = 'Your mail was successfully sent.';
                $disable = true;
            }
        } else {
            $error_msg[] = 'Your mail could not be sent this time.';
        }
    } else {
        $error_msg[] = 'Your mail could not be sent this time.';
    }
}
function get_data($var) {
    if (isset($_POST[$var]))
        echo htmlspecialchars($_POST[$var]);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Whistleblower form</title>

    <style type="text/css">
        p.error, p.success {
            font-weight: bold;
            padding: 10px;
            border: 1px solid;
        }
        p.error {
            background: #ffc0c0;
            color: #900;
        }
        p.success {
            background: #b3ff69;
            color: #4fa000;
        }
    </style>
</head>
<body>

<!--
	Free PHP Mail Form v2.4.5 - Secure single-page PHP mail form for your website
	Copyright (c) Jem Turner 2007-2017
	http://jemsmailform.com/

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	To read the GNU General Public License, see http://www.gnu.org/licenses/.
-->

<?php
if (!empty($error_msg)) {
    echo '<p class="error">ERROR: '. implode("<br />", $error_msg) . "</p>";
}
if ($result != NULL) {
    echo '<p class="success">'. $result . "</p>";
}
?>



<form action="<?php echo basename(__FILE__); ?>" method="post">
    <noscript>
        <p><input type="hidden" name="nojs" id="nojs" /></p>
    </noscript>
    <p>
        <label for="name">Name: *</label>
        <input type="text" name="name" id="name" value="<?php get_data("name"); ?>" /><br />

        <label for="email">E-mail: *</label>
        <input type="text" name="email" id="email" value="<?php get_data("email"); ?>" /><br />

        <label for="comments">Content: </label>
        <textarea name="content" id="content" rows="5" cols="20"><?php get_data("content"); ?></textarea><br />
    </p>
    <p>
        <input type="submit" name="submit" id="submit" value="Send" <?php if (isset($disable) && $disable === true) echo ' disabled="disabled"'; ?> />
    </p>
</form>


</body>
</html>

 }