<?php

namespace Hootlex\Moderation;


class Status
{
    const PENDING = 0;
    const APPROVED = 1;
    const REJECTED = 2;
    const POSTPONED = 3;

    /**
     * Converts a status id to status name.
     *
     * @param int $id
     * @throws \Exception
     * @return string
     */
    public static function toName(int $id)
    {
        switch ($id) {
            case self::PENDING:
                return 'pending';
            case self::APPROVED:
                return 'approved';
            case self::REJECTED:
                return 'rejected';
            case self::POSTPONED:
                return 'postponed';
            default:
                throw new \Exception('The moderation status id provided is unknown');
        }
    }

    /**
     * Converts a status name to status id.
     *
     * @param string $name
     * @throws \Exception
     * @return int
     */
    public static function toId(string $name)
    {
        switch ($name) {
            case 'pending':
                return self::PENDING;
            case 'approved':
                return self::APPROVED;
            case 'rejected':
                return self::REJECTED;
            case 'postponed':
                return self::POSTPONED;
            default:
                throw new \Exception('The moderation status name provided is unknown');
        }
    }
}
