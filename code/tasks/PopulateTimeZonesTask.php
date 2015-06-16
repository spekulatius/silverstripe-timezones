<?php
/**
 * Populates the default values for the time zones
 */
class PopulateTimeZonesTask extends MigrationTask {
	/**
	 * @config
	 *
	 * @var bool
	 */
	private static $run_during_dev_build = true;

	/**
	 * @var string
	 */
	protected $title = "Populate time zones";

	/**
	 * @var string
	 */
	protected $description = "Populates the default values for the time zones";

	/**
	 * replacement values for the time zones string
	 *
	 * @var array
	 */
	protected $replacementMap = array(
		'_' => ' ',
		'St ' => 'St. '
	);

	/**
	 * {@inheritdoc}
	 */
	public function up() {
		// only run this task if there aren't any time zones defined yet
		if (TimeZone::get()->count() == 0) {
			$this->message('Adding new time zone entries.');

			// prepare the information provided by PHP
			foreach (timezone_identifiers_list() as $timezone) {
				// prepare the time zone information
				$timezoneDetails = $timezone;

				// replace some strings to increase the readibility.
				foreach ($this->replacementMap as $old => $new)
					$timezoneDetails = str_replace($old, $new, $timezoneDetails);

				// split the time zone information into the sections
				$timezoneParts = explode('/', $timezoneDetails);

				// adding the new time zone
				$tz = new TimeZone();
				$tz->Identifier = $timezone;
				$tz->Region = $timezoneParts[0];
				$tz->Name = array_pop($timezoneParts);
				$tz->write();
			}

			$this->message('Finished adding new time zone entries.');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function down() {
		// remove the old time zones
		$this->message('Removing old time zone entries.');
		foreach (TimeZone::get() as $tz) $tz->delete();
	}

	/**
	 * prints a message during the run of the task
	 *
	 * @param string $text
	 */
	protected function message($text) {
		if (Controller::curr() instanceof DatabaseAdmin) {
			DB::alteration_message($text, 'obsolete');
		} else {
			Debug::message($text);
		}
	}
}
