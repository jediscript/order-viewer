<?php
/**
 * @author Jediscript <jed.lagunday@gmail.com>
 */

namespace App\Domain\ValueObject;

use DomainException;

class OrderId
{
    private $id;

    private function __construct($id)
    {
        if (!is_numeric($id)) {
            throw new DomainException('Order ID is invalid');
        }

        $this->id = $id;
    }

    /**
     * @param int $id
     * @return OrderId
     */
    public static function fromInt(int $id)
    {
        return new self($id);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->id;
    }
}
