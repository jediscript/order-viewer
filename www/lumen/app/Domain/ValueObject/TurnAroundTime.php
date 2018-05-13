<?php
/**
 * @author Jediscript <jed.lagunday@gmail.com>
 */

namespace App\Domain\ValueObject;

use DateTime;
use DomainException;

class TurnAroundTime
{
    const CONFIRMED = 'confirmed';
    const DELIVERED = 'delivered';
    const FAILED_DELIVERY = 'failed_delivery';
    const FOR_PICKUP = 'for_pickup';
    const IN_TRANSIT = 'in_transit';
    const PENDING = 'pending';
    const PICKED_UP = 'picked_up';
    const RETURNED = 'returned';

    /**
     * @var string
     */
    private $tat;

    /**
     * @var DateTime
     */
    private $timeStamp;

    private function __construct(string $tat, DateTime $timeStamp)
    {
        $constant = strtoupper($tat);

        if (!defined('self::' . $constant)) {
            throw new DomainException('TAT is invalid');
        }

        $this->tat = constant('self::' . $constant);
        $this->timeStamp = $timeStamp;
    }

    public static function __callStatic($name, $arguments)
    {
        return new self($name, $arguments);
    }

    public static function fromStringAndTimestamp(string $string, DateTime $timestamp): self
    {
        return new self($string, $timestamp);
    }

    public function __toString()
    {
        return (string)$this->timeStamp . ' ' . (string)$this->tat;
    }

    /**
     * @return string
     */
    public function getTat(): string
    {
        return $this->tat;
    }

    /**
     * @return DateTime
     */
    public function getTimeStamp(): DateTime
    {
        return $this->timeStamp;
    }
}
