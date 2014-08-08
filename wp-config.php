<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */

/** MySQL database username */

/** MySQL database password */

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
define('FS_METHOD', 'direct');
define('AUTH_KEY',         '-[<lT~2%0} -2^6Qz=.;u<6A<)--P-k*@5r;) fX-eh;ho~d/XHx{412s0_MB+^[');
define('SECURE_AUTH_KEY',  'FJ;E[&+mW_8e03+hCrydtMuo>IS_KW5)Fte)+oJ!G-@q|OKIV4?Gj0Z<d:>Qd7,n');
define('LOGGED_IN_KEY',    'h^e}*!:Rj5!-bUY1&m5<:[,:sQfh/7X9=hg17Svq#Gk8@s-BQ<i0KiGi;v!;Mux~');
define('NONCE_KEY',        'iGBo@,SBd7T#=pq2zr4&nQ|&jb=W;Qh-[>VQDs^jt|8I$4t/;&yv%[<!6Tcp/,xA');
define('AUTH_SALT',        '4~+Kww=}lmeEl/AB%S}j?v*UmR.Q#WfAszqy-}@pqAGQM0/;/;il}%Zk%=v+G=-F');
define('SECURE_AUTH_SALT', '-=d88gaElPC ){iz|YVS8/x+$i^C7A+ghZpp0E0_l-YGA[0VRYOg#9@6TTApZCAn');
define('LOGGED_IN_SALT',   ';S+e2J++OYk3S,/`3&s2kN>|/NK.0UVS&1`MdD*A4>q1Q|hAk}KaJ+@,>T}`bA4K');
define('NONCE_SALT',       '4IDl33cO%9?c[MC;*%Cz#H`Zb5s|?a&7+:yM,e/Ne6LV,M-eexM=9UGD.tQhX2[&');
define('DB_NAME', 'wordpress');
define('DB_USER', 'wordpress');
define('DB_PASSWORD', 'cuIIAURGoM');
require_once(ABSPATH . 'wp-settings.php');
