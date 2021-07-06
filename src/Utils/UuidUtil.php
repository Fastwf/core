<?php

namespace Fastwf\Core\Utils;

class UuidUtil {

    /**
     * Verify that the format match the uuid4 format.
     *
     * @return boolean true when the format match
     */
    public static function isUuid($uuid) {
        // Format expected: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
        // x in 0-9a-e
        $uuid = strtolower($uuid);

        $sizes = [8, 4, 4, 4, 12];
        $sizeIndex = 0;

        $expected = $sizes[$sizeIndex];
        $count = 0;

        $index = 0;
        $length = strlen($uuid);
        while ($index < $length && $sizeIndex < 5) {
            $char = $uuid[$index];

            if ($char === '-') {
                // Control the size of sequence before '-' char
                if ($count !== $expected) {
                    return false;
                }

                // Update the expected size and reset count
                $sizeIndex++;

                if ($sizeIndex < 5) {
                    $expected = $sizes[$sizeIndex];
                    $count = 0;
                }
            } else {
                // Control that char is in '0' to '9' and 'a' to 'f'
                $ord = ord($char);
                if (($ord < 48 || 57 < $ord) && ($ord < 97 || 102 < $ord)) {
                    return false;
                }

                $count++;
            }

            $index++;
        }

        // Count the final number of chars
        return $count === $expected && $sizeIndex === 4;
    }

}
