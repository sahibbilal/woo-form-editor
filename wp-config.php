<?php
define( 'WP_CACHE', true );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u361859791_Ngpnm' );

/** Database username */
define( 'DB_USER', 'u361859791_GPBv2' );

/** Database password */
define( 'DB_PASSWORD', 'ShqusOGrNB' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'D?&Vrlu3vVCM+d[ZR,?MX/|@0=L2d0W>u<fXiYZBT6Z~>x7Q#YLcR%*@Y=N**lw?' );
define( 'SECURE_AUTH_KEY',   'DJKZn@rz~W=+xMtKsIkbDPPB+CB;m5EjT*KFI!S/ndLPCQu[GrNft33pB*CaUtWn' );
define( 'LOGGED_IN_KEY',     '_0dF|j7^pRPcn*Q^p-=[0|-ruC>iL54DJ.#9^ez~**!T_fqaRnpCQF,WJ&HZ:-]0' );
define( 'NONCE_KEY',         '@|*et> ddd0fIHaouBI9!r+6/&&W1hkN]pV;`F3pqJP]q`i@Drjq`z]Lr|K97G,D' );
define( 'AUTH_SALT',         'S<A1i(h7=C(H++_cy!$8:ecJ.$LsanAzvaZJQGG1|p6Fs>$@pIkKXxte9OS+tJ` ' );
define( 'SECURE_AUTH_SALT',  'nwCjK2pAv}VyM9Pqf=,@+2e+BH0(V6#Wv|qEWYhP1Ok?vG4NH()h] ->nz%%,[Ll' );
define( 'LOGGED_IN_SALT',    'k]&.07umDi{liDW:k6}$@]kzWH4As+Me-?|K5vPkbFk>ZNN)`Bt3_AV|C:4}30(}' );
define( 'NONCE_SALT',        'H6_C8#-CE^o8Tn29LID2@C<k8?K &:AXO:SdYR+5<HMZ%4U;bKTZkhu;!Fq_%|Gq' );
define( 'WP_CACHE_KEY_SALT', 's=B2HlaGOK%#XgtJ02-6})VrE,G0exSnj>.kZuU!{J*@^[.+Xaxm,I$0>6bd`d(Z' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', true );
}

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', '8f23a056158d6b3de3820c21289c7d1d' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
