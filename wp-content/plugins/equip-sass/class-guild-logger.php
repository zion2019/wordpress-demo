<?php


class Guild_Logger {

	protected $channel;
	protected $logFile;

	public function __construct( $channel, $logFile ) {
		$this->channel = $channel;
		$this->logFile = $logFile;
	}

	public function addRecord( $message ) {
		if ( ! defined( 'WP_DEBUG_LOG' ) || false === WP_DEBUG_LOG ) {
			return false;
		}

		$timezone = new \DateTimeZone( date_default_timezone_get() ?: 'UTC' );
		$ts       = new \DateTime( null, $timezone );
		$ts->setTimezone( $timezone );

		$record = array(
			'message'  => (string) $message,
			'channel'  => $this->channel,
			'datetime' => $ts,
		);

		$record['formatted'] = $this->format( $record );

		$this->write( $record );

		return true;
	}

	protected function format( array $record ) {
		$format = "[%datetime%] %channel%: %message%\n";
		$output = $format;

		foreach ( $record as $var => $val ) {
			if ( false !== strpos( $output, '%' . $var . '%' ) ) {
				$output = str_replace( '%' . $var . '%', $this->normalize( $val ), $output );
			}
		}

		return $output;
	}

	protected function normalize( $data ) {
		$normalized = '';
		if ( is_string( $data ) ) {
			$normalized = $data;
		}

		if ( $data instanceof \DateTime ) {
			$normalized = $data->format( 'Y-m-d H:i:s' );
		}

		return $normalized;
	}

	protected function write( array $record ) {
		$stream = fopen( $this->logFile, 'a' );
		fwrite( $stream, $record['formatted'] );
		fclose( $stream );
	}
}