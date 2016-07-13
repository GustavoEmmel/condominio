<?php
/**
 * Status Active Record
 * @author  <your-name-here>
 */
class Status extends TRecord
{
    const TABLENAME = 'status';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
    }

    
    /**
     * Method getChamados
     */
    public function getChamados()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('status_id', '=', $this->id));
        return Chamado::getObjects( $criteria );
    }
    
    
    /**
     * Method getEnquetes
     */
    public function getEnquetes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('status_id', '=', $this->id));
        return Enquete::getObjects( $criteria );
    }
    


}
