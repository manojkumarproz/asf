<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';
    $config = require 'config/smtp_config.php';

    $from = $_REQUEST['email'] ?? '';
    $name = $_REQUEST['name'] ?? '';
    $subject = $_REQUEST['subject'] ?? '';
    $number = $_REQUEST['number'] ?? '';
    $cmessage = $_REQUEST['message'] ?? '';

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 0; 
        $mail->isSMTP();
        $mail->Host       = $config['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['username'];
        $mail->Password   = $config['password'];
        $mail->SMTPSecure = $config['encryption'];
        $mail->Port       = $config['port'];

        // Recipients
        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($config['username']); // Sending to the company email
        $mail->addReplyTo($from, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "New Contact Form Submission: " . $subject;

        $body = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 20px auto; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
                .header { background-color: #070b11; padding: 25px; text-align: center; border-bottom: 4px solid #ff5f13; }
                .header h1 { color: #fff; margin: 0; font-size: 24px; letter-spacing: 1px; }
                .content { padding: 30px; background-color: #fff; }
                .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #777; border-top: 1px solid #eee; }
                .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                .info-table td { padding: 12px; border-bottom: 1px solid #eee; }
                .info-table td:first-child { width: 100px; font-weight: bold; color: #ff5f13; }
                .message-box { background-color: #f9f9f9; padding: 20px; border-radius: 5px; border-left: 4px solid #ff5f13; margin-top: 20px; white-space: pre-wrap; }
                .label { display: inline-block; padding: 4px 10px; border-radius: 4px; background-color: #ff5f13; color: #fff; font-size: 11px; font-weight: bold; text-transform: uppercase; margin-bottom: 10px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>ASF ENTERPRISES</h1>
                </div>
                <div class='content'>
                    <div class='label'>New Inquiry</div>
                    <h2 style='color: #070b11; margin: 0 0 15px 0;'>Contact Form Submission</h2>
                    <p>You have received a new inquiry from your website contact form.</p>
                    
                    <table class='info-table'>
                        <tr>
                            <td>Name:</td>
                            <td>{$name}</td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><a href='mailto:{$from}' style='color: #1696e7; text-decoration: none;'>{$from}</a></td>
                        </tr>
                        <tr>
                            <td>Phone:</td>
                            <td><a href='tel:{$number}' style='color: #1696e7; text-decoration: none;'>{$number}</a></td>
                        </tr>
                        <tr>
                            <td>Subject:</td>
                            <td>{$subject}</td>
                        </tr>
                    </table>

                    <h3 style='color: #070b11; margin: 25px 0 10px 0; border-bottom: 1px solid #eee; padding-bottom: 5px;'>Message Details</h3>
                    <div class='message-box'>" . htmlspecialchars($cmessage) . "</div>
                </div>
                <div class='footer'>
                    &copy; " . date('Y') . " ASF Enterprises. All rights reserved.<br>
                    This is an automated notification from your website.
                </div>
            </div>
        </body>
        </html>";

        $mail->Body = $body;

        $mail->send();
        echo json_encode(['status' => 'success', 'message' => 'Message has been sent']);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error', 
            'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"
        ]);
    }
?>