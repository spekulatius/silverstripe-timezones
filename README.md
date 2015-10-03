# Time zones module

Provides a reusable model and data of the time zones worldwide. In addition a dropdown field for a time zones selection is included.

## Requirements

* PHP >= 5.2.0
* SilverStripe Framework ~3.1.

## Installation

For the installation you can either download the package, unzip it into your project directory and run dev build manually or run the following commands in your project directory:

```
composer require spekulatius/silverstripe-timezones
./framework/sake dev/build
```

The data will automaticially populated on dev build.

## Provided Data

The provided data comes from PHPs built-in function [`timezone_identifiers_list()`](http://php.net/DateTimeZone.listIdentifiers). Before the data gets saved in the db it will be prepared so its ready to be used.

## Dropdown Field

If you want to use the dropdown field you can simply add a timezone to your db fields:

```
<?php
class MyPage extends Page {
	/**
	 * @var array
	 */
	private static $db = array(
		'UserTimeZone' => 'TimeZone'
	);

	/**
	 * @return FieldList
	 */
	public function getCMSFields() {
		$fields = parent::getCMSFields();

		// ...

		$fields->addFieldToTab(
			'Root.Main',
			TimeZoneField::create(
				'TimeZone',
				'My time zone'
			)
		);

		return $fields;
	}
```

## Format of time zone

You can change the default format of the time zone in the dropdown menu by creating a file `mysite/_config/timezone.yml` and adding e.g.

```
Injector:
  TimeZoneData:
    properties:
      format: '%Name (%Region)'
```

You can use 'Name', 'Region' and 'Identifier' in the string and it will be replaced proper.
