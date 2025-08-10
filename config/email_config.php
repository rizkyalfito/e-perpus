<?php
// config/email_config.php

// Konfigurasi SMTP Email - GANTI DENGAN DATA ANDA
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'wulandariulan199@gmail.com'); // GANTI dengan email Anda
define('SMTP_PASSWORD', 'ljgy zume sosh llok');    // GANTI dengan App Password Gmail
define('SMTP_ENCRYPTION', 'tls');

// Pengirim Email
define('FROM_EMAIL', 'wulandariulan199@gmail.com');    // GANTI dengan email Anda  
define('FROM_NAME', 'E-Perpus System');

// Base URL untuk reset link
define('BASE_URL', 'http://localhost/e-perpus/'); // Sesuaikan dengan URL project Anda

// Load PHPMailer - pilih salah satu sesuai instalasi Anda
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    // Jika pakai Composer
    require_once __DIR__ . '/../vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../phpmailer/src/PHPMailer.php')) {
    // Jika download manual ke folder phpmailer
    require_once __DIR__ . '/../phpmailer/src/Exception.php';
    require_once __DIR__ . '/../phpmailer/src/PHPMailer.php';
    require_once __DIR__ . '/../phpmailer/src/SMTP.php';
} else {
    // Coba path lain jika PHPMailer di folder berbeda
    require_once 'phpmailer/src/Exception.php';
    require_once 'phpmailer/src/PHPMailer.php';
    require_once 'phpmailer/src/SMTP.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Fungsi untuk mengirim email reset password
function sendResetPasswordEmail($to_email, $to_name, $username, $token) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_ENCRYPTION;
        $mail->Port       = SMTP_PORT;
        
        // Disable SSL verification for localhost (hanya untuk development)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        // Pengaturan karakter
        $mail->CharSet = 'UTF-8';
        
        // Recipients
        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->addAddress($to_email, $to_name);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Reset Password Admin - E-Perpus';
        
        // Buat reset link
        $reset_link = BASE_URL . "reset_password_form.php?token=$token&username=$username";
        
        // Email HTML Template
        $mail->Body = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 0; padding: 0; 
                    background-color: #f4f4f4; 
                }
                .container { 
                    max-width: 600px; 
                    margin: 20px auto; 
                    background-color: white;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                }
                .header { 
                    background-color: #007bff; 
                    color: white; 
                    padding: 30px 20px; 
                    text-align: center; 
                }
                .content { padding: 30px 20px; }
                .button { 
                    display: inline-block; 
                    padding: 12px 30px; 
                    background-color: #007bff; 
                    color: white !important; 
                    text-decoration: none; 
                    border-radius: 5px; 
                    margin: 20px 0;
                    font-weight: bold;
                }
                .link-box {
                    background-color: #f8f9fa;
                    padding: 15px;
                    border-radius: 5px;
                    word-break: break-all;
                    font-family: monospace;
                    font-size: 12px;
                    margin: 15px 0;
                }
                .warning {
                    background-color: #fff3cd;
                    border: 1px solid #ffeaa7;
                    border-radius: 5px;
                    padding: 15px;
                    margin: 20px 0;
                }
                .footer { 
                    padding: 20px; 
                    text-align: center; 
                    font-size: 12px; 
                    color: #6c757d;
                    background-color: #f8f9fa;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🔒 Reset Password</h1>
                </div>
                <div class='content'>
                    <p>Halo <strong>$to_name</strong>,</p>
                    <p>Anda telah meminta reset password untuk akun admin dengan username: <strong>$username</strong></p>
                    <p>Klik tombol di bawah ini untuk mereset password Anda:</p>
                    <div style='text-align: center;'>
                        <a href='$reset_link' class='button'>🔐 Reset Password Sekarang</a>
                    </div>
                    <p>Atau copy dan paste link berikut di browser Anda:</p>
                    <div class='link-box'>$reset_link</div>
                    
                    <div class='warning'>
                        <h4>⚠️ Perhatian Penting:</h4>
                        <ul>
                            <li>Link ini hanya berlaku selama <strong>1 jam</strong></li>
                            <li>Link hanya dapat digunakan <strong>satu kali</strong></li>
                            <li>Jika Anda tidak meminta reset password, <strong>abaikan email ini</strong></li>
                        </ul>
                    </div>
                </div>
                <div class='footer'>
                    <p>📧 Email ini dikirim otomatis oleh sistem E-Perpus</p>
                    <p>&copy; " . date('Y') . " E-Perpus by FA Team</p>
                </div>
            </div>
        </body>
        </html>";
        
        // Text version untuk email client yang tidak support HTML
        $mail->AltBody = "
Reset Password - E-Perpus System

Halo $to_name,

Anda telah meminta reset password untuk akun admin dengan username: $username

Klik link berikut untuk mereset password Anda:
$reset_link

PERHATIAN:
- Link ini hanya berlaku selama 1 jam
- Link hanya dapat digunakan satu kali  
- Jika Anda tidak meminta reset password, abaikan email ini

E-Perpus System " . date('Y');
        
        $mail->send();
        return true;
        
    } catch (Exception $e) {
        // Log error untuk debugging
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>