# Time zones module

Provides a reusable model and data of the time zones worldwide. In addition a dropdownfield for a time zone selection is included.

## Requirements

* PHP >= 5.2.0
* SilverStripe Framework ~3.1.

## Installation

For the installation you can either download, unzip the package and run dev build manually or run the following commands in your root directory:

```
composer require spekulatius/silverstripe-timezones
./framework/sake dev/build
```

The data will automaticially populated on dev build.

## Provided Data

The provided data comes from PHPs built-in function [`timezone_identifiers_list()`](http://php.net/DateTimeZone.listIdentifiers). Before the data gets saved in the db it will be prepared so its ready to be used.

## Dropdown Field

If you want to use the dropdown field you can

```
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
  TimeZone:
    properties:
      format: '%Name (%Region)'
```

You can use 'Name', 'Region' and 'Identifier' in the string and it will be replaced proper.
