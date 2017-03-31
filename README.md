jQuery driven formBuilder that allows for user defined forms
============================================================
This library relies on jQuery formBuilder for user driven custom forms.  It can be used to overwrite a default configuration, or simply extend it.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist enigmatix/yii2-dynamicforms "*"
```

or add

```
"enigmatix/yii2-dynamicforms": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \enigmatix\dynamicforms\AutoloadExample::widget(); ?>```