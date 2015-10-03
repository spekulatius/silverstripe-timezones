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
	protected $title = 'Populate time zones';

	/**
	 * @var string
	 */
	protected $description = 'Populates the default values for the time zones';

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
		// Workaround for bug in MigrationTask::run(). Will be removed later on
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
		if (TimeZoneData::get()->count() == 0) {
			$this->message('Adding new time zone entries.');

			// prepare the information provided by PHP
			$timezones = DateTimeZone::listIdentifiers();

			foreach ($timezones as $timezone) {
				// replace some strings to increase the readibility.
				$tzNice = str_replace(
					array_keys($this->replacementMap),
					array_values($this->replacementMap),
					$timezone
				);

				// split the time zone information into the sections
				$timezoneParts = explode('/', $tzNice);

				// adding the new time zone
				$tz = new TimeZoneData();
				$tz->Identifier = $timezone;
				$tz->Region = $timezoneParts[0];
				$tz->Name = array_pop($timezoneParts);
				$tz->write();
			}

			$this->message('Finished adding new time zone entries.');
		}

		// check if the titles in the dataobjects need to be refreshed
		if ($this->checkIfTitlesNeedRebuild()) $this->rebuildTitles();
	}

	/**
	 * {@inheritdoc}
	 */
	public function down() {
		// remove the old time zones
		$this->message('Removing old time zone entries.');
		foreach (TimeZoneData::get() as $tz) $tz->delete();
	}

	/**
	 * decides if the titles need to be rebuild
	 *
	 * @return boolean
	 */
	protected function checkIfTitlesNeedRebuild() {
		// Assumption is that if the first one ($example) doesn't match (anymore) we need to refresh all.
		$example = TimeZoneData::get()->first();
		return ($example->Title != $example->prepareTitle());
	}

	/**
	 * Rebuilds the title in the dataobjects
	 */
	protected function rebuildTitles() {
		// Update the Title field in the dataobjects. This saves the time to build the title dynamically each time.
		foreach (TimeZoneData::get() as $tz) {
			$newTitle = $tz->prepareTitle();
			if ($newTitle != $tz->Title) {
				$tz->Title = $newTitle;
				$tz->write();
			}
		}
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
