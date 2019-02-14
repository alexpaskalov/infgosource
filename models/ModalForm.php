<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 13.02.2019
 * Time: 13:32
 */
namespace app\models;

class ModalForm extends \yii\base\Model
{
    public $captcha;

    public function rules()
    {
        $rules[] = ['captcha', 'required'];
        $rules[] = ['captcha', 'captcha'];
        return $rules;
    }
}