<?php
/**
 * Chamado Active Record
 * @author  <your-name-here>
 */
class Chamado extends TRecord
{
    const TABLENAME = 'chamado';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $morador;
    private $status;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('titulo');
        parent::addAttribute('descricao');
        parent::addAttribute('providencia');
        parent::addAttribute('dataAbertura');
        parent::addAttribute('dataEncerramento');
        parent::addAttribute('morador_id');
        parent::addAttribute('status_id');
    }

    
    /**
     * Method set_morador
     * Sample of usage: $chamado->morador = $object;
     * @param $object Instance of Morador
     */
    public function set_morador(Morador $object)
    {
        $this->morador = $object;
        $this->morador_id = $object->id;
    }
    
    /**
     * Method get_morador
     * Sample of usage: $chamado->morador->attribute;
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
    
    
    /**
     * Method set_status
     * Sample of usage: $chamado->status = $object;
     * @param $object Instance of Status
     */
    public function set_status(Status $object)
    {
        $this->status = $object;
        $this->status_id = $object->id;
    }
    
    /**
     * Method get_status
     * Sample of usage: $chamado->status->attribute;
     * @returns Status instance
     */
    public function get_status()
    {
        // loads the associated object
        if (empty($this->status))
            $this->status = new Status($this->status_id);
    
        // returns the associated object
        return $this->status;
    }
    


}
