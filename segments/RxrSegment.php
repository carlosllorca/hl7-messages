<?php
/**
 * Created by PhpStorm.
 * User: carlos
 * Date: 8/02/17
 * Time: 13:29
 */
namespace cllorca\hl7messages\Segments;

class RxrSegment extends \yii\base\Model
{

        public $injection_type;
        public $injection_type_code;
        public $injection_site;
        public $injection_site_code;
       
    public function rules()
    {
        return [
            [['injection_type','injection_site'], 'required'],
            [['injection_type'], 'validateInjectionType'],
            [['injection_site'], 'validateInjectionSite'],

            [['injection_type_code','injection_type_code'],'safe']
        ];

    }
    public static $injection_type_desc=
        [
            'Intradermal'=>'ID',
            'Intramuscular'=>'IM',
            'Nasal'=>'NS',
            'Intravenous'=>'IV',
            'Oral'=>'PO',
            'Other/Miscellaneous'=>'OTH',
            'Subcutaneous'=>'SC',
            'Transdermal'=>'TD',

        ];
    public static $injection_place_desc=
        [
            'Left Thigh'=>'LT',
            'Left Arm'=>'LA',
            'Left Deltoid'=>'LD',
            'Left Gluteus Medius'=>'LG',
            'Left Vastus Lateralis'=>'LVL',
            'Left Lower Forearm'=>'LLFA',
            'Right Arm'=>'RA',
            'Right Thigh'=>'RT',
            'Right Vastus Lateralis'=>'RVL',
            'Right Gluteus Medius'=>'RG',
            'Right Deltoid'=>'RD',
            'Right Lower Forearm'=>'RLFA',

        ];
    public function validateInjectionType($attribute){
        $found=false;
        foreach (self::$injection_type_desc as $clave=>$valor){
            if(strtoupper($clave)==strtoupper($this->injection_type)){
                $this->injection_type_code=$valor;
                $found=true;
                break;
            }

        }
        if(!$found)
            $this->addError($attribute, 'Invalid Injection Place.');

    }
    public function validateInjectionSite($attribute){
        $found=false;
        foreach (self::$injection_place_desc as $clave=>$valor){
            if(strtoupper($clave)==strtoupper($this->injection_site)){
                $this->injection_site_code=$valor;
                $found=true;
                break;
            }

        }
        if(!$found)
            $this->addError($attribute, 'Invalid Injection Place.');

    }

}