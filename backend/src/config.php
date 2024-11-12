<?php

/** Database */
if (empty(getenv("DB_NAME")) || empty(getenv("DB_USER")) || empty(getenv("DB_PASSWORD")) || empty(getenv("DB_HOST"))) {
	die('Database missing connection variables. Please check environment variables.');
}

define('DB_NAME', getenv("DB_NAME")); // Db name
define('DB_USER', getenv("DB_USER")); // User
define('DB_PASSWORD', getenv("DB_PASSWORD")); // Password
define('DB_HOST', getenv("DB_HOST")); // hostname
define('DB_PORT', getenv("DB_PORT")); // Port

define('JWT_SECRET', getenv("JWT_SECRET"));
define('JWT_ISSUER', getenv("JWT_ISSUER"));


define('BACKEND_URL', getenv("VIRTUAL_HOST"));
define('FRONTEND_URL', getenv("FRONTEND_URL"));