<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Url;

class SidebarWidget extends Widget
{
    public function run()
    {
        return $this->render('sidebar');
    }
}
