<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/config/app.php";

loadEnv(__DIR__ . "/.env");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php#contact");
    exit;
}

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$reason = trim($_POST["reason"] ?? "");
$message = trim($_POST["message"] ?? "");

if ($name === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || $reason === "" || $message === "") {
    header("Location: index.php#contact?status=invalid");
    exit;
}

$mail = new PHPMailer(true);
$receiver = $email;

try {
    $mail->isSMTP();
    $mail->Host = "mail.faylabs.my.id";
    $mail->SMTPAuth = true;
    $mail->Username = "admin@faylabs.my.id";
    $mail->Password = $_ENV["EMAIL_PASSWORD"] ?? getenv("EMAIL_PASSWORD");
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom("admin@faylabs.my.id", "Faylabs Contact");
    $mail->addAddress($receiver);
    $mail->addReplyTo($email, $name);

    $safeName = htmlspecialchars($name, ENT_QUOTES, "UTF-8");
    $safeEmail = htmlspecialchars($email, ENT_QUOTES, "UTF-8");
    $safeReason = htmlspecialchars($reason, ENT_QUOTES, "UTF-8");
    $safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, "UTF-8"));

    $mail->isHTML(true);
    $mail->Subject = "New Contact Message: {$reason}";
    $mail->Body = "<h3>New Contact Message</h3><p><strong>Name:</strong> {$safeName}</p><p><strong>Email:</strong> {$safeEmail}</p><p><strong>Reason:</strong> {$safeReason}</p><p><strong>Message:</strong><br>{$safeMessage}</p>";
    $mail->AltBody = "Name: {$name}\nEmail: {$email}\nReason: {$reason}\n\nMessage:\n{$message}";

    $mail->send();
    header("Location: index.php#contact?status=sent");
    exit;
} catch (Exception $e) {
    file_put_contents(__DIR__ . "/send-email.log", "Email gagal dikirim: " . $mail->ErrorInfo . PHP_EOL, FILE_APPEND);
    header("Location: index.php#contact?status=failed");
    exit;
}
