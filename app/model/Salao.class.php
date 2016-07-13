<?php
/**
 * Salao Active Record
 * @author  <your-name-here>
 */
class Salao extends TRecord
{
    const TABLENAME = 'salao';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $morador;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('dataReserva');
        parent::addAttribute('horaInicio');
        parent::addAttribute('horaFim');
        parent::addAttribute('morador_id');
    }

    
    /**
     * Method set_morador
     * Sample of usage: $salao->morador = $object;
     * @param $object Instance of Morador
     */
    public function set_morador(Morador $object)
    {
        $this->morador = $object;
        $this->morador_id = $object->id;
    }
    
    /**
     * Method get_morador
     * Sample of usage: $salao->morador->attribute;
     * @returns Morador instance
     */
    public function get_morador()
    {
        // loads the associated object
        if (empty($this->morador))
            $this->morador = new Morador($this->morador_id);
    
        // returns the associated object
        return $this->morador;
    }
    


}
