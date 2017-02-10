<?php
/**
 * Created by PhpStorm.
 * User: carlos
 * Date: 8/02/17
 * Time: 13:29
 */
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