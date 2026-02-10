<?php

$server   = 'smtp.office365.com';  // Your SMTP server
$port     = 587;                  // Usually 25, 587, or 465 (for SSL)
$timeout  = 30;
$username = 'noreply@rizzyhome.com';   // SMTP username
$password = '@Rizzyhome1';      // SMTP password
$from     = 'noreply@rizzyhome.com';
$to       = 'techbugs06@gmail.com';

$subject = 'Test Email via Raw SMTP';
$message = "Subject: $subject\r\n";
$message .= "From: $from\r\n";
$message .= "To: $to\r\n";
$message .= "Content-Type: text/plain; charset=utf-8\r\n";
$message .= "\r\nTest email for routine testing.";

$socket = fsockopen($server, $port, $errno, $errstr, $timeout);

if (!$socket) {
    echo "❌ Failed to connect: $errstr ($errno)\n";
    echo "Tried with '$username' & '$password'\n";
    exit;
}

fputs($socket, "EHLO $server\r\n");
fputs($socket, "AUTH LOGIN\r\n");
fputs($socket, base64_encode($username) . "\r\n");
fputs($socket, base64_encode($password) . "\r\n");
fputs($socket, "MAIL FROM:<$from>\r\n");
fputs($socket, "RCPT TO:<$to>\r\n");
fputs($socket, "DATA\r\n");
fputs($socket, "$message\r\n.\r\n");
fputs($socket, "QUIT\r\n");

while (!feof($socket)) {
    echo fgets($socket, 512);
}

fclose($socket);
