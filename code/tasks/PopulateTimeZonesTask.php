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
	public function run($request) {
		// I think there is a bug in MigrationTask::run()
		// $request->param('Direction') does not is null
		if ($request->getVar('Direction') == 'down') {
			$this->down();
		} else {
			$this->up();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function up() {
		// only run this task if there aren't any time zones defined yet
		if (TimeZone::get()->count() == 0) {
			$this->message('Adding new time zone entries.');

			// prepare the information provided by PHP
			$timezones = DateTimeZone::listIdentifiers();

			foreach ($timezones as $tz) {

				// replace some strings to increase the readibility.
				$tz = str_replace(
					array_keys($this->replacementMap),
					array_values($this->replacementMap),
					$tz
				);

				// split the time zone information into the sections
				$timezoneParts = explode('/', $tz);

				// adding the new time zone
				$tz = new TimeZone();
				$tz->Identifier = $tz;
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
