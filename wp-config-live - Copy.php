<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** multiple environments listed here
if ($_SERVER['REMOTE_ADDR']=='127.0.0.1') {
  define('WP_ENV', 'dev');
} else {
  define('WP_ENV', 'staging');
}

if (WP_ENV == 'dev') {
  define('WP_HOME','http://127.0.0.1/minneapolis-heart-institute-foundation');
  define('WP_SITEURL','http://127.0.0.1/minneapolis-heart-institute-foundation');
	// ** MySQL settings - You can get this info from your web host ** //
	/** The name of the database for WordPress */
	define('DB_NAME', 'mhif');

	/** MySQL database username */
	define('DB_USER', 'root');

	/** MySQL database password */
	define('DB_PASSWORD', 'root');

	/** MySQL hostname */
	define('DB_HOST', '127.0.0.1');

	/** Database Charset to use in creating database tables. */
	define('DB_CHARSET', 'utf8mb4');

	/** The Database Collate type. Don't change this if in doubt. */
	define('DB_COLLATE', '');
} else if (WP_ENV == 'staging') {
  define('WP_HOME','http://augeogreercloud.com/minneapolis-heart-institute-foundation');
  define('WP_SITEURL','http://augeogreercloud.com/minneapolis-heart-institute-foundation');
	// ** MySQL settings - You can get this info from your web host ** //
	/** The name of the database for WordPress */
	define('DB_NAME', 'augeogre_mhif');

	/** MySQL database username */
	define('DB_USER', 'augeogre_admin');

	/** MySQL database password */
	define('DB_PASSWORD', '905park');

	/** MySQL hostname */
	define('DB_HOST', 'localhost');

	/** Database Charset to use in creating database tables. */
	define('DB_CHARSET', 'utf8mb4');

	/** The Database Collate type. Don't change this if in doubt. */
	define('DB_COLLATE', '');
}

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'XE9 iX~$`a.{_K.A)vg<|wNAPhA$rLhFHD-5(HFU5l ?rNDbZTj|aj%>)+!Kv.a(');
define('SECURE_AUTH_KEY',  'qgG-]zL:$U-Gp^|E.1bSLWX]$tnrdFOiH>f-L3],!hlU:y>OWV+2Z?H.Nz^*DrPb');
define('LOGGED_IN_KEY',    'Ft1rjdnn2->t5eRHMx?E`dMTCn4S!U@-|esm|%CQh6i{|#ePb5!vs,]VqwF5ZI^3');
define('NONCE_KEY',        'MR{EQ#-:r-v3z,iwb+MwWX85;r^y/i+V$(&e-+d=w.l4We|.z+O3^g2PA`Cf^:hj');
define('AUTH_SALT',        'iuH64^l5K-`gF`Kbec!YEQmk*@d&kmOU^6~Y~-*ms+Tqe*U&Nb|de3|KmU:_yQ;C');
define('SECURE_AUTH_SALT', ';F7=$JFEaDm=-#bg)Cyu@MGoh+0.TKfxa7S,|TSb pq8?T$G.qp1w(AqV-qrP?Mc');
define('LOGGED_IN_SALT',   'OPlGUhBO8yAPQ+o m1q<k8|>ml- %v-i9gKmUv+~O5@LLLtrXd+aMnkqRRPXO_xt');
define('NONCE_SALT',       'k_}$R_=4d`9]YSs73M~#slcp:hRT]`O+-ZA;$|oj*;!?*BSO7b[#CTl[R?X}/tb ');

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
if (WP_ENV == 'dev') {
  define('WP_DEBUG', false);
}
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
