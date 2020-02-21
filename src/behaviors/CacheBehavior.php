<?php

namespace kvmanager\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class CacheBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => [$this->owner, 'cleanCache'],
            ActiveRecord::EVENT_AFTER_UPDATE => [$this->owner, 'cleanCache'],
            ActiveRecord::EVENT_AFTER_DELETE => [$this->owner, 'cleanCache'],
        ];
    }
}
