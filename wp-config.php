<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'aes_mfg');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'K|SHu+-.mA%:{UG<+Zp5|UvGN8Qi{Ul@HU5@Xk/F-)QP{C$Ax/9lDxa!Oi:*g}b3');
define('SECURE_AUTH_KEY',  'CWeFbULg!A$ZM`5y`{ESNbth:Op{nS>W(vc-k2Ze?K3|T+4j_o]Q=fYn9boJc{m`');
define('LOGGED_IN_KEY',    '6{Z4p-4]Lq73=Keiw-o=W?RhJe,frA(+9c.-3u.6Ppl=Piq9veva^}+BDaGPT:w%');
define('NONCE_KEY',        'ro35cW{f&Nij1)xxRMBj=?$evD?j4+OM0&[<Dha=F|NnR+mm.  7xnk0Rcb<0!~1');
define('AUTH_SALT',        '$W]%2..c#n>l&IqHDN-/EWk]+qsU>379~X|`,3#>D^5rr:0qEc,1$cA/~DM1+WHV');
define('SECURE_AUTH_SALT', '-J|rg0S4No/zW+$EnAgKjfGOp9!{mXz]0870.?rAY|l[u4!+0S90T-s+F>N),kA%');
define('LOGGED_IN_SALT',   'f.4bZZHA-HPPYj.|+YW6W+X<E6wN)|c_U$0VJW!#jD}?bJFLGQdoVKUnv>nN`;Fx');
define('NONCE_SALT',       'EG+/2Q>H^w=6M#>/1DY-f%D6~&8RI5+ByOwm)jA4ev}EI1+emCuj=lQ~[-:E:69d');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
