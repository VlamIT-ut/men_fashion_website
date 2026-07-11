<?php
// Router for Vercel deployment to support PHP outside the api directory
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Default to index.php
if ($uri === '/' || $uri === '') {
    $uri = '/index.php';
}

// Determine the target file path
if (strpos($uri, '/admin/') === 0) {
    $file = dirname(__DIR__) . '/frontend/admin/' . basename($uri);
} elseif (strpos($uri, '/payment/') === 0) {
    $file = dirname(__DIR__) . '/frontend/payment/' . basename($uri);
} else {
    $file = dirname(__DIR__) . '/frontend/' . basename($uri);
}

if (file_exists($file)) {
    // Set SCRIPT_NAME and PHP_SELF to the matched route for correctness
    $_SERVER['PHP_SELF'] = $uri;
    $_SERVER['SCRIPT_NAME'] = $uri;
    
    // Change working directory to the target file's directory so relative includes work
    chdir(dirname($file));
    
    require $file;
} else {
    http_response_code(404);
    echo "404 Not Found: " . htmlspecialchars($uri);
}
?>
