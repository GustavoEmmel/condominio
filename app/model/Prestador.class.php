<?php
/**
 * Prestador Active Record
 * @author  <your-name-here>
 */
class Prestador extends TRecord
{
    const TABLENAME = 'prestador';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $habilidades;
    private $contatos;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
    }

    
    /**
     * Method addHabilidade
     * Add a Habilidade to the Prestador
     * @param $object Instance of Habilidade
     */
    public function addHabilidade(Habilidade $object)
    {
        $this->habilidades[] = $object;
    }
    
    /**
     * Method getHabilidades
     * Return the Prestador' Habilidade's
     * @return Collection of Habilidade
     */
    public function getHabilidades()
    {
        return $this->habilidades;
    }
    
    /**
     * Method addContato
     * Add a Contato to the Prestador
     * @param $object Instance of Contato
     */
    public function addContato(Contato $object)
    {
        $this->contatos[] = $object;
    }
    
    /**
     * Method getContatos
     * Return the Prestador' Contato's
     * @return Collection of Contato
     */
    public function getContatos()
    {
        return $this->contatos;
    }

    
    /**
     * Method getMelhorias
     */
    public function getMelhorias()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('prestador_id', '=', $this->id));
        return Melhoria::getObjects( $criteria );
    }
    

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->habilidades = array();
        $this->contatos = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
    
        // load the related Habilidade objects
        $repository = new TRepository('PrestadorHabilidade');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('prestador_id', '=', $id));
        $prestador_habilidades = $repository->load($criteria);
        if ($prestador_habilidades)
        {
            foreach ($prestador_habilidades as $prestador_habilidade)
            {
                $habilidade = new Habilidade( $prestador_habilidade->habilidade_id );
                $this->addHabilidade($habilidade);
            }
        }
    
        // load the related Contato objects
        $repository = new TRepository('Contato');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('prestador_id', '=', $id));
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
    
        // delete the related PrestadorHabilidade objects
        $criteria = new TCriteria;
        $criteria->add(new TFilter('prestador_id', '=', $this->id));
        $repository = new TRepository('PrestadorHabilidade');
        $repository->delete($criteria);
        // store the related PrestadorHabilidade objects
        if ($this->habilidades)
        {
            foreach ($this->habilidades as $habilidade)
            {
                $prestador_habilidade = new PrestadorHabilidade;
                $prestador_habilidade->habilidade_id = $habilidade->id;
                $prestador_habilidade->prestador_id = $this->id;
                $prestador_habilidade->store();
            }
        }
        // delete the related Contato objects
        $criteria = new TCriteria;
        $criteria->add(new TFilter('prestador_id', '=', $this->id));
        $repository = new TRepository('Contato');
        $repository->delete($criteria);
        // store the related Contato objects
        if ($this->contatos)
        {
            foreach ($this->contatos as $contato)
            {
                unset($contato->id);
                $contato->prestador_id = $this->id;
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
        // delete the related PrestadorHabilidade objects
        $repository = new TRepository('PrestadorHabilidade');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('prestador_id', '=', $id));
        $repository->delete($criteria);
        
        // delete the related Contato objects
        $repository = new TRepository('Contato');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('prestador_id', '=', $id));
        $repository->delete($criteria);
        
    
        // delete the object itself
        parent::delete($id);
    }


}
