<?php
/**
 * Circular Active Record
 * @author  <your-name-here>
 */
class Circular extends TRecord
{
    const TABLENAME = 'circular';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('titulo');
        parent::addAttribute('descricao');
        parent::addAttribute('cadastro');
        parent::addAttribute('id_system_user');
    }


}
