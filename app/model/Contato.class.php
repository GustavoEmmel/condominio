<?php
/**
 * Contato Active Record
 * @author  <your-name-here>
 */
class Contato extends TRecord
{
    const TABLENAME = 'contato';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tipo');
        parent::addAttribute('valor');
        parent::addAttribute('morador_id');
        parent::addAttribute('funcionario_id');
        parent::addAttribute('prestador_id');
    }


}
