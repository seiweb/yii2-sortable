<?php

namespace seiweb\sortable\behaviors;

use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * Behavior for sortable Yii2 GridView widget.
 *
 * For example:
 *
 * ```php
 * public function behaviors()
 * {
 *    return [
 *       'sort' => [
 *           'class' => SortableGridBehavior::className(),
 *           'sortableAttribute' => 'sortOrder',
 *           'scopeAttribute' => null
 *       ],
 *   ];
 * }
 * ```
 *
 */
class SortableGridBehavior extends Behavior
{
    /** @var string database field name for row sorting */
    public $sortableAttribute = 'sort';

    /** @var string|array database field name for defining scope */
    public $scopeAttribute = null;

    public function events()
    {
        return [ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert'];
    }

    public function gridSort($items)
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        if (!$model->hasAttribute($this->sortableAttribute)) {
            throw new InvalidConfigException("Model does not have sortable attribute `{$this->sortableAttribute}`.");
        }

        $newOrder = [];
        $models = [];
        $new_sort_order = 1;
        foreach ($items as $model_id) {
            $models[$model_id] = $model::findOne($model_id);
            $newOrder[$model_id] = $new_sort_order++;
        }
        $model::getDb()->transaction(function () use ($models, $newOrder) {
            foreach ($newOrder as $modelId => $orderValue) {
                /** @var ActiveRecord[] $models */
                $models[$modelId]->updateAttributes([$this->sortableAttribute => $orderValue]);
            }
        });
    }

    public function beforeInsert()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        if (!$model->hasAttribute($this->sortableAttribute)) {
            throw new InvalidConfigException("Invalid sortable attribute `{$this->sortableAttribute}`.");
        }

        if ($this->scopeAttribute !== null && !is_array($this->scopeAttribute))
            $this->scopeAttribute = [$this->scopeAttribute];

        foreach ($this->scopeAttribute as $scopeAttribute)
            if (!$model->hasAttribute($scopeAttribute)) {
                throw new InvalidConfigException("Invalid scope attribute `{$scopeAttribute}`.");
            }

        if ($this->scopeAttribute !== null) {
            $condition = [];
            foreach ($this->scopeAttribute as $scopeAttribute)
                $condition[$scopeAttribute] = $model->{$scopeAttribute};
            $maxOrder = $model->find()->where($condition)->max($model->tableName() . '.' . $this->sortableAttribute);
        } else {
            $maxOrder = $model->find()->max($model->tableName() . '.' . $this->sortableAttribute);
        }
        $model->{$this->sortableAttribute} = $maxOrder + 1;
    }
}
