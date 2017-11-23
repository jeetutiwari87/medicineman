<?php
/** Enable W3 Total Cache */



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
define('DB_NAME', 'medicinemanshop');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'SO+)hsE1u8;>pRd2hM)3qNcT{KR@25(b8-bQj<t~*FoML8]0t_^na:.Dat>o]pG.');
define('SECURE_AUTH_KEY',  'Lu[8hB#>m+x^wiG7!{<kzE!X1%hrQzH`yJ-`o[JF6s0@ 81E8Q@O4v]Lf?[JD]7q');
define('LOGGED_IN_KEY',    'L2GfK<l.Q,>PIA?qbc6{2.UGOGADOuJoDet*uRBY4H_4V`dkc<iw*m?AN{Lc6?!*');
define('NONCE_KEY',        'K<x,GW$${5kHDK|mfcY!?$A!Z^TCN$QC/KJq=S99Es?Oei6)Hcb[$lVumwU| 4^.');
define('AUTH_SALT',        '!R<R;.rim@j<k{?NW*Sjjh#-ifE23-trUnv}m@kXa{8d,3bzmC~*ehpM0F6ru{Qg');
define('SECURE_AUTH_SALT', 'MM2RA^icm!C@UF$q+9<&mH8`W@&BX%3=}R3#Tlm{r+^+0;IP!(LI8tqTuA+90 -R');
define('LOGGED_IN_SALT',   'NJhs%+r50eFeA+y/C>Q+=j-/$x6fwZS3RNzu.Ut}HqSQ^.N,&>%8D]EYfx*[$<bQ');
define('NONCE_SALT',       '8) KM^y)&!2P5Xp.HW `cAYPN_6#zosC !J+0=XAlk>*n:x`]BX;a$)#AgEHm/]@');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'md3_';
define('WP_HOME','z/');define('WP_SITEURL','http://localhost/medicinemanshop/');
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
define( 'WP_AUTO_UPDATE_CORE', false );
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
