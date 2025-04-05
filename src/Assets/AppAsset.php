<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace App\Assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css',
        '/vendors/simplebar/css/simplebar.css',
        '/css/style.css',
        '/css/examples.css',
        '/css/site.css',
    ];
    public $js = [
        '/js/config.js',
        '/js/color-modes.js',
        '/vendors/@coreui/coreui/js/coreui.bundle.min.js',
        '/vendors/simplebar/js/simplebar.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset'
    ];
}
