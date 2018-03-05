# [SilverStripe Time zones FormField module](https://github.com/spekulatius/silverstripe-timezones)

[![Build Status](https://api.travis-ci.org/spekulatius/silverstripe-timezones.svg?branch=master)](https://travis-ci.org/spekulatius/silverstripe-timezones) [![Latest Stable Version](https://poser.pugx.org/spekulatius/silverstripe-timezones/version.svg)](https://github.com/spekulatius/silverstripe-timezones/releases) [![Latest Unstable Version](https://poser.pugx.org/spekulatius/silverstripe-timezones/v/unstable.svg)](https://packagist.org/packages/spekulatius/silverstripe-timezones) [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/spekulatius/silverstripe-timezones.svg)](https://scrutinizer-ci.com/g/spekulatius/silverstripe-timezones?branch=master) [![Total Downloads](https://poser.pugx.org/spekulatius/silverstripe-timezones/downloads.svg)](https://packagist.org/packages/spekulatius/silverstripe-timezones) [![License](https://poser.pugx.org/spekulatius/silverstripe-timezones/license.svg)](https://github.com/spekulatius/silverstripe-timezones/blob/master/license.md)

Provides a reusable model and data of the time zones worldwide. In addition a
dropdown field for a time zones selection is included.

### Requirements

* SilverStripe Framework 4.0

### Installation

For the installation you can either download the package, unzip it into your
project directory and run dev build manually or run the following commands in
your project directory:

```
composer require spekulatius/silverstripe-timezones
./vendor/bin/sake dev/build
```

The data will automatically populated on dev build.

### Provided Data

The provided data comes from PHPs built-in function [`timezone_identifiers_list()`](http://php.net/DateTimeZone.listIdentifiers).
Before the data gets saved in the db it will be prepared so its ready to be
used.

### Dropdown Field

If you want to use the dropdown field you can simply add a timezone to your db
fields:

```
<?php
use Spekulatius\TimeZones\Model\FieldType\TimeZone;
use Spekulatius\TimeZones\Forms\TimeZoneField;

class MyPage extends Page
{
    /**
     * @var array
     */
    private static $db = array(
        'UserTimeZone' => TimeZone::class
    );

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
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
}
```

### Format of time zone

You can change the default format of the time zone in the dropdown menu by
creating a file `mysite/_config/timezone.yml` and adding e.g.

```
SilverStripe\Core\Injector\Injector:
  Spekulatius\TimeZones\Model\TimeZoneData:
    properties:
      format: '%Name (%Region)'
```

You can use 'Name', 'Region' and 'Identifier' in the string and it will be
replaced proper.

    * [Future ideas/development, issues](https://github.com/spekulat/silverstripe-timezones/issues),
    * [Contributing](https://github.com/spekulat/silverstripe-timezones/blob/master/CONTRIBUTING.md),
    * [License](https://github.com/spekulat/silverstripe-timezones/blob/master/license.md)
