<?php
/**
 * Created by PhpStorm.
 * User: carlos
 * Date: 8/02/17
 * Time: 13:29
 */
namespace cllorca\hl7messages\Segments;

class OrcSegment extends \yii\base\Model
{
        public $orderControl='RE';
        public $placerOrderNumber;
        public $fillerOrderNumber;//codigo de la vacuna
        public $enteredBy;//Quien  puso la vacuna
        public $orderingProvider;


    public function rules()
    {
        return [
            [['orderControl'], 'required'],

            [['placerOrderNumber', 'fillerOrderNumber','enteredBy','orderingProvider'],'safe']

        ];
    }
}