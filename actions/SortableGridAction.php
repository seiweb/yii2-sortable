<?php
namespace seiweb\sortable\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;

/**
 * Action for sortable Yii2 GridView widget.
 *
 * For example:
 *
 * ```php
 * public function actions()
 * {
 *    return [
 *       'sort' => [
 *          'class' => SortableGridAction::className(),
 *          'modelName' => Model::className(),
 *          'postKey'=>'sorting',
 *       ],
 *   ];
 * }
 * ```
 *
 */
class SortableGridAction extends Action
{
    public $modelName;
    public $postKey = 'sorting';

    public function run()
    {
        if (!$items = Yii::$app->request->post($this->postKey)) {
            throw new BadRequestHttpException('Don\'t received POST param `sorting`.');
        }
        \Yii::trace($items);
        /** @var \yii\db\ActiveRecord $model */
        $model = new $this->modelName;
        if (!$model->hasMethod('gridSort')) {
            throw new InvalidConfigException(
                "Not found right `SortableGridBehavior` behavior in `{$this->modelName}`."
            );
        }

        $model->gridSort($items);
    }
}
