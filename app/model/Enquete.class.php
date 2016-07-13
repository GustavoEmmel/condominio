<?php
/**
 * Enquete Active Record
 * @author  <your-name-here>
 */
class Enquete extends TRecord
{
    const TABLENAME = 'enquete';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $melhoria;
    private $status;
    private $moradors;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('dataAbertura');
        parent::addAttribute('dataFechamento');
        parent::addAttribute('melhoria_id');
        parent::addAttribute('status_id');
    }

    
    /**
     * Method set_melhoria
     * Sample of usage: $enquete->melhoria = $object;
     * @param $object Instance of Melhoria
     */
    public function set_melhoria(Melhoria $object)
    {
        $this->melhoria = $object;
        $this->melhoria_id = $object->id;
    }
    
    /**
     * Method get_melhoria
     * Sample of usage: $enquete->melhoria->attribute;
     * @returns Melhoria instance
     */
    public function get_melhoria()
    {
        // loads the associated object
        if (empty($this->melhoria))
            $this->melhoria = new Melhoria($this->melhoria_id);
    
        // returns the associated object
        return $this->melhoria;
    }
    
    
    /**
     * Method set_status
     * Sample of usage: $enquete->status = $object;
     * @param $object Instance of Status
     */
    public function set_status(Status $object)
    {
        $this->status = $object;
        $this->status_id = $object->id;
    }
    
    /**
     * Method get_status
     * Sample of usage: $enquete->status->attribute;
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
    
    
    /**
     * Method addMorador
     * Add a Morador to the Enquete
     * @param $object Instance of Morador
     */
    public function addMorador(Morador $object)
    {
        $this->moradors[] = $object;
    }
    
    /**
     * Method getMoradors
     * Return the Enquete' Morador's
     * @return Collection of Morador
     */
    public function getMoradors()
    {
        return $this->moradors;
    }

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->moradors = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
    
        // load the related Morador objects
        $repository = new TRepository('EnqueteMorador');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('enquete_id', '=', $id));
        $enquete_moradors = $repository->load($criteria);
        if ($enquete_moradors)
        {
            foreach ($enquete_moradors as $enquete_morador)
            {
                $morador = new Morador( $enquete_morador->morador_id );
                $this->addMorador($morador);
            }
        }
    
        // load the object itself
        return parent::load($id);
    }

    /**
     * Store the object and its aggregates
     */
    public function store()
    {
        // store the object itself
        parent::store();
    
        // delete the related EnqueteMorador objects
        $criteria = new TCriteria;
        $criteria->add(new TFilter('enquete_id', '=', $this->id));
        $repository = new TRepository('EnqueteMorador');
        $repository->delete($criteria);
        // store the related EnqueteMorador objects
        if ($this->moradors)
        {
            foreach ($this->moradors as $morador)
            {
                $enquete_morador = new EnqueteMorador;
                $enquete_morador->morador_id = $morador->id;
                $enquete_morador->enquete_id = $this->id;
                $enquete_morador->store();
            }
        }
    }

    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;
        // delete the related EnqueteMorador objects
        $repository = new TRepository('EnqueteMorador');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('enquete_id', '=', $id));
        $repository->delete($criteria);
        
    
        // delete the object itself
        parent::delete($id);
    }


}
