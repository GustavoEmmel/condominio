<?php
trait SystemChangeLogTrait
{
    public function onAfterDelete( $object )
    {
        SystemChangeLog::register($this, $object, array());
    }
    
    public function onBeforeStore($object)
    {
        $this->lastState = array();
        if (self::exists($object->id))
        {
            $this->lastState = parent::load($object->id)->toArray();
        }
    }
    
    public function onAfterStore($object)
    {
        SystemChangeLog::register($this, $this->lastState, (array) $object);
    }
}
