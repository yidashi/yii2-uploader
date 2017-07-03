<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16/7/16
 * Time: 上午11:14
 */

namespace yidashi\uploader\actions;

use Yii;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;


class UploadAction extends Action
{
    public $basePath = '@webroot/upload';

    public $baseUrl = '@web/upload';
    /**
     * @var string Path to directory where files will be uploaded
     */
    public $path = '';

    /**
     * @var string Validator name
     */
    public $uploadOnlyImage = true;

    /**
     * @var string Variable's name that Imperavi Redactor sent upon image/file upload.
     */
    public $uploadParam = 'file';
    /**
     * @var string 参数指定文件名
     */
    public $uploadQueryParam = 'fileparam';

    public $multiple = false;

    /**
     * @var array Model validator options
     */
    public $validatorOptions = [];

    /**
     * @var string Model validator name
     */
    private $_validator = 'image';

    public $deleteUrl = ['/upload/delete'];

    public $callback;

    public $itemCallback;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (Yii::$app->request->get($this->uploadQueryParam)) {
            $this->uploadParam = Yii::$app->request->get($this->uploadQueryParam);
        }
        if ($this->uploadOnlyImage !== true) {
            $this->_validator = 'file';
        }
        $this->basePath = Yii::getAlias($this->basePath);
        $this->baseUrl = Yii::getAlias($this->baseUrl);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isPost) {
            $files = UploadedFile::getInstancesByName($this->uploadParam);
//            p($files);
            if (!$this->multiple) {
                $res = [$this->uploadOne($files[0])];
            } else {
                $res = $this->uploadMore($files);
            }
            $result = [
                'files' => $res
            ];
            if ($this->callback instanceof \Closure) {
                $result = call_user_func($this->callback, $result);
            }
            return $result;
        } else {
            throw new BadRequestHttpException('Only POST is allowed');
        }

    }
    private function uploadMore(array $files) {
        $res = [];
        foreach ($files as $file) {

            $result = $this->uploadOne($file);
            $res[] = $result;
        }
        return $res;
    }
    private function uploadOne(UploadedFile $file)
    {
        try {
            $model = new DynamicModel(compact('file'));
            $model->addRule('file', $this->_validator, $this->validatorOptions)->validate();

            if ($model->hasErrors()) {
                throw new Exception($model->getFirstError('file'));
            } else {
                $fileName = ($this->path ? $this->path . '/' : '') . $file->name;
                $filePath = $this->basePath . ($this->path ? '/' . $this->path : '');
                $fileFullPath = $this->basePath . '/' . $fileName;
                if (!is_dir($filePath)) {
                    FileHelper::createDirectory($filePath);
                }
                $file->saveAs($fileFullPath);
                $result = [
                    'name' => $file->name,
                    'url' => $this->baseUrl . '/' . $fileName,
                    'path' => $fileName,
                    'extension' => $file->extension,
                    'type' => $file->type,
                    'size' => $file->size
                ];
                if ($this->uploadOnlyImage !== true) {
                    $result['filename'] = $file->name;
                }
            }
            if ($this->itemCallback instanceof \Closure) {
                $result = call_user_func($this->itemCallback, $result);
            }
        } catch (Exception $e) {
            $result = [
                'error' => $e->getMessage()
            ];
        }
        return $result;
    }
}
