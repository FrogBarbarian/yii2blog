<?php

declare(strict_types=1);

namespace src\helpers;

use Yii;

/**
 * Помогает получать данные.
 */
class Get
{
    /**
     * Данные из БД.
     * @param string $obj Тип объекта (posts, comments, users, etc.).
     * @param string $param Параметр для сортировки.
     * @param int $sort Тип сортировки.
     */
    public static function data(string $obj, int $sort = SORT_ASC, string $param = 'id', bool $useCache = false): array
    {

        $class = explode('_', $obj);

        foreach ($class as &$str) {
            $str = ucfirst($str);
        }

        $class = 'app\models\\' . (rtrim(implode('', $class), 's'));


        if ($useCache) {
            $cache = Yii::$app->cache;
            $data = $cache->get($obj);

            if ($data === null) {
                $data = $class::find()
                    ->orderBy([$param => $sort])
                    ->all();
                $cache->set($obj, $data, BASE_CACHE_TIME);
            }
        } else {
            $data = $class::find()
                ->orderBy([$param => $sort])
                ->all();
        }

        return $data;
    }
}
