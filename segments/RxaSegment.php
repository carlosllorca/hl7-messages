<?php
/**
 * Created by PhpStorm.
 * User: carlos
 * Date: 8/02/17
 * Time: 13:29
 */
namespace cllorca\hl7messages\Segments;

class RxaSegment extends \yii\base\Model
{
        public $subId=0;
        public $subCounter=1;
        public $immunizationDate;
        public $cvxVaccineCode;
        public $cvxVaccineName;
        public $vaccineAmmount=999;//Cantidad;
        public $vaccineUnit;//Cantidad;
        public $vaccineAdministrationNotes='Historical information–source unspecified';
        public $vaccineAdministrationCode;
        public $nurseName;
        public $nurseLastName;
        public $facility;
        public $facility_npi;
        public $vaccine_lote;
        public $vaccine_exp_date;
        public $vaccine_provider;


    public static $administrationNotes_types=
        [
            'New immunization record'=>'00',
            'Historical information–source unspecified'=>'01',
            'Historical information–from school record'=>'07',

        ];
    public function rules()
    {
        return [
            [['subId','subCounter','cvxVaccineCode','vaccineAmmount','vaccineAdministrationNotes'], 'required'],
            [['cvxVaccineCode','vaccineAmmount'], 'integer'],
            [['vaccineAdministrationNotes'], 'validateVaccineAdministrationNotes'],

            [['vaccineAmmount','vaccineUnit','vaccineName','nurseName','nurseLastName','facility','facility_npi'],'safe'],
            [['vaccineAdministrationNotes','vaccineAdministrationCode','vaccine_provider','vaccine_lote' ,'vaccine_exp_date','validateVaccineAdministrationNotes'],'safe'],


        ];
    }
    public function validateVaccineAdministrationNotes($attribute){
        $found=false;
        foreach (self::$administrationNotes_types as $clave=>$valor){
            if(strtoupper($clave)==strtoupper($this->vaccineAdministrationNotes)){
                $this->vaccineAdministrationCode=$valor;
                $found=true;
                break;
            }

        }
        if(!$found)
            $this->addError($attribute, 'Invalid Administration Note.');

    }
}