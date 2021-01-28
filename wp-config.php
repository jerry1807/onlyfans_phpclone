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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'COXyUx6Ko30F' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         ':K9?PTri+/fI~GLJ$0Ns 36]ZY6T#j#y9<BP_S.u>pWKT[T,s8XjrrD:0K N+S&f' );
define( 'SECURE_AUTH_KEY',  'CPj%r/fL;{vl_O?9!O+s~k8pgW8i0r;nJG=g^i9mxK*K2B^L|[IG`>O;JV9B@yoc' );
define( 'LOGGED_IN_KEY',    'vUj)BZ_%[jm+-c^8V~Z{O|]f/X0K1QF[-8i2-)aGjoM;ti/4/X:I6]t qzh6} qe' );
define( 'NONCE_KEY',        'c~2<u/cnOr1Yqj*^KU0&M6VCAS1muuDGs:#kget,f1apOvS*S;:!]tmd{.;(~b8k' );
define( 'AUTH_SALT',        '{XGHvF*SO,FoaV0ITSy(7ZdZ@H!Kn9m4,n*Wj-Qz4ql7zW>pyl>QoH[T!d %G|qI' );
define( 'SECURE_AUTH_SALT', 'm-8DH%$KR{QL9W)4nYa9V$HH1>>IK*e25yVmUR~>_7Tio)l}(N]qW;]E@j@N|5/%' );
define( 'LOGGED_IN_SALT',   ']47$,^g>QM=YP4G7}bd[3&VDn;Y%FE$;h&u}pkPB4TNSt>Gk}4kl_!@tG/<RbIGp' );
define( 'NONCE_SALT',       'vB~y{G7PT4R7,rcFyR$ITz^JgE]5[F|yR4?uXkgq[GIF!Lz:+KAuM2d~DuO}u=!&' );

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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
