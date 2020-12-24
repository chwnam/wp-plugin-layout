<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_Cron' ) ) {
	class WPPL_Cron implements WPPL_Object {
		/**
		 * Hook name
		 *
		 * @var string
		 */
		public string $hook = '';

		/**
		 * Timestamp to run
		 *
		 * @var int
		 */
		public int $timestamp;

		/**
		 * Interval.
		 *
		 * hourly, twicedaily, daily, weekly
		 *
		 * @var string
		 */
		public string $recurrence = '';

		/**
		 * Argument
		 *
		 * @var array
		 */
		public array $args = [];

		/**
		 * Is this a single event
		 *
		 * @var bool
		 */
		public bool $single_event = false;

		public function __construct(
			string $hook,
			?int $timestamp,
			string $recurrence,
			array $args = [],
			bool $single_event = false
		) {
			$this->hook         = $hook;
			$this->timestamp    = $timestamp ? absint( $timestamp ) : time();
			$this->recurrence   = $recurrence;
			$this->args         = $args;
			$this->single_event = $single_event;
		}

		public function register() {
			if ( $this->hook ) {
				if ( ! wp_next_scheduled( $this->hook ) ) {
					if ( $this->single_event ) {
						wp_schedule_single_event( $this->timestamp, $this->hook, $this->args );
					} else {
						wp_schedule_event( $this->timestamp, $this->recurrence, $this->hook, $this->args );
					}
				}
			}
		}

		public function unregister() {
			if ( $this->hook ) {
				wp_unschedule_hook( $this->hook );
			}
		}
	}
}
