<?php

namespace yidashi\uploader;

use yii\web\AssetBundle;

class AttachmentUploadAsset extends AssetBundle
{

    public $css = [
        'attachment-upload.css'
    ];

    public $js = [
        'attachment-upload.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yidashi\uploader\blueimpFileupload\BlueimpFileuploadAsset'
    ];

    public function init()
    {
        parent::init();
        $this->sourcePath = __DIR__ . '/static';
    }
}
