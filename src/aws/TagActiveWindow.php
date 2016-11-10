<?php

namespace luya\admin\aws;

use luya\Exception;
use luya\admin\models\Tag;
use luya\admin\models\TagRelation;
use luya\admin\ngrest\base\ActiveWindow;

/**
 * Create an Active Window where you can assign tags to a row of the underlying table via a ref table.
 *
 * Use $alias to define the headline in the Active Window
 *
 * Usage example of registering the Tag Active Window:
 *
 * ```php
 * $config->aw->load(['class' => '\luya\admin\aws\TagActiveWindow', 'alias' => 'Tags', 'tableName' => self::tableName()]);
 * ```
 *
 * @author Basil Suter <basil@nadar.io>
 */
class TagActiveWindow extends ActiveWindow
{
    /**
     * @var string The name of the module where the active windows is located in order to finde the view path.
     */
    public $module = 'admin';

    /**
     * @var string The icon name from goolges material icon set (https://material.io/icons/)
     */
    public $icon = "view_list";

    public $tableName = null;

    /**
	 * @inheritdoc
	 */
    public function init()
    {
        parent::init();
        
        if ($this->tableName === null) {
            throw new Exception("The Active Window tableName property can not be null.");
        }
    }
    
    /**
     * The default action which is going to be requested when clicking the ActiveWindow.
     *
     * @return string The response string, render and displayed trough the angular ajax request.
     */
    public function index()
    {
        return $this->render('index');
    }

    public function callbackLoadTags()
    {
        return Tag::find()->asArray()->all();
    }

    public function callbackLoadRelations()
    {
        return TagRelation::find()->where(['table_name' => $this->tableName, 'pk_id' => $this->getItemId()])->asArray()->all();
    }

    public function callbackSaveRelation($tagId, $value)
    {
        $find = TagRelation::find()->where(['tag_id' => $tagId, 'table_name' => $this->tableName, 'pk_id' => $this->getItemId()])->one();

        if ($find) {
            TagRelation::deleteAll(['tag_id' => $tagId, 'table_name' => $this->tableName, 'pk_id' => $this->getItemId()]);
            return 0;
        } else {
            $model = new TagRelation();
            $model->setAttributes([
                'tag_id' => $tagId,
                'table_name' => $this->tableName,
                'pk_id' => $this->getItemId(),
            ]);
            $model->insert(false);
            return 1;
        }
    }

    public function callbackSaveTag($tagName)
    {
        $model = Tag::find()->where(['name' => $tagName])->one();

        if ($model) {
            return false;
        }

        $model = new Tag();
        $model->scenario = 'restcreate';
        $model->setAttributes(['name' => $tagName]);
        $model->save(false);

        return $model->id;
    }
}
