<?php
/**
 * This DataObject defines a time zone entry in the database.
 */
class TimeZoneData extends DataObject {
	/**
	 * @var array
	 */
	private static $db = array(
		'Identifier' => 'varchar(255)',
		'Region' => 'varchar(255)',
		'Name' => 'varchar(255)',
		'Title' => 'varchar(255)',
	);

	/**
	 * Defines the format of the title, which is used e.g. for the dropdown.
	 *
	 * @var string
	 */
	public $format = '%Name';

	/**
	 * {@inheritdoc}
	 */
	public function requireDefaultRecords() {
		parent::requireDefaultRecords();

		// run the population task if required.
		if(PopulateTimeZonesTask::config()->run_during_dev_build) {
			$task = new PopulateTimeZonesTask();
			$task->up();
		}
	}

	/**
	 * Returns the title according to the configuration
	 *
	 * @return string
	 */
	public function prepareTitle() {
		$title = $this->format;

		// replace the placeholders with actual data
		foreach (array_keys(self::$db) as $key) {
			$title = str_ireplace('%' . $key, $this->$key, $title);
		}

		return $title;
	}
}
