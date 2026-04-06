<?php
/* @var $this UserController */
/* @var $model UserSearch */

$this->pageTitle = Yii::app()->name . ' — Users & Profiles';
$this->breadcrumbs = array('Users');

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css'
);
?>

<div class="grid-header">
    <h1><span class="icon">👥</span> Users & Profiles</h1>
    <div class="grid-actions">
        <button type="button" id="btn-generate" class="btn btn-primary" onclick="generateData()">
            <span class="icon">✨</span> Add 10 mock records
        </button>
        <button type="button" id="btn-refresh" class="btn btn-refresh" onclick="refreshGrid()">
            <span class="refresh-icon">⟳</span> Refresh
        </button>
    </div>
</div>

<div class="grid-info">
    <p>Use the filter fields below each column header to search. The grid updates automatically via AJAX.</p>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'user-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'ajaxUpdate' => 'user-grid',
        'afterAjaxUpdate' => 'js:function(id, data) { attachDatePickers(); }',
        'pager' => array(
                'cssFile' => false,
                'header' => '',
                'firstPageLabel' => '«',
                'lastPageLabel' => '»',
                'prevPageLabel' => '‹',
                'nextPageLabel' => '›',
        ),
        'htmlOptions' => array('class' => 'grid-view custom-grid'),
        'itemsCssClass' => 'items-table',
        'pagerCssClass' => 'custom-pager',
        'summaryCssClass' => 'grid-summary',
        'columns' => array(
                array(
                        'name' => 'id',
                        'headerHtmlOptions' => array('class' => 'col-id'),
                        'htmlOptions' => array('class' => 'col-id'),
                        'filter' => true,
                ),
                array(
                        'name' => 'email',
                        'headerHtmlOptions' => array('class' => 'col-email'),
                        'filter' => CHtml::activeTextField($model, 'email', array(
                                'class' => 'filter-input',
                                'placeholder' => 'Filter email...',
                        )),
                ),
                array(
                        'name' => 'profile_name',
                        'header' => 'Name',
                        'value' => '$data->profile ? $data->profile->name : ""',
                        'filter' => CHtml::activeTextField($model, 'profile_name', array(
                                'class' => 'filter-input',
                                'placeholder' => 'Filter name...',
                        )),
                ),
                array(
                        'name' => 'profile_surname',
                        'header' => 'Surname',
                        'value' => '$data->profile ? $data->profile->surname : ""',
                        'filter' => CHtml::activeTextField($model, 'profile_surname', array(
                                'class' => 'filter-input',
                                'placeholder' => 'Filter surname...',
                        )),
                ),
                array(
                        'name' => 'type',
                        'header' => 'User Type',
                        'value' => 'ucfirst($data->type)',
                        'filter' => CHtml::activeDropDownList($model, 'type',
                                User::getTypeOptions(),
                                array('prompt' => '— All —', 'class' => 'filter-select')
                        ),
                        'htmlOptions' => array('class' => 'col-type'),
                ),
                array(
                        'name' => 'profile_lang',
                        'header' => 'Lang',
                        'value' => '$data->profile ? $data->profile->langLabel : ""',
                        'filter' => CHtml::activeDropDownList($model, 'profile_lang',
                                Profile::getLangOptions(),
                                array('prompt' => '— All —', 'class' => 'filter-select')
                        ),
                        'htmlOptions' => array('class' => 'col-lang'),
                ),
                array(
                        'name' => 'profile_status',
                        'header' => 'Status',
                        'type' => 'raw',
                        'value' => '$data->profile ? \'<span class="badge badge-\' . ($data->profile->status ? "active" : "banned") . \'">\' . $data->profile->statusLabel . \'</span>\' : ""',
                        'filter' => CHtml::activeDropDownList($model, 'profile_status',
                                Profile::getStatusOptions(),
                                array('prompt' => '— All —', 'class' => 'filter-select')
                        ),
                        'htmlOptions' => array('class' => 'col-status'),
                ),
                array(
                        'name' => 'profile_login_at',
                        'header' => 'Last Login',
                        'value' => '$data->profile && $data->profile->login_at ? Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm", $data->profile->login_at) : "Never"',
                        'filter' => CHtml::activeTextField($model, 'profile_login_at', array(
                                'class' => 'filter-input datepicker-input',
                                'placeholder' => 'Pick date...',
                                'id' => 'filter-login-date',
                        )),
                ),
                array(
                        'name' => 'referer',
                        'header' => 'Referer',
                        'type' => 'raw',
                        'value' => '$data->referer ? CHtml::link(parse_url($data->referer, PHP_URL_HOST), $data->referer, array("target"=>"_blank", "class"=>"referer-link")) : \'<span class="text-muted">—</span>\'',
                        'filter' => false,
                ),
                array(
                        'name' => 'created_at',
                        'header' => 'Registered',
                        'value' => 'Yii::app()->dateFormatter->format("dd.MM.yyyy", $data->created_at)',
                        'filter' => false,
                        'htmlOptions' => array('class' => 'col-date'),
                ),
        ),
));
?>

<script>
    function attachDatePickers() {
        jQuery('#filter-login-date').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            onSelect: function (dateText) {
                // Trigger grid filter update
                var grid = jQuery('#user-grid');
                // Need to set the value and trigger a keyup-like action
                jQuery(this).val(dateText);
                // Find the form inside the grid and submit via AJAX
                var settings = grid.yiiGridView.getSettings('user-grid') || {};
                grid.yiiGridView('update', {data: grid.find('.filters input, .filters select').serialize()});
            }
        });
    }

    function refreshGrid() {
        jQuery.fn.yiiGridView.update('user-grid');
    }

    function generateData() {
        var btn = jQuery('#btn-generate');
        var originalText = btn.html();
        btn.html('<span class="icon">⏳</span> Loading...').prop('disabled', true);

        jQuery.ajax({
            url: '<?php echo Yii::app()->createUrl("user/generate"); ?>',
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    refreshGrid();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function () {
                alert('An error occurred during generation.');
            },
            complete: function () {
                btn.html(originalText).prop('disabled', false);
            }
        });
    }

    jQuery(function () {
        attachDatePickers();
    });
</script>
