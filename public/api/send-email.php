<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Method not allowed']);
  exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

function clean($v): string {
  return trim((string)($v ?? ''));
}

$fullName = clean($data['fullName'] ?? '');
$contactNumber = clean($data['contactNumber'] ?? '');
$email = clean($data['email'] ?? '');
$enquiryType = clean($data['enquiryType'] ?? '');
$message = clean($data['message'] ?? '');
$page = clean($data['page'] ?? '');

if ($fullName === '' || strlen($fullName) < 2) {
  http_response_code(400);
  echo json_encode(['error' => 'Full name is required.']);
  exit;
}
if ($contactNumber === '' || strlen($contactNumber) < 7) {
  http_response_code(400);
  echo json_encode(['error' => 'Contact number is required.']);
  exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo json_encode(['error' => 'Valid email is required.']);
  exit;
}
if ($enquiryType === '') {
  http_response_code(400);
  echo json_encode(['error' => 'Enquiry type is required.']);
  exit;
}
if ($message === '' || strlen($message) < 10) {
  http_response_code(400);
  echo json_encode(['error' => 'Message must be at least 10 characters.']);
  exit;
}

/**
 * =========================
 *  Afrihost SMTP Settings
 * =========================
 * You can set these as environment variables on your host.
 * Defaults below are placeholders.
 *
 * Common Afrihost SMTP host is often:
 *   smtp.afrihost.co.za
 * But some setups use:
 *   mail.yourdomain.com
 * Ask Afrihost / check your cPanel/email settings if unsure.
 */
$SMTP_HOST = getenv('AFRIHOST_SMTP_HOST') ?: 'CHANGE_ME_SMTP_HOST';
$SMTP_PORT = (int)(getenv('AFRIHOST_SMTP_PORT') ?: 'CHANGE_ME_SMTP_PORT');

$SMTP_USER = getenv('AFRIHOST_SMTP_USER') ?: 'CHANGE_ME_APP_OR_EMAIL_USERNAME';
$SMTP_PASS = getenv('AFRIHOST_SMTP_PASS') ?: 'CHANGE_ME_APP_OR_EMAIL_PASSWORD';

// Receiver fixed as requested:
$TO_EMAIL = 'CHANGE_ME_TO_EMAIL';

// Sender (From) should typically be your own domain mailbox:
$FROM_EMAIL = $SMTP_USER;
$FROM_NAME  = 'Gcilishe Website';

// Build email
$subject = "New Website Enquiry: {$enquiryType} — {$fullName}";
$bodyText =
  "New website enquiry\n\n" .
  "Full Name: {$fullName}\n" .
  "Contact Number: {$contactNumber}\n" .
  "Email: {$email}\n" .
  "Enquiry Type: {$enquiryType}\n" .
  "Page: {$page}\n\n" .
  "Message:\n{$message}\n";

$boundary = 'bnd_' . bin2hex(random_bytes(8));
$headers =
  "From: {$FROM_NAME} <{$FROM_EMAIL}>\r\n" .
  "Reply-To: {$fullName} <{$email}>\r\n" .
  "To: {$TO_EMAIL}\r\n" .
  "Subject: {$subject}\r\n" .
  "MIME-Version: 1.0\r\n" .
  "Content-Type: text/plain; charset=UTF-8\r\n" .
  "Content-Transfer-Encoding: 8bit\r\n";

$emailData = $headers . "\r\n" . $bodyText . "\r\n";

function smtp_write($sock, string $cmd): void {
  fwrite($sock, $cmd . "\r\n");
}

function smtp_read($sock): string {
  $data = '';
  while (!feof($sock)) {
    $line = fgets($sock, 515);
    if ($line === false) break;
    $data .= $line;
    // If the 4th char is a space, it's the end of the response
    if (isset($line[3]) && $line[3] === ' ') break;
  }
  return $data;
}

function smtp_expect($sock, array $codes): string {
  $resp = smtp_read($sock);
  $code = (int)substr($resp, 0, 3);
  if (!in_array($code, $codes, true)) {
    throw new Exception("SMTP error: expected " . implode('/', $codes) . ", got {$code}. Response: {$resp}");
  }
  return $resp;
}

try {
  $timeout = 15;
  $sock = stream_socket_client(
    "tcp://{$SMTP_HOST}:{$SMTP_PORT}",
    $errno,
    $errstr,
    $timeout,
    STREAM_CLIENT_CONNECT
  );

  if (!$sock) {
    throw new Exception("Could not connect to SMTP server: {$errstr} ({$errno})");
  }

  stream_set_timeout($sock, $timeout);

  // Server greeting
  smtp_expect($sock, [220]);

  // Identify
  $hostName = $_SERVER['SERVER_NAME'] ?? 'localhost';
  smtp_write($sock, "EHLO {$hostName}");
  smtp_expect($sock, [250]);

  // STARTTLS
  smtp_write($sock, "STARTTLS");
  smtp_expect($sock, [220]);

  // Enable TLS encryption
  $cryptoOk = stream_socket_enable_crypto($sock, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
  if ($cryptoOk !== true) {
    throw new Exception("Failed to start TLS encryption.");
  }

  // EHLO again after TLS
  smtp_write($sock, "EHLO {$hostName}");
  smtp_expect($sock, [250]);

  // AUTH LOGIN
  smtp_write($sock, "AUTH LOGIN");
  smtp_expect($sock, [334]);

  smtp_write($sock, base64_encode($SMTP_USER));
  smtp_expect($sock, [334]);

  smtp_write($sock, base64_encode($SMTP_PASS));
  smtp_expect($sock, [235]);

  // MAIL FROM / RCPT TO
  smtp_write($sock, "MAIL FROM:<{$FROM_EMAIL}>");
  smtp_expect($sock, [250]);

  smtp_write($sock, "RCPT TO:<{$TO_EMAIL}>");
  smtp_expect($sock, [250, 251]);

  // DATA
  smtp_write($sock, "DATA");
  smtp_expect($sock, [354]);

  // Send message, end with <CRLF>.<CRLF>
  fwrite($sock, $emailData . "\r\n.\r\n");
  smtp_expect($sock, [250]);

  // QUIT
  smtp_write($sock, "QUIT");
  smtp_expect($sock, [221]);

  fclose($sock);

  echo json_encode(['ok' => true, 'message' => 'Thank you. Your enquiry has been sent.']);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode([
    'error' => 'Email could not be sent. Please try again later.',
    // Comment this out in production if you don’t want debug info:
    'debug' => $e->getMessage()
  ]);
}
