<?php
/**
 * Morador Active Record
 * @author  <your-name-here>
 */
class Morador extends TRecord
{
    const TABLENAME = 'morador';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $contatos;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('apartamento');
    }

    
    /**
     * Method addContato
     * Add a Contato to the Morador
     * @param $object Instance of Contato
     */
    public function addContato(Contato $object)
    {
        $this->contatos[] = $object;
    }
    
    /**
     * Method getContatos
     * Return the Morador' Contato's
     * @return Collection of Contato
     */
    public function getContatos()
    {
        return $this->contatos;
    }

    
    /**
     * Method getSalaos
     */
    public function getSalaos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('morador_id', '=', $this->id));
        return Salao::getObjects( $criteria );
    }
    
    
    /**
     * Method getChamados
     */
    public function getChamados()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('morador_id', '=', $this->id));
        return Chamado::getObjects( $criteria );
    }
    

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->contatos = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
    
        // load the related Contato objects
        $repository = new TRepository('Contato');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('morador_id', '=', $id));
        $this->contatos = $repository->load($criteria);
    
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
    
        // delete the related Contato objects
        $criteria = new TCriteria;
        $criteria->add(new TFilter('morador_id', '=', $this->id));
        $repository = new TRepository('Contato');
        $repository->delete($criteria);
        // store the related Contato objects
        if ($this->contatos)
        {
            foreach ($this->contatos as $contato)
            {
                unset($contato->id);
                $contato->morador_id = $this->id;
                $contato->store();
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
        // delete the related Contato objects
        $repository = new TRepository('Contato');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('morador_id', '=', $id));
        $repository->delete($criteria);
        
    
        // delete the object itself
        parent::delete($id);
    }


}
