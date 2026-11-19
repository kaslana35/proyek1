<?php

return [
    // Comma-separated list of allowed IPs/CIDR for admin access.
    // Leave empty to allow all IPs (default).
    // Example: "192.168.1.0/24,10.0.0.5"
    'allowed_ips' => array_filter(array_map('trim', explode(',', env('ADMIN_ALLOWED_IPS', '')))),
];
