<?php
define('WP_CACHE', true); // Added by WP Rocket
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
define( 'DB_NAME', 'zion-site' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'My_site3610379@2019' );

/** MySQL hostname */
define( 'DB_HOST', '39.100.233.12' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'dqX|s;C9[l%jVQfKj>m^%I>dsPHiS@+Y#Tkh+2*x}%S2_1gZKk]?9OhR(b]?k#t!' );
define( 'SECURE_AUTH_KEY',  'IuA8[ `vZA)V:4HL9E$iv:2S^r)67HGrhoJ6Pf:CJi|4C]=8OlA,P:$yGfJte?0#' );
define( 'LOGGED_IN_KEY',    'X~cYd$5Fv0NUA#WTv.-](%63GS:5~j(SEa,Tc6Ff$:sg#lRB+4eg~P@LI0}#{xdS' );
define( 'NONCE_KEY',        '.>k!<[,L|;{k{]UvRm%7R,#I 9Jm=N&FVGZJsYu-}{:A!}K6zLeaw-ng90Px>2{|' );
define( 'AUTH_SALT',        'h2E$x+3j3I5d(+-aVt@0Sj96m*BX!Abbvr47YuO%yIJ1UC@gzI$!s, _j3xqcw<u' );
define( 'SECURE_AUTH_SALT', 'xg$,o()}$3gH|xsM&0N5qiE;L1:GBOj3`AbG!LJ6vFcu6w!4yRV:U+m$p%]4Cx~Y' );
define( 'LOGGED_IN_SALT',   'O(-qm`ErET8nbL:cpOu:>kXQ0WH:>&Un$ gh,3H{2D 4&-| $FZ5`wM}Z%8jNPU*' );
define( 'NONCE_SALT',       'bajRGbUe!1].I<;8Kr_<O?k&fSGaD#4!&nO*y>y3My1CwIDzN2yf8}LO4N J<RHd' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
