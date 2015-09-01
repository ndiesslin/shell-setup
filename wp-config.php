<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'mhif');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         ').-;)..xu$ixQ}W8`p,]&-y268e0N<8jLC23kxv|<bn|Gzh ZrPEIKd}_HpK(C2S');
define('SECURE_AUTH_KEY',  'w*/Gq*3G{-~y7+a5+27aU4%Ux,wv?p3u.p)+z`^djk-XEIZoA3dkcKttZ3AJ)9H~');
define('LOGGED_IN_KEY',    'F[It8O*aK]W%rv1z:cXHF8C~I@5j]7$ 5t;Ae6X~l@8b+1S0k8?*B_YsdE-B)vrI');
define('NONCE_KEY',        'rxkQ*N+)v(-uARJb5i`N7p&KgAe09s|X-#/S|z-7/2u8J0~-R=h,B.i-ze`/c Ww');
define('AUTH_SALT',        '>y|e*?*H)eAy:)K}V+-1}FS.H:OL9x.k66Tfsu`ep>Esk^lm?ldUgNLur%lWCqhZ');
define('SECURE_AUTH_SALT', 'Rp|~j4I!O|i*p+crOiw^Ok^|^Q S/#k {qVT|M, M]P2FY=C9Qn&D4|Jk+L?%uKr');
define('LOGGED_IN_SALT',   'kw-.l=CDg5~:/u6MM$!`o-p%qo*)0qjeqW4c(3,pIG+|!5~6KUOGrtOz]g+:q82@');
define('NONCE_SALT',       'm`D5?}C}Ay;ES-$|h&p~70vd]!|le-kl`N1fD7h@vC{.m2B)3^{Tto^vGb6G&cK9');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
require_once(ABSPATH . 'wp-settings.php');
