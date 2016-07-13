<?php
/**
 * Classe para geraзгo de grбficos de linhas
 */
final class TLineChart extends TChart
{
    private $data;
    private $xlabels;
    private $ylabel;
    
    /**
     * Mйtodo construtor
     * @param $chartDesigner objeto TChartDesigner
     */
    public function __construct(TChartDesigner $chartDesigner)
    {
        parent::__construct($chartDesigner);
        
        $this->data = array();
    }
    
    /**
     * Define os rуtulos do eixo X do grбfico
     * @param $labels vetor com rуtulos
     */
    public function setXLabels($labels)
    {
        $this->xlabels = $labels;
    }
    
    /**
     * Define o rуtulo do eixo Y do grбfico
     * @param $label rуtulo
     */
    public function setYLabel($label)
    {
        $this->ylabel = $label;
    }
    
    /**
     * Adiciona uma sйrie de dados ao grбfico
     * @param $legend legenda para a sйrie de dados
     * @param $data sйrie de dados
     */
    public function addData($legend, $data)
    {
        $this->data[$legend] = $data;
    }
    
    /**
     * Gera o grбfico
     */
    public function generate()
    {
        $this->chartDesigner->drawLineChart($this->title, $this->data, $this->xlabels, $this->ylabel, 
                                            $this->width, $this->height, $this->outputPath);
    }
}

?>