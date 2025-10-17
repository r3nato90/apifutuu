<?php
define('APP_ROOT', __DIR__ . '/ia_futebol_app');
require_once APP_ROOT . '/includes/config.php';
require_once APP_ROOT . '/includes/db.php';
require_once APP_ROOT . '/includes/functions.php';

$url = $_GET['url'] ?? '';
$route = explode('/', rtrim($url, '/'))[0];
if (empty($route)) { $route = isLoggedIn() ? 'dashboard' : 'login'; }

// Identifica se a rota é do admin
$isAdminRoute = strpos($route, 'admin') === 0;
$pageFile = $isAdminRoute ? (APP_ROOT . '/admin/' . $route . '.php') : (APP_ROOT . '/pages/' . $route . '.php');
if ($route === 'admin') $pageFile = APP_ROOT . '/admin/index.php'; // Rota principal do admin

ob_start();

if (file_exists($pageFile)) {
    require $pageFile;
} else {
    http_response_code(404);
    require APP_ROOT . '/pages/404.php';
}
$pageContent = ob_get_clean();

// Escolhe qual layout renderizar
if ($isAdminRoute && isLoggedIn() && isAdmin()) {
    require APP_ROOT . '/templates/layout_admin.php'; // Renderiza o layout do admin
} else {
    require APP_ROOT . '/templates/layout.php'; // Renderiza o layout normal
}
?>