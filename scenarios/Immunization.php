<?php
namespace cllorca\hl7messages\Scenarios;
use yii\base\Model;
use yii\bootstrap\ActiveForm;
use yii\db\ActiveRecord;
use cllorca\hl7messages\Segments;
use cllorca\hl7messages\Scenarios;
use cllorca\hl7messages\Segments\PidSegment;
use cllorca\hl7messages\Segments\Nk1Segment;
use cllorca\hl7messages\Scenarios\Vaccines;
/**
 * @property PidSegment $pid 
 * @property Nk1Segment $nk1[] 
 * @property Vaccines $vaccines[]
 * @property Vaccines $temp_vaccine
 *
 */
class Immunization extends Model
{
    public $pid;
    public $nk1=[];
    public $vaccines=[];    
    public $temp_vaccine;
    public function saveVaccine(){
        array_push($this->vaccines,$this->temp_vaccine);
    }
    /**
     * @inheritdoc
     */
    public function temp_Vaccine(){
        return $this->temp_vaccine;
    }
    /**
     * @inheritdoc
     */
    public function createVaccine(){
        $this->temp_vaccine=new Vaccines();
    }
    public function getPid(){
        return $this->hasProperty('pid');
    }
    /**
     * @inheritdoc
     */
    public function setnk1($nk1){
        array_push($this->nk1,$nk1);
    }
};