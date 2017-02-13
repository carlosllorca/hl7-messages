<?php
namespace cllorca\hl7messages\Scenarios;

use cllorca\hl7messages\Segments\OrcSegment;
use cllorca\hl7messages\Segments\RxaSegment;
use cllorca\hl7messages\Segments\RxrSegment;
use yii\base\Exception;
use yii\base\Model;
use cllorca\hl7messages\Segments;
/**
 * This is the model class for HL7 v2 Immunization Scenario.
 *
 * @property OrcSegment $orc
 * @property RxaSegment $rxa
 * @property RxrSegment $rxr
 *
 */
class Vaccines extends Model{
    public $orc;
    public $rxa;
    public $rxr;
    public function saveSegment($segment){
        if ($segment instanceof OrcSegment){
            $this->orc=$segment;
        }else if($segment instanceof RxaSegment){
            $this->rxa=$segment;
        }else if($segment instanceof RxrSegment){
            $this->rxr=$segment;
        }else{
            throw new Exception ('Sorry Object can not match with any attribute of this class');
        }
    }
};