<?php
/**
 * Habilidade Active Record
 * @author  <your-name-here>
 */
class Habilidade extends TRecord
{
    const TABLENAME = 'habilidade';
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


}
