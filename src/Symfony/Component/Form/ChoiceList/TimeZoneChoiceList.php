<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\ChoiceList;

/**
 * Represents a choice list where each timezone is broken down by continent.
 *
 * @author Bernhard Schussek <bernhard.schussek@symfony.com>
 */
class TimeZoneChoiceList extends DefaultChoiceList
{
    /**
     * Stores the available timezone choices
     * @var array
     */
    protected static $timezones = array();


    /**
     * Returns the timezone choices
     *
     * The choices are generated from the ICU function
     * \DateTimeZone::listIdentifiers(). They are cached during a single request,
     * so multiple timezone fields on the same page don't lead to unnecessary
     * overhead.
     *
     * @return array  The timezone choices
     */
    public function __construct(array $preferredChoices = array())
    {
        if (count(self::$timezones) == 0) {
            foreach (\DateTimeZone::listIdentifiers() as $timezone) {
                $parts = explode('/', $timezone);

                if (count($parts) > 2) {
                    $region = $parts[0];
                    $name = $parts[1].' - '.$parts[2];
                } else if (count($parts) > 1) {
                    $region = $parts[0];
                    $name = $parts[1];
                } else {
                    $region = 'Other';
                    $name = $parts[0];
                }

                if (!isset(self::$timezones[$region])) {
                    self::$timezones[$region] = array();
                }

                self::$timezones[$region][$timezone] = str_replace('_', ' ', $name);
            }
        }

        parent::__construct(self::$timezones, $preferredChoices);
    }
}