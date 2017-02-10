<?php
/**
 * Created by PhpStorm.
 * User: carlos
 * Date: 8/02/17
 * Time: 13:29
 */
namespace cllorca\hl7messages\Segments;

class Nk1Segment extends \yii\base\Model
{
        public $id;
        public $name1;
        public $name2;
        public $relationship;
        public $address1;
        public $address2;
        public $city;
        public $state;
        public $country;
        public $postal_code;
        public $relationship_code;


        public $phone;
        public $email;
        public $publicityCode;
        public static $relation_types=
            [
                'Associate'=>'ASC',
                'Brother'=>'BRO',
                'Care giver'=>'CGV',
                'Child'=>'CHD',
                'Handicapped dependent'=>'DEP',
                'Life partner'=>'DOM',
                'Emergency contact'=>'EMC',
                'Extended family'=>'EXF',
                'Foster Child'=>'FCH',
                'Friend'=>'FND',
                'Father'=>'FTH',
                'Grandchild'=>'GCH',
                'Guardian'=>'GRD',
                'Grandparent'=>'GRP',
                'Mother'=>'MTH',
                'Natural child'=>'NCH',
                'None'=>'NON',
                'Other adult'=>'OAD',
                'Other'=>'OTH',
                'Stepchild'=>'SCH',
                'Self'=>'SEL',
                'Sibling'=>'SIB',
                'Sister'=>'SIS',
                'Spouse'=>'SPO',
                'Unknown'=>'UNK',
                'Ward of court'=>'WRD',
            ];



    public function rules()
    {
        return [
            [['name1','name2','relationship'], 'required'],
            ['email','email'],
            ['relationship','validateRelationship'],
            [['address1','address2','city','state','country','postal_code', 'phone','email','publicityCode','relationship_code'],'safe']
        ];

    }
    public function validateRelationship($attribute){
        $found=false;
        foreach (self::$relation_types as $clave=>$valor){
            if(strtoupper($clave)==strtoupper($this->relationship)){
                $this->relationship_code=$valor;
                $found=true;
                break;
            }

        }
        if(!$found)
            $this->addError($attribute, 'Invalid relation.');

    }

}