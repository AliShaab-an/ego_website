<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * EmailService - Send emails via SMTP using PHPMailer
 * 
 * Configuration via environment variables:
 * SMTP_HOST, SMTP_PORT, SMTP_USER, SMTP_PASS, SMTP_FROM
 */
class EmailService
{
    /**
     * Send an email
     * 
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $htmlBody HTML body content
     * @param string $textBody Plain text body (optional fallback)
     * @return bool True on success
     * @throws Exception On failure
     */
    public static function send(string $to, string $subject, string $htmlBody, string $textBody = ''): bool
    {
        // Respect the admin settings toggle — if SMTP is disabled, skip silently
        if (!(bool)getSetting('enable_smtp', 0)) {
            Logger::info('Email skipped (SMTP disabled in settings)', "To: $to | Subject: $subject");
            return false;
        }

        $mail = new PHPMailer(true);

        try {
            // SMTP configuration from the settings table (falls back to .env / defaults)
            $smtpHost       = getSetting('smtp_host',       $_ENV['SMTP_HOST'] ?? 'localhost');
            $smtpPort       = (int)getSetting('smtp_port',  $_ENV['SMTP_PORT'] ?? 587);
            $smtpUser       = getSetting('smtp_username',   $_ENV['SMTP_USER'] ?? '');
            $smtpPass       = getSetting('smtp_password',   $_ENV['SMTP_PASS'] ?? '');
            $smtpEncryption = getSetting('smtp_encryption', 'tls');
            $smtpFromEmail  = getSetting('smtp_from_email', '');
            $fromEmail      = filter_var($smtpFromEmail, FILTER_VALIDATE_EMAIL) ? $smtpFromEmail : ($_ENV['SMTP_FROM'] ?? 'noreply@example.com');
            $fromName       = getSetting('smtp_from_name',  getSetting('website_name', 'EGO'));

            $mail->isSMTP();
            $mail->Host     = $smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $smtpUser;
            $mail->Password = $smtpPass;
            $mail->Port     = $smtpPort;

            // Map encryption setting to PHPMailer constant
            if (strtolower($smtpEncryption) === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;   // port 465
            } elseif (strtolower($smtpEncryption) === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // port 587
            } else {
                $mail->SMTPSecure = '';  // none
                $mail->SMTPAuth   = false;
            }

            // Sender
            $mail->setFrom($fromEmail, $fromName);

            // Recipient
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = $textBody ?: strip_tags($htmlBody);

            $mail->send();

            Logger::info('Email sent', "To: $to | Subject: $subject");
            return true;

        } catch (\Exception $e) {
            Logger::error('Email failed', "To: $to | Subject: $subject | Error: " . $mail->ErrorInfo);
            throw new \Exception('Failed to send email: ' . $mail->ErrorInfo);
        }
    }

    /**
     * Render an email template and return the HTML string
     * 
     * @param string $template Template name (e.g., 'password-reset')
     * @param array $data Data to pass to the template
     * @return string Rendered HTML
     */
    public static function renderTemplate(string $template, array $data = []): string
    {
        $templateFile = VIEWS . 'emails/' . $template . '.php';

        if (!file_exists($templateFile)) {
            throw new \Exception("Email template not found: $template");
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $templateFile;
        return ob_get_clean();
    }
}
