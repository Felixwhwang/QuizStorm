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
define('DB_NAME', 'autombq6_quizstorm');

/** MySQL database username */
define('DB_USER', 'autombq6_Felix');

/** MySQL database password */
define('DB_PASSWORD', 'Shalury2mm13!');

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
define('AUTH_KEY',         'Tc=qH^}=!Fq5$++y&>iGaK.PIpO{o$n,x|k4l,lP$7U_Yof+nlVKndeqG]rcqL5Z');
define('SECURE_AUTH_KEY',  't[ Q:.eOH&aw{,I-s>*0:e|GVJ]Jr-?7so3^7US |a<@24FyZ5-u5_QRc6<<7`Bd');
define('LOGGED_IN_KEY',    'r8bq@wL;c1`UqOBTG)7we5R1;s;c?yc`b,XA^ 6b8[[l3xKXb4rWf]nP2([aY>R%');
define('NONCE_KEY',        ';WUn;]/MltgoTIc,HFGG>(^PBPs=u74I4nQQh;{XvF]QK7JJ]B>-6u<h,jGo_2~N');
define('AUTH_SALT',        '7.Mzk*ro7uhHtV%~k~%xl;Y*:,=Vy9.ST#p4x)#R/hFt>N7g@-$9$(`sCTpuSJ)<');
define('SECURE_AUTH_SALT', 'c-mN_y47Fx;Mb>_-V$B(p:J.7Nf[yO3ZaA)^xP.+e;O4TF;jdte8Ll_A(54A9s{K');
define('LOGGED_IN_SALT',   'FX7,EHZclHI]Ogp6-sCWy0RD9<kyBCZD,3&VV]en9PYU^,4[DCSh40jiR*TQkG(.');
define('NONCE_SALT',       'I@l~QJtk P{wkj+A=C]#MdHe8:78vE7}XJMl0RnWr_6z<ed:+S>r#SYlqua#B[mj');

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
