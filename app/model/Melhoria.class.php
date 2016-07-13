<?php
/**
 * Melhoria Active Record
 * @author  <your-name-here>
 */
class Melhoria extends TRecord
{
    const TABLENAME = 'melhoria';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $prestador;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('titulo');
        parent::addAttribute('descricao');
        parent::addAttribute('custoAproximado');
        parent::addAttribute('custoFinal');
        parent::addAttribute('dataRealizacao');
        parent::addAttribute('prestador_id');
    }

    
    /**
     * Method set_prestador
     * Sample of usage: $melhoria->prestador = $object;
     * @param $object Instance of Prestador
     */
    public function set_prestador(Prestador $object)
    {
        $this->prestador = $object;
        $this->prestador_id = $object->id;
    }
    
    /**
     * Method get_prestador
     * Sample of usage: $melhoria->prestador->attribute;
     * @returns Prestador instance
     */
    public function get_prestador()
    {
        // loads the associated object
        if (empty($this->prestador))
            $this->prestador = new Prestador($this->prestador_id);
    
        // returns the associated object
        return $this->prestador;
    }
    

    
    /**
     * Method getEnquetes
     */
    public function getEnquetes()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('melhoria_id', '=', $this->id));
        return Enquete::getObjects( $criteria );
    }
    


}
