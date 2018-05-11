<?php
/**
 * @author Jediscript <jed.lagunday@gmail.com>
 */

namespace App\Domain\ValueObject;

use Exception;

class OrderId
{
    private $id;

    private function __construct($id)
    {
        if (!is_string($id)) {
            throw new Exception("Order ID must be of type string.");
        }

        $this->id = $id;
    }

    public static function fromString($string) {
        return new self($string);
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
