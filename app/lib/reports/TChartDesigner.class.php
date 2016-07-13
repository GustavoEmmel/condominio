<?php
/**
 *
 */
abstract class TChartDesigner
{
    public abstract function drawLineChart($title, $data, $xlabels, $ylabel, $width, $height, $outputPath);
    public abstract function drawBarChart($title,  $data, $xlabels, $ylabel, $width, $height, $outputPath);
    public abstract function drawPieChart($title,  $data, $width, $height, $outputPath);
}

?>