<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="language" content="en">

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="app-wrapper">

    <header class="app-header">
        <div class="header-inner">
            <div class="logo">
                <span class="logo-icon">⚡</span>
                <?php echo CHtml::encode(Yii::app()->name); ?>
            </div>
            <nav class="main-nav">
                <?php $this->widget('zii.widgets.CMenu', array(
                    'items' => array(
                        array('label' => 'Home', 'url' => array('/site/index')),
                        array('label' => 'Users', 'url' => array('/user/index')),
                    ),
                    'htmlOptions' => array('class' => 'nav-list'),
                )); ?>
            </nav>
        </div>
    </header>

    <main class="app-content">
        <?php if (isset($this->breadcrumbs)): ?>
            <div class="breadcrumb-bar">
                <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                    'separator' => ' <span class="sep">›</span> ',
                    'htmlOptions' => array('class' => 'breadcrumbs'),
                )); ?>
            </div>
        <?php endif ?>

        <?php echo $content; ?>
    </main>

    <footer class="app-footer">
        <p>&copy; <?php echo date('Y'); ?> — Test Task &middot; <?php echo Yii::powered(); ?></p>
    </footer>

</div>

</body>
</html>
