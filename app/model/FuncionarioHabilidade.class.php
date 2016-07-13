<?php
/**
 * FuncionarioHabilidade Active Record
 * @author  <your-name-here>
 */
class FuncionarioHabilidade extends TRecord
{
    const TABLENAME = 'funcionario_habilidade';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('funcionario_id');
        parent::addAttribute('habilidade_id');
    }


}
