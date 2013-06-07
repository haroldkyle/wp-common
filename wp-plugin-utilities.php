<?php
/**
 * abstract utilities class of WP-* plugins from hello@petermolnar.eu
 */

if (!class_exists('WP_Plugins_Utilities')) {

	/**
	 * abstract class for common, required functionalities
	 *
	 */
	class WP_Plugins_Utilities {

		/**
		 * Private ctor so nobody else can instance it
		 *
		 */
		private function __construct() { }

		/**
		 * Init singleton
		 */
		public static function Utility() {
			static $inst = null;
			if ($inst === null) {
				$inst = new WP_Plugins_Utilities();
			}
			return $inst;
		}

		/**
		 * sends message to syslog
		 *
		 * @param string $message message to add besides basic info
		 * @param int $log_level [optional] Level of log, info by default
		 *
		 */
		public function log ( $identifier, $message, $log_level = LOG_WARNING ) {

			if ( @is_array( $message ) || @is_object ( $message ) )
				$message = serialize($message);

			//if ( !isset ( $this->options['log'] ) || $this->options['log'] != 1 )
			//	return false;

			if ( function_exists( 'trigger_error' ) ) {
				switch ( $log_level ) {
					case LOG_ERR:
						trigger_error ( $identifier . " " . $message, E_USER_ERROR );
						break;
					//case LOG_WARNING:
					//	trigger_error ( $identifier . " " . $message, E_USER_WARNING );
					//	break;
					default:
						if ( WP_DEBUG == true ) trigger_error ( $identifier . " " . $message, E_USER_NOTICE );
						break;
				}

			}

		}

		/**
		 * replaces http:// with https:// in an url if server is currently running on https
		 *
		 * @param string $url URL to check
		 *
		 * @return string URL with correct protocol
		 *
		 */
		public function replace_if_ssl ( $url ) {
			if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' )
				$_SERVER['HTTPS'] = 'on';

			if ( isset($_SERVER['HTTPS']) && (( strtolower($_SERVER['HTTPS']) == 'on' )  || ( $_SERVER['HTTPS'] == '1' ) ))
				$url = str_replace ( 'http://' , 'https://' , $url );

			return $url;
		}


		//protected function log ( $message, $log_level = LOG_INFO ) {
		//
		//	if ( @is_array( $message ) || @is_object ( $message ) )
		//		$message = serialize($message);
		//
		//	if ( !isset ( $this->options['log'] ) || $this->options['log'] != 1 )
		//		return false;
		//
		//	switch ( $log_level ) {
		//		case LOG_ERR :
		//			if ( function_exists( 'syslog' ) && function_exists ( 'openlog' ) ) {
		//				openlog('wordpress('.$_SERVER['HTTP_HOST'].')',LOG_NDELAY|LOG_PERROR,LOG_SYSLOG);
		//				syslog( $log_level , self::plugin_constant . $message );
		//			}
		//			/* error level is real problem, needs to be displayed on the admin panel */
		//			//throw new Exception ( $message );
		//		break;
		//		default:
		//			if ( function_exists( 'syslog' ) && function_exists ( 'openlog' ) && isset( $this->options['log_info'] ) && $this->options['log_info'] == 1 ) {
		//				openlog('wordpress(' .$_SERVER['HTTP_HOST']. ')', LOG_NDELAY,LOG_SYSLOG);
		//				syslog( $log_level, self::plugin_constant . $message );
		//			}
		//		break;
		//	}
		//
		//}
	}
}
