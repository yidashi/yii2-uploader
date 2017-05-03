<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16/7/16
 * Time: 上午1:46
 */

namespace yidashi\uploader\actions;


use yii\web\Controller;

class UploadController extends Controller
{
    public $enableCsrfValidation = false;

    public function actions()
    {
        return [
            'image-upload' => [
                'class' => UploadAction::className(),
            ],
            'file-upload' => [
                'class' => UploadAction::className(),
                'uploadOnlyImage' => false
            ],
            'images-upload' => [
                'class' => UploadAction::className(),
                'multiple' => true,
            ],
            'files-upload' => [
                'class' => UploadAction::className(),
                'uploadOnlyImage' => false,
                'multiple' => true,
            ],

        ];
    }

    public function actionDelete($id)
    {
        //TODO
    }
}