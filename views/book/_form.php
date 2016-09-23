<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Book */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php
    $authors = \yii\helpers\ArrayHelper::map(\app\models\Author::find()->asArray()->all(), 'id', 'name');
    $options = [];
    foreach ($authors as $id => $name) {
        if (in_array($id, $model->authors)) {
            $options[$id] = [
                'selected' => 'selected',
            ];
        }
    }
    ?>

    <?= $form->field($model, 'authors[]')->dropDownList($authors, [
        'multiple'=>'multiple',
        'options' => $options,
    ]) ?>

    <?= $form->field($model, 'published')->widget(\yii\jui\DatePicker::className(), [
        //'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
