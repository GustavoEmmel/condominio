<?php
namespace Adianti\Widget\Template;

use Adianti\Core\AdiantiCoreTranslator;
use Exception;
use TApplicationTranslator;

/**
 * Html Renderer
 *
 * @version    2.0
 * @package    widget
 * @subpackage template
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class THtmlRenderer
{
    private $path;
    private $sections;
    private $replacements;
    private $enabledSections;
    private $repeatSection;
    private $enabledTranslation;
    
    /**
     * Constructor method
     * 
     * @param $path  HTML resource path
     */
    public function __construct($path)
    {
        if (!file_exists($path))
        {
            throw new Exception(AdiantiCoreTranslator::translate('File not found').': ' . $path);
        }
        $this->path = $path;
        $this->enabledSections = array();
        $this->enabledTranslation = FALSE;
    }
    
    /**
     * Enable translation inside template
     */
    public function enableTranslation()
    {
        $this->enabledTranslation = TRUE;
    }
    
    /**
     * Enable a HTML section to show
     * 
     * @param $sectionName Section name
     * @param $replacements Array of replacements for this section
     * @param $repeat Define if the section is repeatable
     */
    public function enableSection($sectionName, $replacements = NULL, $repeat = FALSE)
    {
        $this->enabledSections[] = $sectionName;
        $this->replacements[$sectionName] = $replacements;
        $this->repeatSection[$sectionName] = $repeat;
    }
    
    /**
     * Replace the content with array of replacements
     * 
     * @param $replacements array of replacements
     * @param $content content to be replaced
     */
    private function replace(&$replacements, $content)
    {
        if (is_array($replacements))
        {
            foreach ($replacements as $variable => $value)
            {
                if (is_scalar($value))
                {
                    $content = str_replace('{$'.$variable.'}', $value, $content);
                }
                else if (is_object($value))
                {
                    if (method_exists($value, 'show'))
                    {
                        ob_start();
                        $value->show();
                        $output = ob_get_contents();
                        ob_end_clean();
                        $content = str_replace('{$'.$variable.'}', $output, $content);
                        $replacements[$variable] = $output;
                    }
                }
            }
        }
        return $content;
    }
    
    /**
     * Show the HTML and the enabled sections
     */
    public function show()
    {
        $opened_sections = array();
        $sections_stack = array('main');
        $array_content = array();
        $buffer = array();
        
        if (file_exists($this->path))
        {
            $array_content = file($this->path);
            
            // iterate line by line
            foreach ($array_content as $line)
            {
                $line_ = trim($line);
                $line_ = str_replace("\n", '', $line_);
                $line_ = str_replace("\r", '', $line_);
                
                if ($this->enabledTranslation)
                {
                    $line  = TApplicationTranslator::translateTemplate($line_);
                }
                
                // detect section start
                if ( (substr($line_, 0,5)=='<!--[') AND (substr($line_, -4) == ']-->') AND (substr($line_, 0,6)!=='<!--[/') )
                {
                    $sectionName = substr($line_, 5, strpos($line_, ']-->')-5);
                    $sections_stack[] = $sectionName;
                    $buffer[$sectionName] = '';
                    $opened_sections[$sectionName] = TRUE;
                }
                
                // detect section end
                if ( (substr($line_, 0,6)=='<!--[/') )
                {
                    $sectionName = substr($line_, 6, strpos($line_, ']-->')-6);
                    $opened_sections[$sectionName] = FALSE;
                    
                    if (isset($this->repeatSection[$sectionName]) AND $this->repeatSection[$sectionName])
                    {
                        // if the section is repeatable, repeat the content according to its replacements
                        if (isset($this->replacements[$sectionName]))
                        {
                            // in this case (repeatSection), replacements is a matrix, foreach iteration
                            foreach ($this->replacements[$sectionName] as $iteration_replacement)
                            {
                                $row = $buffer[$sectionName];
                                $row = $this->replace($iteration_replacement, $row);
                                print $row;
                            }
                        }
                    }
                    $buffer[$sectionName] = '';
                    array_pop($sections_stack);
                }
                
                $sectionName = end($sections_stack);
                if (in_array($sectionName, $this->enabledSections)) // if the section is enabled
                {
                    // if the section is repeatable, then put the line inside the buffer
                    if (isset($this->repeatSection[$sectionName]) AND $this->repeatSection[$sectionName])
                    {
                        $buffer[$sectionName] .= $line;
                    }
                    else
                    {
                        // print the line with the replacements
                        if (isset($this->replacements[$sectionName]))
                        {
                            print $this->replace($this->replacements[$sectionName], $line);
                        }
                        else
                        {
                            print $line;
                        }
                    }
                }
            }
        }
        
        // check for unclosed sections
        if ($opened_sections)
        {
            foreach ($opened_sections as $section => $opened)
            {
                if ($opened)
                {
                    throw new Exception(AdiantiCoreTranslator::translate('The section (^1) was not closed properly', $section));
                }
            }
        }
    }
    
    /**
     * Returns the HTML content as a string
     */
    public function getContents()
    {
        ob_start();
        $this->show();
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
