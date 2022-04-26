<?php

namespace travelsoft\rest;

class TravelLineRest
{
    /**
     * перобразовать периоды проживания в формат сайта
     * @param ?int $minLosVal minLos|minLosArrivalBased
     * @param ?int $maxLosVal maxLos|maxLosArrivalBased
     * @return array|false|null
     */
    static function prepareLifePeriodForSave($minLosVal, $maxLosVal) {
        if (empty($minLosVal) && empty($maxLosVal)) {
            return null;
        } elseif (empty($maxLosVal)) {
            $minLos = $minLosVal;
            if ($minLosVal >= 40) {
                $maxLos = $minLosVal+10;
            } else {
                $maxLos = 40;
            }
        } elseif (empty($minLosVal)) {
            $minLos = 1;
            $maxLos = $maxLosVal;
        } else {
            if ($minLosVal > $maxLosVal) return false;
            $minLos = $minLosVal;
            $maxLos = $maxLosVal;
        }
        return range($minLos, $maxLos);
    }
}