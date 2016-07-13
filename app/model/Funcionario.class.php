<?php
/**
 * Funcionario Active Record
 * @author  <your-name-here>
 */
class Funcionario extends TRecord
{
    const TABLENAME = 'funcionario';
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
        parent::addAttribute('funcao');
    }

    
    /**
     * Method addHabilidade
     * Add a Habilidade to the Funcionario
     * @param $object Instance of Habilidade
     */
    public function addHabilidade(Habilidade $object)
    {
        $this->habilidades[] = $object;
    }
    
    /**
     * Method getHabilidades
     * Return the Funcionario' Habilidade's
     * @return Collection of Habilidade
     */
    public function getHabilidades()
    {
        return $this->habilidades;
    }
    
    /**
     * Method addContato
     * Add a Contato to the Funcionario
     * @param $object Instance of Contato
     */
    public function addContato(Contato $object)
    {
        $this->contatos[] = $object;
    }
    
    /**
     * Method getContatos
     * Return the Funcionario' Contato's
     * @return Collection of Contato
     */
    public function getContatos()
    {
        return $this->contatos;
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
        $repository = new TRepository('FuncionarioHabilidade');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('funcionario_id', '=', $id));
        $funcionario_habilidades = $repository->load($criteria);
        if ($funcionario_habilidades)
        {
            foreach ($funcionario_habilidades as $funcionario_habilidade)
            {
                $habilidade = new Habilidade( $funcionario_habilidade->habilidade_id );
                $this->addHabilidade($habilidade);
            }
        }
    
        // load the related Contato objects
        $repository = new TRepository('Contato');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('funcionario_id', '=', $id));
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
    
        // delete the related FuncionarioHabilidade objects
        $criteria = new TCriteria;
        $criteria->add(new TFilter('funcionario_id', '=', $this->id));
        $repository = new TRepository('FuncionarioHabilidade');
        $repository->delete($criteria);
        // store the related FuncionarioHabilidade objects
        if ($this->habilidades)
        {
            foreach ($this->habilidades as $habilidade)
            {
                $funcionario_habilidade = new FuncionarioHabilidade;
                $funcionario_habilidade->habilidade_id = $habilidade->id;
                $funcionario_habilidade->funcionario_id = $this->id;
                $funcionario_habilidade->store();
            }
        }
        // delete the related Contato objects
        $criteria = new TCriteria;
        $criteria->add(new TFilter('funcionario_id', '=', $this->id));
        $repository = new TRepository('Contato');
        $repository->delete($criteria);
        // store the related Contato objects
        if ($this->contatos)
        {
            foreach ($this->contatos as $contato)
            {
                unset($contato->id);
                $contato->funcionario_id = $this->id;
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
        // delete the related FuncionarioHabilidade objects
        $repository = new TRepository('FuncionarioHabilidade');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('funcionario_id', '=', $id));
        $repository->delete($criteria);
        
        // delete the related Contato objects
        $repository = new TRepository('Contato');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('funcionario_id', '=', $id));
        $repository->delete($criteria);
        
    
        // delete the object itself
        parent::delete($id);
    }


}
