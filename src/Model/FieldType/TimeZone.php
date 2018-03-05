<?php

namespace Spekulatius\TimeZones\Model\FieldType;

use SilverStripe\ORM\FieldType\DBVarchar;
use Spekulatius\TimeZones\Forms\TimeZoneField;

/**
 * allows to define a timezone field directly in the $db
 */
class TimeZone extends DBVarchar
{
    /**
     * @param string $title
     * @param array $param
     * @return TimeZoneField
     */
    public function scaffoldFormField($title = null, $params = null)
    {
        return new TimeZoneField($this->name, $title);
    }

    /**
     * @param string $name
     * @param int $size
     * @param array $options
     */
    public function __construct($name = null, $size = 255, $options = array())
    {
        parent::__construct($name, $size, $options);
    }
}
