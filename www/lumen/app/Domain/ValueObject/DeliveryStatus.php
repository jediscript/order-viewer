<?php
/**
 * @author Jediscript <jed.lagunday@gmail.com>
 */

namespace App\Domain\ValueObject;

use DomainException;

class DeliveryStatus
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
    private $status;

    /**
     * DeliveryStatus constructor.
     * @param string $status
     */
    private function __construct(string $status)
    {
        $constant = strtoupper($status);

        if (!defined('self::' . $constant)) {
            throw new DomainException('Status is invalid');
        }

        $this->status = constant('self::' . $constant);
    }

    /**
     * @param $name
     * @param $arguments
     * @return DeliveryStatus
     */
    public static function __callStatic($name, $arguments)
    {
        return new self($name);
    }

    /**
     * @param string $string
     * @return DeliveryStatus
     */
    public static function fromString(string $string): self
    {
        return new self($string);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->status;
    }
}
