<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require __DIR__ . "/vendor/autoload.php";
    require __DIR__ . "/config/app.php";

    loadEnv(__DIR__ . "/.env");

    $mail = new PHPMailer(true);
    $receiver = "yuana6898@gmail.com";

    try {
        $mail->isSMTP();
        $mail->Host = "mail.faylabs.my.id";
        $mail->SMTPAuth = true;
        $mail->Username = "admin@faylabs.my.id";
        $mail->Password = $_ENV["EMAIL_PASSWORD"] ?? getenv("EMAIL_PASSWORD");
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('admin@faylabs.my.id', "Faylabs");
        $mail->addAddress($receiver);

        $mail->isHTML(true);
        $mail->Subject = "Test Email";
        $mail->Body = "<h3>Berhasil terkirim</h3>";

        $mail->send();
        file_put_contents("send-email.log", "Email terkirim");
    } catch (Exception $e) {
        file_put_contents("send-email.log", "Email gagal dikirim".$mail->ErrorInfo);
    }
?>