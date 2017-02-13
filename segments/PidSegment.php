<?php

/**
 * This is the model class for PID segments. If you need add new segment add  a public attribute and use it in 
 * Hl7v2 class. Methods  
 *
 * @property string $patientId
 * @property RxaSegment $rxa
 * @property RxrSegment $rxr
 **/
namespace cllorca\hl7messages\Segments;

class PidSegment extends \yii\base\Model
{
        public $patientId;
        public $patientName1;
        public $patientName2;
        public $date_birth;
        public $sex;
        public $race;
        public $address;
        public $address1;
        public $city;
        public $state;
        public $zipCode;
        public $phone;
        public $email;
        public $ss_number;
        public $country;
        public $ethnicGroup;
        public $origin;

    public function rules()
    {
        return [
            [['patientId', 'patientName1','patientName2','date_birth','sex'], 'required'],
            ['email','email'],
            [['patientId', 'patientName1','patientName2','date_birth',
                'sex','race','address','address1','city','state','country','zipCode','phone',
            'email','ethnicGroup','ss_number','origin'],'safe']

        ];
    }
}