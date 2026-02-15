<?php

/**
 * Quick Email Test Script
 * Run this from command line: php test-email.php
 * 
 * Make sure your .env file has the correct SMTP settings first!
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('This is a test email from your Records Management System.', function ($message) {
        $message->to(env('MAIL_USERNAME')) // Send to yourself for testing
                ->subject('Test Email - Records Management System');
    });
    
    echo "✅ Email sent successfully! Check your inbox.\n";
} catch (\Exception $e) {
    echo "❌ Error sending email: " . $e->getMessage() . "\n";
    echo "\nCommon issues:\n";
    echo "1. Check your .env file has correct SMTP settings\n";
    echo "2. Make sure you're using an App Password (not your regular password)\n";
    echo "3. Verify 2-Factor Authentication is enabled\n";
    echo "4. Check that 'Less secure app access' is not needed (use App Passwords instead)\n";
}

