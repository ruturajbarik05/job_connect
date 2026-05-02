<?php
$urls = [
    '/',
    '/jobs',
    '/companies',
    '/categories',
    '/login',
    '/register',
    '/forgot-password',
    '/jobs/search',
];

foreach ($urls as $url) {
    $ch = curl_init('http://127.0.0.1:8000' . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, false);
    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $status = $code >= 400 ? '*** ERROR ***' : 'OK';
    echo str_pad($url, 30) . " => HTTP $code $status\n";
    
    if ($code >= 400 && $code !== 405) {
        // Extract error message if any
        if (preg_match('/<title>(.*?)<\/title>/s', $response, $m)) {
            echo "   Title: " . trim($m[1]) . "\n";
        }
        if (preg_match('/class="exception-message[^"]*"[^>]*>(.*?)<\/div/s', $response, $m)) {
            echo "   Error: " . trim(strip_tags($m[1])) . "\n";
        }
        // Look for common Laravel error patterns
        if (preg_match('/View \[([^\]]+)\] not found/s', $response, $m)) {
            echo "   Missing View: " . $m[1] . "\n";
        }
        if (preg_match('/Class ["\']([^"\']+)["\'] not found/s', $response, $m)) {
            echo "   Missing Class: " . $m[1] . "\n";
        }
        if (preg_match('/SQLSTATE\[([^\]]+)\]/s', $response, $m)) {
            echo "   SQL Error: " . $m[1] . "\n";
        }
    }
}
