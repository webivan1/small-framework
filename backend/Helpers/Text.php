<?php
/**
 * Created by PhpStorm.
 * User: maltsev
 * Date: 19.10.2017
 * Time: 14:01
 */

namespace App\Helpers;

class Text
{
    /**
     * Метод лимитирует кол-во символов в переданной строке
     * @param type $text
     * @param type $limit
     * @param type $endText
     * @return type
     */
    public static function extra($text, $limit = 30, $endText = '')
    {
        $textLength = mb_strlen($text, 'UTF-8');
        if ($textLength > $limit) {
            return mb_substr($text, 0, $limit, 'UTF-8') . $endText;
        }

        return $text;
    }

    /**
     * Метод лимитирует кол-во символов в переданной строке и возвращает строку без разрыва слов
     * Алиас extraWord
     * @param string $text
     * @param integer $limit
     * @param string $endText
     * @param boolean $pre
     * @return string
     */
    public static function extraWorld($text, $limit = 30, $endText = '', $pre = false)
    {
        if (mb_strlen($text, 'UTF-8') > $limit) {
            $string = mb_substr($text, 0, $limit, 'UTF-8');
            $string = rtrim($string, '?!,.-_—');
            $string = trim(mb_substr($string, 0, mb_strrpos($string, ' ', 'UTF-8'), 'UTF-8'));
            if ($pre) {
                $words = explode(' ', $string);
                for ($i = sizeof($words) - 1; $i > 0; $i--) {
                    if (mb_strlen($words[$i], 'UTF-8') <= 3) {
                        unset($words[$i]);
                    } else {
                        break;
                    }
                }

                return trim(implode(' ', $words)) . $endText;
            }

            return trim(mb_substr($string, 0, mb_strrpos($string, ' ', 'UTF-8'), 'UTF-8') . $endText);
        }

        return $text;
    }

    public static function extraWord($text, $limit = 30, $endText = '', $pre = false)
    {
        return self::extraWorld($text, $limit, $endText, $pre);
    }

    /**
     * Метод лимитирует кол-во символов в переданной строке до точки
     *
     * @param type $text
     * @param type $limit
     * @param type $endText
     * @return type
     */
    public static function crop($text, $limit = 30, $endText = '')
    {
        if (mb_strlen($text, 'UTF-8') >= $limit) {
            $string = mb_substr($text, 0, $limit, 'UTF-8');
            return trim(mb_substr($string, 0, mb_strrpos($string, '.', 'UTF-8') + 1, 'UTF-8') . $endText);
        } else {
            return $text;
        }
    }

    /**
     * Text::declensionWords(1, array('стройка', 'стройки', 'строек'))
     * // стройка
     * @param $number
     * @param array $suffix
     * @return mixed
     */
    public static function declensionWords($number, $suffix = [])
    {
        $keys = [2, 0, 1, 1, 1, 2];
        $mod = $number % 100;
        $suffix_key = ($mod > 7 && $mod < 20) ? 2 : $keys[min($mod % 10, 5)];
        return $suffix[$suffix_key];
    }

    /**
     * Метод возвращает содержимое первого абзаца с вырезанными тегами
     * @param type $text
     * @return type
     */
    public static function getFirstP($text)
    {
        return strip_tags(strstr($text, '</p>', true), '<a>');
    }

    public static function getWithTagsFirstP($text)
    {
        return str_replace(['</p>', '</p>'], '', strstr($text, '</p>', true));
    }

    /**
     * Метод возвращает содержимое без первого абзаца
     * @param type $text
     * @return type
     */
    public static function cutFirstP($text)
    {
        return strstr(strstr($text, '</p>'), '<p');
        //preg_match('#\<p\>(.*?)\<\/p\>#is', $text, $matches);
        //var_dump($matches);
        //return $matches[0];
    }

    /**
     * Метод возвращает приведенную к нужному представлению цену
     * @param type $text
     * @return type
     */
    public static function convertBasePrice($price)
    {
        if (strlen($price) < 7) {
            $pr = substr($price, 0, 3) . ' тыс ';
        } elseif (strlen($price) == 7) {
            $pr = substr(substr($price, 0, 1) . ',' . substr($price, 1), 0, 4) . ' млн ';
        } else {
            $pr = substr(substr($price, 0, 2) . ',' . substr($price, 2), 0, 5) . ' млн ';
        }
        return $pr;
    }

    /**
     * Метод добавляет гет параметры в фильтрах
     * ДОПИСАТЬ ПРОВЕРКУ НА МАССИВ И ПЕРЕНЕСТИ МЕТОД В КЛАСС PATH
     * @param array $param
     * @return array
     *
     *  !!! НЕ ИСПОЛЬЗУЕТСЯ !!!
     *
     */
    /* function addQueryString( $param = array(), $del = array() )
      {
      parse_str(Yii::app()->request->getQueryString(), $arrayQueryString);
      foreach($del as $d)
      unset($arrayQueryString[$d]);
      return array_merge( $arrayQueryString, $param);
      } */

    /**
     * Метод конвертирует русские символы в JSON для дальнейшей записи в БД
     * @param array $array
     * @return json
     */
    public static function jsonEncodeRus($array = [])
    {
        return preg_replace_callback(
            '/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'), json_encode($array)
        );
    }

    /**
     * Метод приводит двумерный масив к виду array('str'=>'str')
     * @param array $array
     * @return array
     */
    public static function arrayKeyToKey($array = [])
    {
        foreach ($array as $key => $item) {
            if (is_integer($key)) {
                $res[$item] = $item;
            } else {
                $res[$key] = $key;
            }
        }

        return $res;
    }

    /**
     *
     * @param type $arr
     * @return string|null
     */
    public static function arrayToStr($arr)
    {
        if (is_array($arr)) {
            $buf = [];
            foreach ($arr as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $val) {
                        $buf[] = $key . '=' . $val;
                    }
                } else {
                    $buf[] = $key . '=' . $value;
                }
            }

            return $buf;
        }

        return null;
    }

    /**
     * Метод добавляет пустой элемент в начало массива (для select)
     * @param array $array
     * @return array
     */
    public static function addEmptyFirstToArray($array = [], $name = '')
    {
        $ar[''] = '';
        foreach ($array as $key => $val) {
            $ar[$key] = $val;
        }

        return $ar;
    }

//    public static function pr( $ar = array() )
//    {
//        echo '<pre>';
//        print_r( $ar );
//        echo '</pre>';
//    }

    public static function isInt($var)
    {
        if (!empty($var) && is_int($var + 0) && $var > 0) {
            return true;
        }

        return false;
    }

    /**
     * Метод преобразует количество комнат в строку
     * @param number
     * @return string
     */
    public static function convertRoom($room, $count)
    {
        if (!empty($room) && is_int($room + 0) && $room > 0) {
            switch ($room) {
                case 1:
                    $str = self::declensionWords($count, ['однушка', 'однушки', 'однушек']);
                    break;

                case 2:
                    $str = self::declensionWords($count, ['двушка', 'двушки', 'двушек']);
                    break;

                case 3:
                    $str = self::declensionWords($count, ['трешка', 'трешки', 'трешек']);
                    break;

                default:
                    $str = self::declensionWords($count, ['многокомнатная', 'многокомнатных', 'многокомнатных']);
                    break;
            }

            return $str;
        }

        return false;
    }

    /**
     *
     * @param type $text
     * @return type
     */
    public static function translate($text)
    {
        $matrix = [
            "й" => "i",
            "ц" => "c",
            "у" => "u",
            "к" => "k",
            "е" => "e",
            "н" => "n",
            "г" => "g",
            "ш" => "sh",
            "щ" => "sh",
            "з" => "z",
            "х" => "h",
            "ъ" => "\'",
            "ф" => "f",
            "ы" => "i",
            "в" => "v",
            "а" => "a",
            "п" => "p",
            "р" => "r",
            "о" => "o",
            "л" => "l",
            "д" => "d",
            "ж" => "zh",
            "э" => "ie",
            "ё" => "e",
            "я" => "ya",
            "ч" => "ch",
            "с" => "s",
            "м" => "m",
            "и" => "i",
            "т" => "t",
            "ь" => "\'",
            "б" => "b",
            "ю" => "yu",
            "і" => "i",
            "ї" => "i",
            "Й" => "I",
            "Ц" => "C",
            "У" => "U",
            "К" => "K",
            "Е" => "E",
            "Н" => "N",
            "Г" => "G",
            "Ш" => "SH",
            "Щ" => "SH",
            "З" => "Z",
            "Х" => "X",
            "Ъ" => "\'",
            "Ф" => "F",
            "Ы" => "I",
            "В" => "V",
            "А" => "A",
            "П" => "P",
            "Р" => "R",
            "О" => "O",
            "Л" => "L",
            "Д" => "D",
            "Ж" => "ZH",
            "Э" => "IE",
            "Ё" => "E",
            "Я" => "YA",
            "Ч" => "CH",
            "С" => "S",
            "М" => "M",
            "И" => "I",
            "Т" => "T",
            "Ь" => "\'",
            "Б" => "B",
            "Ю" => "YU",
            "І" => "I",
            "Ї" => "I",
            "«" => "",
            "»" => "",
            " " => "-",
        ];

        foreach ($matrix as $from => $to) {
            $text = str_replace($from, $to, $text);
        }

//        $text = preg_replace('/[^A-Za-z0-9_\-]/', '', $text);

        return trim($text);
    }

    public static function translateYandex($text, $divider = '-')
    {
        $replaces = [
            "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "jo",
            "ж" => "zh", "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l", "м" => "m",
            "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "u",
            "ф" => "f", "х" => "h", "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "sch",
            "ъ" => "", "ы" => "y", "ь" => "", "э" => "eh", "ю" => "yu", "я" => "ya",
            " " => $divider, "-" => $divider
        ];

        $newString = null;

        $text = preg_replace('/[^[а-яa-z0-9\s\-\_]]*/iu', '', mb_strtolower($text, 'utf8'));

        foreach ($replaces as $ru => $en) {
            $text = str_replace($ru, $en, $text);
        }

        return preg_replace("/\\" . $divider . "{2,}/", '', trim($text, $divider));
    }

    /**
     * @param $value string
     * @return array
     */
    public static function snakeToCamel($value)
    {
        return lcfirst(implode('', array_map('ucfirst', explode('_', $value))));
    }

    /**
     * Сокращает цену до млн. рублей
     * @param number $price
     */
    public static function shortPrice($price, $keyShortStrings = [])
    {
        $keyShortStrings = empty($keyShortStrings) ? [2 => 'тыс', 3 => 'млн', 4 => 'млрд'] : $keyShortStrings;
        $shortPriceArray = explode('.', number_format($price, 0, ',', '.'));

        $shortPrice = strlen($shortPriceArray[0]) >= 3 ? $shortPriceArray[0] : round($shortPriceArray[0] . (
            isset($shortPriceArray[1]) ? '.' . $shortPriceArray[1] : null
            ), 1);

        return [
            'price' => $shortPrice,
            'text' => isset($keyShortStrings[count($shortPriceArray)])
                ? $keyShortStrings[count($shortPriceArray)]
                : null
        ];
    }

    public static function mb_ucfirst($text, $encoding = 'utf8')
    {
        return mb_strtoupper(mb_substr($text, 0, 1, $encoding), $encoding)
            . mb_substr($text, 1, null, $encoding);
    }

    //Передаем знак рубля

    /**
     * @param string $template
     * @return string
     */
    public static function rub($template = 'default')
    {
        if ($template == 'text') {
            $out = 'руб.';
        } else {
            $out = '<span class="rub">&#8381;</span>';
        }
        return $out;

    }

    /**
     * @todo проверить
     */
    public static function removeTrashTags($text)
    {
        return CHtml::decode($text);
        $text = strip_tags(CHtml::decode($text), '<p><a><b><strong><table><tr><td><th><tbody><tfoot>');
        return preg_replace_callback('@<([^\>]+)>((?!.*<[^\>]?>)(^|\r|\n|.)*?)<\/[^\>]+>@', function ($value) {
            $doc = new DOMDocument(1.0, 'utf8');
            $doc->loadHTML(mb_convert_encoding($value[0], 'HTML-ENTITIES', "UTF-8"));
            $element = (new DOMXPath($doc))->query("//$value[1]")->item(0);
            // remove attr
            $element->removeAttribute('style');
            return $doc->saveHTML();
        }, $text);
    }

    public static function strEncode($string)
    {
        return str_rot13(base64_encode($string));
    }

    public static function strDecode($string)
    {
        return base64_decode(str_rot13($string));
    }

    public static function convertToRuSymbols($text)
    {
        return mb_convert_encoding(str_replace([
            'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', '[', ']', '{', '}',
            'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', ';', '\'', ':', '"',
            'z', 'x', 'c', 'v', 'b', 'n', 'm', ',', '.', '/', '<', '>', '?'
        ], [
            'й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ъ', 'х', 'ъ',
            'ф', 'ы', 'в', 'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'э', 'ж', 'э',
            'я', 'ч', 'с', 'м', 'и', 'т', 'ь', 'б', 'ю', '.', 'б', 'ю', '.',
        ], mb_strtolower($text, 'utf8')), 'utf8');
    }
}