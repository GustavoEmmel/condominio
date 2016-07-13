<?php
/**
 * Classe abstrata para gráfico
 */
abstract class TChart
{
    protected $title;
    protected $chartDesigner;
    protected $outputPath;
    protected $width;
    protected $height;
    
    /**
     * Método construtor
     * @param $chartDesigner objeto TChartDesigner
     */
    public function __construct(TChartDesigner $chartDesigner)
    {
        $this->chartDesigner = $chartDesigner;
    }
    
    /**
     * Define o título do gráfico
     * @param $title título do gráfico
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * Define o tamanho do gráfico
     * @param $width  largura
     * @param $height altura
     */
    public function setSize($width, $height)
    {
        $this->width  = $width;
        $this->height = $height;
    }
    
    /**
     * Define o arquivo de saída do gráfico
     * @param $outputPath localização do arquivo de saída
     */
    public function setOutputPath($outputPath)
    {
        $this->outputPath = $outputPath;
    }
    
    /**
     * Método abstrato para geração do gráfico
     */
    abstract public function generate();
}
?>