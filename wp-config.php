<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'servicelisting-react' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'D#>T0nw>kRXVKS0J[PDjfH^jo.]%b!4u!n`xmc5|9$lB_-@=VAdCr)8ET~%KHFdT' );
define( 'SECURE_AUTH_KEY',  '~,d@WtYC$3 o<q<: 9(V5I[F%KX.EtX!(C :Ty!7]sE%V!}K3.8?!~M3)fHXx{@}' );
define( 'LOGGED_IN_KEY',    '-]`)_|r:7ZxF9v<cr6 Ov$oAp((Pcr?MYP@J~o*}UeEVPmT}G7nE]AF^+F<v|<RG' );
define( 'NONCE_KEY',        'Xru7,BK5=yqA~_Q|g>.!)JM9X{62w?2Z@3Py_44)BpT}yUNzB^^_ TWM9S@,W,q!' );
define( 'AUTH_SALT',        'Vav;=MU>B*@+66G>X~4pdPgCysHZ57Th,R)%1J:Pg9I!s-+t:1< ibGr2k(b$UQ5' );
define( 'SECURE_AUTH_SALT', 'n>nYV(tLHbW-W}-PQ.*kW7Rz]9mBs(9dbW/;_Md5xn_zq/!$~QID^5#>2G}-&<1|' );
define( 'LOGGED_IN_SALT',   '=+8@e%G +F8q&;h#.,:W~L&=9*N;)9]xc)2qo/#LN0f8l,8kgGS,Wk(~3N& eZG_' );
define( 'NONCE_SALT',       'W;p?rou4OxKjLQhqMWZ]4FbV4SGC~b=Bg<14ytD%@!R-~;@B5&As}hv-w$3zG%b{' );



/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );
define('WP_DEBUG_DISPLAY', false);
define('WP_DEBUG_LOG',false); 
/* Add any custom values between this line and the "stop editing" line. */
define('FS_METHOD', 'direct');





/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
