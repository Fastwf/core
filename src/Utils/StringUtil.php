<?php

namespace Fastwf\Core\Utils;

class StringUtil {

    /**
     * Verify the sequence end with the end sequence.
     *
     * @param string $seq the sequence
     * @param string $end the end sequence to test
     * @return bool true when the sequence end with
     */
    public static function endsWith($seq, $end) {
        $seqIndex = \strlen($seq) - 1;
        $endIndex = \strlen($end) - 1;

        while ($seqIndex >= 0 && $endIndex >= 0) {
            if ($seq[$seqIndex] !== $end[$endIndex]) {
                return false;
            }

            $seqIndex--;
            $endIndex--;
        }

        return $endIndex === -1;
    }

}
