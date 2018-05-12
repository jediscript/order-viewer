<?php
/**
 * @author Jediscript <jed.lagunday@gmail.com>
 */

namespace App\Domain\ValueObject;

use DomainException;

class TrackingNumber
{
    private $id;

    private function __construct($id)
    {
        if (!is_string($id)) {
            throw new DomainException("Tracking number is invalid");
        }

        $this->id = $id;
    }

    /**
     * @param string $string
     * @return TrackingNumber
     */
    public static function fromString(string $string) {
        return new self($string);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->id;
    }
}
