<?php
/**
 * Provides a field for the time zones.
 *
 * You can either define a list or use the default values provided.
 */
class TimeZoneField extends DropdownField {
	/**
	 * @var array
	 */
	protected $extraClasses = array('dropdown');

	/**
	 * @throws Exception
	 *
	 * @param string $name
	 * @param string $title = null
	 * @param SS_List $source = null
	 * @param string $value = ''
	 * @param Form $form = null
	 * @param string $emptyString = null
	 * @return void
	 */
	public function __construct($name, $title = null, $source = null, $value = '', $form = null, $emptyString = null) {
		// if a source is given it should be a SS_List of TimeZone objects
		if(!is_null($source) && !is_a($source, "SS_List")) {
			throw new Exception("\$source must be null to use the provided values, or an SS_List of TimeZone objects");
			return false;
		}

		// if no source has been defined we assume the default time zones will be used
		if(!$source) $source = TimeZone::get();

		// leave the actual field
		parent::__construct(
			$name,
			$title,
			$source->sort('Title')->map('Identifier', 'Title'),
			$value,
			$form,
			$emptyString
		);
	}
}
