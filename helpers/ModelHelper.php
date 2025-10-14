<?php

namespace app\helpers;

use Yii;
use yii\helpers\ArrayHelper;

class ModelHelper
{
    public static function createMultiple($modelClass, $multipleModels = [], $indexKey = 'id')
    {
        $model = new $modelClass;
        $formName = $model->formName();
        $post = Yii::$app->request->post($formName);
        $models = [];

        if (!empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, $indexKey, $indexKey));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item[$indexKey]) && !empty($item[$indexKey]) && isset($multipleModels[$item[$indexKey]])) {
                    // Load model yang ada dari multipleModels
                    $models[] = $multipleModels[$item[$indexKey]];
                } else {
                    // Buat model baru jika id tidak ada
                    $models[] = new $modelClass;
                }
            }
        }

        return $models;
    }
}

