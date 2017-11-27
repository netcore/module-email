<?php

return [
    // Emails layout
    'layout'                    => 'emails.layout', // resources/views/emails/layout.blade.php

    // Automated emails template
    'automated_emails_template' => null,

    // Campaign emails template
    'campaign_emails_template'  => null,

    // Regex for replacing user data in email content,
    // For example, [USER_EMAIL]
    'replace_regex'             => '~\[(.*?)\]~s', // [key]
];
