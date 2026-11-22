<?php
// The previous age-based password reset was vulnerable to account takeover.
// Do not re-enable it without a verified, single-use and expiring token
// delivered to a controlled recovery channel.
http_response_code(403);
header('Cache-Control: no-store');
header('Content-Type: text/plain; charset=utf-8');
echo 'La recuperación de contraseña está deshabilitada en esta demostración.';
