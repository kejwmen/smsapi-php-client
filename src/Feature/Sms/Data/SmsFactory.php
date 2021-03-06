<?php
declare(strict_types=1);

namespace Smsapi\Client\Feature\Sms\Data;

use DateTime;
use stdClass;

/**
 * @internal
 */
class SmsFactory
{
    public function createFromObject(stdClass $object): Sms
    {
        $sms = new Sms();
        $sms->id = $object->id;
        $sms->points = (float)$object->points;
        $sms->status = $object->status;
        $sms->dateSent = new DateTime('@' . $object->date_sent);
        $sms->number = $object->number;
        $sms->idx = $object->idx;

        return $sms;
    }
}
