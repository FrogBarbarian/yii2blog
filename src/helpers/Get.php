<?php

declare(strict_types=1);

namespace src\helpers;

use Psr\SimpleCache\InvalidArgumentException;
use Yii;

/**
 * Помогает записывать и получать данные из кеша.
 */
class Get
{
    /**
     * Данные из БД.
     * @param string $object Тип объекта (posts, comments, users, etc.).
     * @param string $param Параметр для сортировки.
     * @param int $sort Тип сортировки.
     * @throws InvalidArgumentException
     */
    public static function data(string $object, string $param = 'id', int $sort = SORT_ASC, bool $useCache = true): array
    {

        $class = explode('_', $object);

        foreach ($class as &$str) {
            $str = ucfirst($str);
        }

        $class = 'app\models\\' . (rtrim(implode('', $class), 's'));


        if ($useCache) {
            $cache = Yii::$app->cache;
            $data = $cache->get($object);

            if ($data === null) {
                $data = $class::find()
                    ->orderBy([$param => $sort])
                    ->all();
                $cache->set($object, $data, BASE_CACHE_TIME);
            }
        } else {
            $data = $class::find()
                ->orderBy([$param => $sort])
                ->all();
        }

        return $data;
    }
}
