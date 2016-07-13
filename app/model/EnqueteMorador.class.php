<?php
/**
 * EnqueteMorador Active Record
 * @author  <your-name-here>
 */
class EnqueteMorador extends TRecord
{
    const TABLENAME = 'enquete_morador';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('enquete_id');
        parent::addAttribute('morador_id');
    }


}
