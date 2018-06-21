<?php

namespace Spekulatius\TimeZones\Model;

use Spekulatius\TimeZones\Task\PopulateTimeZonesTask;
use SilverStripe\ORM\DataObject;

/**
 * This DataObject defines a time zone entry in the database.
 */
class TimeZoneData extends DataObject
{
    /**
     * @var array
     */
    private static $db = [
        'Identifier' => 'Varchar(255)',
        'Region' => 'Varchar(255)',
        'Name' => 'Varchar(255)',
        'Title' => 'Varchar(255)',
    ];

    private static $table_name = 'TimeZoneData';

    /**
     * Defines the format of the title, which is used e.g. for the dropdown.
     *
     * @var string
     */
    public $format = '%Name';

    /**
     * {@inheritdoc}
     */
    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();

        // run the population task if required.
        if (PopulateTimeZonesTask::config()->run_during_dev_build) {
            $task = new PopulateTimeZonesTask();
            $task->up();
        }
    }

    /**
     * Returns the title according to the configuration
     *
     * @return string
     */
    public function prepareTitle()
    {
        $title = $this->format;

        // replace the placeholders with actual data
        foreach (array_keys(self::$db) as $key) {
            $title = str_ireplace('%' . $key, $this->$key, $title);
        }

        return $title;
    }
}
