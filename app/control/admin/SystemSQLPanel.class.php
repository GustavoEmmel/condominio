<?php
class SystemSQLPanel extends TPage
{
    private $form;
    private $container;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->form = new TQuickForm('sqlpanel');
        $this->form->class = 'tform';
        $this->form->setFormTitle('SQL Panel');
        $this->form->style = 'width:100%';
        
        $list = scandir('app/config');
        $options = array();
        foreach ($list as $entry)
        {
            if (substr($entry, -4) == '.ini')
            {
                $options[ substr($entry,0,-4) ] = $entry;
            }
        }
        
        $database = new TCombo('database');
        $select = new TText('select');
        $database->addItems($options);
        
        $this->form->addQuickField( _t('Database'), $database, '50%', new TRequiredValidator);
        $this->form->addQuickField( 'SELECT', $select, '80%', new TRequiredValidator );
        $this->form->addQuickAction( _t('Generate'), new TAction(array($this, 'onGenerate')), 'fa:check-circle green');
        
        $select->setSize('80%', 100);
        
        $this->container = new TTable;
        $this->container->style = 'width: 80%';
        $this->container->addRow()->addCell(new TXMLBreadCrumb('menu.xml','SystemProgramList'));
        $this->container->addRow()->addCell($this->form);
        
        parent::add($this->container);
    }
    
    public function onGenerate($param)
    {
        try
        {
            $this->form->validate();
            $data = $this->form->getData();
            
            if (strtoupper(substr( $data->select, 0, 6)) !== 'SELECT')
            {
                throw new Exception(_t('Invalid command'));
            }
            // creates a DataGrid
            $datagrid = new BootstrapDatagridWrapper(new TDataGrid);
            $datagrid->style = 'width: 100%';
            $datagrid->setHeight(320);
            
            TTransaction::open( $data->database );
            
            $conn = TTransaction::get();
            
            $result = $conn->query( $data->select );
            
            $row = $result->fetch();
            
            foreach ($row as $key => $value)
            {
                if (is_string($key))
                {
                    $col = new TDataGridColumn($key, $key, 'left');
                    $datagrid->addColumn($col);
                }
            }
            
            // create the datagrid model
            $datagrid->createModel();
            
            $datagrid->addItem( (object) $row );
            
            $i = 1;
            while ($row = $result->fetch() AND $i<= 1000)
            {
                $datagrid->addItem( (object) $row );
                $i ++;
            }
            
            $panel = new TPanelGroup( _t('Results') );
            $panel->add($datagrid);
            $panel->addFooter( _t('^1 records shown', "<b>{$i}</b>"));
            $this->container->addRow()->addCell($panel);
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}