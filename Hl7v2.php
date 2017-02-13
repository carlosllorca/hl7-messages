<?php

namespace cllorca\hl7messages;
use app\models\ContactForm;
use app\models\LoginForm;
use cllorca\hl7messages\Scenarios\Immunization;
use cllorca\hl7messages\Scenarios\Vaccines;
use cllorca\hl7messages\Segments\Nk1Segment;
use cllorca\hl7messages\Segments\OrcSegment;
use cllorca\hl7messages\Segments\RxaSegment;
use cllorca\hl7messages\Segments\RxrSegment;
use yii\base\ErrorException;
use yii\base\Exception;
use cllorca\hl7messages\Segments\PidSegment;

/**
 * This is just an example.
 */
class Hl7v2 extends \yii\base\Component
{
    private $_hl7Globals = [];
    private $message='';
    private $nk1_count=0;
    public $filename='hl7v2file';
    public $sending_application='SPECIMENSECURE';
    public $sending_facility='';
    public function init()
    {
        $this->_hl7Globals['SEGMENT_SEPARATOR'] = "\n";
        $this->_hl7Globals['SEGMENT_DEFINITION'] = '^~\&';
        $this->_hl7Globals['FIELD_SEPARATOR'] = '|';
        $this->_hl7Globals['NULL'] = '""';
        $this->_hl7Globals['COMPONENT_SEPARATOR'] = '^';
        $this->_hl7Globals['REPETITION_SEPARATOR'] = '~';
        $this->_hl7Globals['ESCAPE_CHARACTER'] = '\\';
        $this->_hl7Globals['SUBCOMPONENT_SEPARATOR'] = '&';
        $this->_hl7Globals['HL7_VERSION'] = '2.6';
    }
    public function writeMessage(){
       $this->header();
       echo $this->message;
    }
    public function getMessage(){
        return $this->message;
    }
    public function generateImmunizationHeader()
    {
        $msg=$this->initArray(15);//MSH tiene 15 espacios.
        $msg[0]='MSH';
        $msg[1]=$this->_hl7Globals['SEGMENT_DEFINITION'];
        $msg[2]=$this->sending_application;
        $msg[3]=$this->sending_facility;
        $msg[6]=date('Ymd');
        $msg[8]='VXU^V04^VXU_V04';
        $msg[9]=\Yii::$app->security->generateRandomString();
        $msg[10]='P';
        $msg[11]='2.5';
        $this->message.= implode($this->_hl7Globals['FIELD_SEPARATOR'], $msg)."\n";
    }
    public function generatePid($patient){
        if (!($patient instanceof PidSegment)) {
            throw new Exception('Patient most be instance of PidSegment');
        }
        if (!$patient->validate())
            return false;
        $arr=$this->initArray(30);
        $arr[0]='PID';
        $arr[3]=$patient->patientId;
        $arr[5]=$patient->patientName2."^".$patient->patientName1;
        $arr[7]=$patient->date_birth;
        $arr[8]=$patient->sex;
        $arr[10]=$patient->race;
        $arr[11]=$patient->address.'^'.$patient->address1.'^'.$patient->city.'^'.$patient->state.'^'.$patient->zipCode.'^'.$patient->country.'^L';
        $str='';
        if($patient->phone&& $patient->phone!='')
        {
            $code=substr($patient->phone,1,3);
            $number=str_replace("-","",substr($patient->phone,5,-1));
            $str='^PRN^^^^'.$code.'^'.$number;
        }
        if($patient->email&& $patient->email!=''){
            $str==""?$str='^NET^^'.$patient->email.'^^^':$str.='~^NET^^'.$patient->email.'^^^';
        }
        $arr[13]=$str;
        $arr[19]=str_replace([' ','(',')','-'],'',$patient->ss_number);
        $arr[22]=$patient->ethnicGroup;
        $arr[23]=$patient->origin;
        $this->message.= implode($this->_hl7Globals['FIELD_SEPARATOR'], $arr)."\n";
        return true;

    }
    public function generateNk1($related){
        if (!($related instanceof Nk1Segment)) {
            throw new Exception('Patient most be instance of Nk1Segment');
        }
        if (!$related->validate())
            return false;
        $this->nk1_count++;
        $arr=$this->initArray(22);
        $arr[0]='NK1';
        $arr[1]=$this->nk1_count;
        $arr[2]=$related->name2."^".$related->name1;
        $arr[3]=$related->relationship_code.'^'.$related->relationship.'^HL70063';
        $arr[4]=$related->address1.'^'.$related->address2.'^'.$related->city.'^'.$related->state.'^'.$related->postal_code.'^'.$related->country.'^L';
        $str='';
        if($related->phone&& $related->phone!='')
        {
            $code=substr($related->phone,1,3);
            $number=str_replace("-","",substr($related->phone,5,-1));
            $str='^PRN^^^^'.$code.'^'.$number;
        }
        if($related->email&& $related->email!=''){
            $str==""?$str='^NET^^'.$related->email.'^^^':$str.='~^NET^^'.$related->email.'^^^';
        }
        $arr[5]=$str;
        $this->message.= implode($this->_hl7Globals['FIELD_SEPARATOR'], $arr)."\n";
        return true;

    }
    public function generateOrc($related){
        if (!($related instanceof OrcSegment)) {
            throw new Exception('Patient most be instance of Nk1Segment');
        }
        if (!$related->validate())
            throw new Exception(json_encode($related->getErrors()));

        $arr=$this->initArray(12);
        $arr[0]='ORC';
        $arr[1]=$related->placerOrderNumber;
        $arr[2]=$related->fillerOrderNumber;
        $arr[10]=$related->enteredBy;


        $this->message.= implode($this->_hl7Globals['FIELD_SEPARATOR'], $arr)."\n";
        return true;

    }
    public function generateRxa($related){
        if (!($related instanceof RxaSegment)) {
            throw new Exception('Patient most be instance of RxaSegment');
        }
        if (!$related->validate())
            throw new Exception(json_encode($related->getErrors()));

        $arr=$this->initArray(21);
        $arr[0]='RXA';
        $arr[1]=$related->subId;
        $arr[2]=$related->subCounter;
        $arr[3]=$related->immunizationDate;
        $arr[5]=$related->cvxVaccineCode."^".$related->cvxVaccineName;
        $arr[6]=$related->vaccineAmmount;
        $arr[7]=$related->vaccineUnit;
        $arr[9]=$related->vaccineAdministrationCode."^".$related->vaccineAdministrationNotes;
        $arr[10]=$related->nurseLastName."^".$related->nurseLastName;
        $arr[11]=$related->facility.'^^^'.$related->facility_npi;//verlo bien en iguana

        $arr[16]=$related->vaccine_exp_date;
        $arr[14]=$related->vaccine_provider;//verlo bien en iguana
        $arr[15]=$related->vaccine_lote;
        $arr[21]='RE';//verlo bien en iguana
        $this->message.= implode($this->_hl7Globals['FIELD_SEPARATOR'], $arr)."\n";
        return true;

    }
    public function generateRxr($related){
        if (!($related instanceof RxrSegment)) {
            throw new Exception('Patient most be instance of RxrSegment');
        }
        if (!$related->validate())
            throw new Exception(json_encode($related->getErrors()));

        $arr=$this->initArray(2);
        $arr[0]='RXR';
        $arr[1]=$related->injection_type_code."^".$related->injection_type."^HL70162";
        $arr[2]=$related->injection_site_code."^".$related->injection_site."^HL70163";
        $this->message.= implode($this->_hl7Globals['FIELD_SEPARATOR'], $arr)."\n";
        return true;

    }
    public function readHl7v2($file){
        $fp = fopen($file, 'r');
        $linea = fgets($fp);
        $arr=$this->stringSplit($linea,$this->_hl7Globals['FIELD_SEPARATOR']);
        if($arr[0]!='MSH'){
            throw new ErrorException('The File Not Start with MSH segment');
        }
        $immunizacion=new Immunization();


        while(!feof($fp)) {

            $linea = fgets($fp);
            if($linea!=''){
                $arr=$this->stringSplit($linea,$this->_hl7Globals['FIELD_SEPARATOR']);

                if($arr[0]=='PID'){
                    $immunizacion->pid=$this->loadPidSegment($arr);

                }elseif($arr[0]=='ORC'){
                    if($immunizacion){
                        $immunizacion->saveVaccine();
                    }
                    $immunizacion->createVaccine();
                    $immunizacion->temp_vaccine->saveSegment($this->loadOrcSegment($arr));

                }elseif($arr[0]=='RXA'){
                    $immunizacion->temp_vaccine->saveSegment($this->loadRxaSegment($arr));
                }elseif($arr[0]=='RXR'){
                    $immunizacion->temp_vaccine->saveSegment($this->loadRxrSegment($arr));
                }else{
                    echo json_encode($arr);
                    throw new ErrorException('Unknown Segment '.$arr[0]);
                }
            }
        }
        fclose($fp);
        unlink($file);
        return $immunizacion;
    }
    private function initArray($length){
        $result=[];
        for ($i=0;$i<$length;$i++){
            array_push($result,'');
        }
        return $result;
    }
    private function header(){
        header("Content-Description: File Transfer");
        header("Content-Type: application/force-download");
        header('Content-Disposition: attachment; filename="'.$this->filename.'.txt"');
        header('Pragma: no-cache');

    }
    private function stringSplit($segment,$fieldSeparator){
        $arr=[];
        $noEnd=true;
        while (strlen($segment)>1&&$noEnd){
            $pos=strpos($segment,$fieldSeparator);
            if(is_int($pos)){
                $field=substr($segment,0,$pos);
                array_push($arr,$field);
                $segment=substr($segment,$pos+1);
            }  else{
                array_push($arr,$segment);
                $segment=null;
                $noEnd=false;
            }
        }

      return $arr;



    }
    private function loadPidSegment($arr){
        $patient=new PidSegment();
        $patient->patientId=$arr[3];
        $result=$this->stringSplit($arr[5],"^");
        $patient->patientName2=$result[0];
        $patient->patientName1=$result[1];
        $patient->date_birth=date('Y-m-d',strtotime($arr[7]));
        $patient->sex=$arr[8];
        $patient->race=$arr[10];
        $result=$this->stringSplit($arr[11],"^");
        $patient->address=$result[0];
        $patient->address1=$result[1];
        $patient->city=$result[2];
        $patient->state=$result[3];
        $patient->zipCode=$result[4];
        $patient->country=$result[5];
        if($arr[13]&&strpos($arr[13],"~")>=0){
            $partes=$this->stringSplit($arr[13],"~");
            for($z=0;$z<count($partes);$z++){
                $result=$this->stringSplit($partes[$z],"^");
                $result[1]=='PRN'?$patient->phone="(".$result[5].") ".$result[6]:$patient->email=$result[3];
            }
        }else if($arr[13]){
            $result=$this->stringSplit($arr[13],"^");
            $result[1]=='PRN'?$patient->phone="(".$result[5].") ".$result[6]:$patient->email=$result[3];
        }
        $patient->ss_number=$arr[19];
        $patient->ethnicGroup=$arr[22];
        $patient->origin=$arr[23];
        return $patient;
    }
    private function loadOrcSegment($arr){
        $related=new OrcSegment();
        $arr[0]='ORC';
        $related->placerOrderNumber=$arr[1];
        $related->fillerOrderNumber=$arr[2];
        $related->enteredBy=$arr[10];
        return $related;
    }
    private function loadRxaSegment($arr){
        $related=new RxaSegment();
        $related->subId=$arr[1];
        $related->subCounter=$arr[2];
        $related->immunizationDate=date('Y-m-d',strtotime($arr[3]));
        $result=$this->stringSplit($arr[5],"^");
        $related->cvxVaccineCode=$arr[5]=$result[0];
        $related->cvxVaccineName=$result[1];
        $related->vaccineAmmount=$arr[6];
        $related->vaccineUnit=$arr[7];
        $result=$this->stringSplit($arr[9],"^");
        $related->vaccineAdministrationCode=$result[0];
        $related->vaccineAdministrationNotes=$result[1];
        $result=$this->stringSplit($arr[10],"^");
        $related->nurseLastName=$result[0];
        $related->nurseLastName==$result[1];
        $result=$this->stringSplit($arr[11],"^");
        $related->facility=$result[0];
        $related->facility_npi=$result[3];
        $related->vaccine_exp_date=date('Y-m-d',strtotime($arr[16]));
        $related->vaccine_provider=$arr[14];
        $related->vaccine_lote=$arr[15];
        return $related;

    }
    private function loadRxrSegment($arr){
        $related=new RxrSegment();
        $result=$this->stringSplit($arr[1],"^");
            $related->injection_type_code=$result[0];
            $related->injection_type=$result[1];
        $result=$this->stringSplit($arr[2],"^");
            $related->injection_site_code=$result[0];
            $related->injection_site=$result[1];
        return $related;

    }
}
