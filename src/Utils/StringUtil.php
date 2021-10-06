<?php

namespace Fastwf\Core\Utils;

class StringUtil {

    /**
     * Verify the sequence $seq starts with the $start sequence.
     *
     * @param string $seq the sequence
     * @param string $start the start sequence to test
     * @return boolean true when the sequence start with
     */
    public static function startsWith($seq, $start) {
        $index = 0;

        $seqLength = \strlen($seq);
        $startLength = \strlen($start);

        if ($startLength <= $seqLength) {
            while ($index < $startLength) {
                if ($seq[$index] !== $start[$index]) {
                    return false;
                }

                $index++;
            }
        }

        return $index == $startLength;
    }

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
