## 1.安装  
```
composer require yidashi/yii2-uploader:"*"
```
## 2.配置 

配置里添加

```
'controllerMap' => [
    'upload' => 'yidashi\\uploader\\actions\\UploadController',
],
```
## 3.使用

>直接使用
  
  单传
```
<?= \yidashi\uploader\SingleWidget::widget(['name' => 'xxx'])?>
```

多传
```
<?= \yidashi\uploader\MultipleWidget::widget(['name' => 'xxx'])?>
```
>或者在activeForm里使用

单传
  
```
<?= $form->field($model,'attributeName')->widget('yidashi\uploader\SingleWidget'); ?>
```

多传
```
<?= $form->field($model,'attributeName')->widget('yidashi\uploader\MultipleWidget'); ?>
```
