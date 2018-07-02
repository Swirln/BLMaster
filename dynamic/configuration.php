<?php
	// ============== Database =================== \\
	define('DATABASE_USER', 'root');
	define('DATABASE_PASS', '');
	define('DATABASE_NAME', 'blmaster');
	define('DATABASE_HOST', 'localhost');
	define('DATABASE_PORT', 3306);
	// ============== System ===================== \\
	define('DEBUGGING', true);
	define('TIMEZONE', 'America/Chicago');
	// ============== Master Server ============== \\
	define('TIMEOUT', 600); // How many seconds until servers die with no update
	define('SERVER_LIMIT', 3); // How many servers a person can have per server
	define('SERVERNAME_VERSION', true); // If servernames will look like "[v21] Carrot's Building"
?>
