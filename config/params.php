<?php

return [
    'adminEmail' => 'admin@yourcompany.com',
    'senderEmail' => 'noreply@yourcompany.com',
    'senderName' => 'Sistem Inventory - Auto Notification',
    
    // SMTP Settings
    'smtpHost' => 'smtp.gmail.com',
    'smtpUsername' => 'diwarnainventory@gmail.com',      // GANTI DENGAN EMAIL ANDA
    'smtpPassword' => 'evlbyaxqmkfzfjba',          // GANTI DENGAN APP PASSWORD
    'smtpPort' => 587,
    'smtpEncryption' => 'tls',
    
    // ROP Notification Recipients
    'ropNotificationEmails' => [
        'christina.josefphine@gmail.com',  // email purchasing
        // 'gudang@gmail.com',   // misalemail gudang
        // 'admin@gmail.com',  // Optional: dll
    ],
    'bsVersion' => '5.x',
];