<?php
// Router for Vercel deployment to support PHP outside the api directory
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Default to index.php
if ($uri === '/' || $uri === '') {
    $uri = '/index.php';
}

// Determine the target file path
if (strpos($uri, '/admin/') === 0 || $uri === '/admin') {
    $filename = basename($uri);
    // If the path is just /admin or /admin/ or doesn't end in .php, default to index.php
    if ($uri === '/admin' || $uri === '/admin/' || strpos($filename, '.php') === false) {
        $filename = 'index.php';
        $uri = '/admin/index.php';
    }
    $file = dirname(__DIR__) . '/frontend/admin/' . $filename;
} elseif (strpos($uri, '/payment/') === 0 || $uri === '/payment') {
    $filename = basename($uri);
    if ($uri === '/payment' || $uri === '/payment/' || strpos($filename, '.php') === false) {
        $filename = 'index.php';
        $uri = '/payment/index.php';
    }
    $file = dirname(__DIR__) . '/frontend/payment/' . $filename;
} else {
    $filename = basename($uri);
    if (strpos($filename, '.php') === false) {
        $filename = 'index.php';
        $uri = '/index.php';
    }
    $file = dirname(__DIR__) . '/frontend/' . $filename;
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
