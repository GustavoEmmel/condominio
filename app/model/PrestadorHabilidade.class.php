<?php
/**
 * PrestadorHabilidade Active Record
 * @author  <your-name-here>
 */
class PrestadorHabilidade extends TRecord
{
    const TABLENAME = 'prestador_habilidade';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('prestador_id');
        parent::addAttribute('habilidade_id');
    }


}
