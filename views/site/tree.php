<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 13.02.2019
 * Time: 10:52
 */


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;


use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\ModalForm $model
 */

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ModalForm */


Modal::begin([
    'header' => '<h2>Let me show you GIF</h2>',
    'id' => 'gifmodal'
]);

?>

<?php $form = ActiveForm::begin([
    'id' => 'captcha-form',
]); ?>

<?= $form->field($model, 'captcha', ['template' => '{input}'])->widget(Captcha::className(), [
    'imageOptions' => [
        'id' => 'captcha-image'
    ]]) ?>


<?= Html::submitButton('Continue', ['class' => 'btn btn-success btn-block']) ;

ActiveForm::end();



Modal::end();

$js = <<<JS
 $('form').on('beforeSubmit', function(){
    var data = $(this).serialize();
        $.ajax({
        url: '/web/index.php?r=site/modal',
        type: 'POST',
        data: data,
        success: function(res){            
            $('#captcha-form').hide().parent('.modal-body').append('<img id="theImg" src="' +  res + '" />');            
        },
        error: function(){
            alert('Error!');
        }
     });
     return false;
 });

    $(document).on('hidden.bs.modal','#gifmodal', function () {
        $('#captcha-image').yiiCaptcha('refresh');
        $('#captcha-form').show();
        $('#gifmodal').find('#theImg').remove();
    });
JS;

$this->registerJs($js);

$this->registerCss(
    "
    .infosource__ul{
        list-style: none;
    }
    .infosource__li{
        padding: 10px 0;
    }
    .infosource__li__li{
        border-bottom: 1px solid #eee;       
    }
    .infosource__link{
        display:inline-block;
        color: #fa0000;
    }
    
    ");


        echo Html::ul($tree, [
            'item' => function($item, $index) {
            return Html::tag(
                'li',
                '<a href="" data-toggle="modal" data-target="#gifmodal">'. $index. '</a>' .
                Html::ul($item, [
                    'item' => function($subitem, $subindex){
                    return '<li class="infosource__li__li"><a class="infosource__link" href="" data-toggle="modal" data-target="#gifmodal">'. $subitem. '</a></li>';
                    },
                    'class' => 'infosource__ul'
                ]),
                ['class' => 'infosource__li']
            );
        },
                'class' => 'infosource__ul']
        );

?>



