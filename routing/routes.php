<?php

// Include necessary files
require_once '../scripts/db.php'; // Example database connection file
require_once '../classes/User.php'; // Example User class file

// Define routes using PHP arrays
$routes = [
    '/' => 'index.php',
    '/login' => 'pages/login.php',
    '/logout' => 'pages/logout.php',
    '/profile' => 'pages/profile.php'
];

// Function to resolve current route
function resolveRoute($uri) {
    global $routes;

    // Check if the route exists
    if (array_key_exists($uri, $routes)) {
        return $routes[$uri];
    } else {
        // Default route if not found (e.g., 404 page)
        return 'pages/error_404.php';
    }
}
