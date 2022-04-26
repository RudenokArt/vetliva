<?php

namespace travelsoft\booking;

/**
 * Конвертер полей сущности бд
 *
 * @author dima
 */
class Converter {
    
    /**
     * Возвращает целое число >= 0
     * @param mixed $value
     * @return int
     */
    public static function toInt ($value) : int {
        return intVal($value);
    }
    
    /**
     * Возвращает строковую переменную с определённым набором тегов
     * @param string $value
     * @return string
     */
    public static function html (string $value) : string {
        $allowTags = array(
            "<p>", "<a>", "<div>", 
            "<span>", "<i>", "<em>",
            "<b>", "<ul>", "<ol>",
            "<li>", "<img>", "<section>", 
            "<article>", "<table>", "<thead>", 
            "<tbody>", "<tfoot>", "<th>",
            "<td>", "<tr>", "<br>",
            "<font>"
            );
        return (string) strip_tags(trim($value), $allowTags);
    }
    
    /**
     * Возвращает сроку экранированную от тегов
     * @param string $value
     * @return string
     */
    public static function text (string $value) : string {
        return (string) strip_tags(trim($value));
    }
    
}
