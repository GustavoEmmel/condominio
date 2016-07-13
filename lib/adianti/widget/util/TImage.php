<?php
namespace Adianti\Widget\Util;

use Adianti\Widget\Base\TElement;

/**
 * Image Widget
 *
 * @version    2.0
 * @package    widget
 * @subpackage util
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TImage extends TElement
{
    private $source; // image path
    
    /**
     * Class Constructor
     * @param $source Image path, of bs:bs-glyphicon, fa:font-awesome
     */
    public function __construct($source)
    {
        if (substr($source,0,3) == 'bs:')
        {
            parent::__construct('i');
            $this-> class = 'glyphicon glyphicon-'.substr($source,3);
            parent::add('');
        }
        else if (substr($source,0,3) == 'fa:')
        {
            parent::__construct('i');
            $this-> class = 'fa fa-'.substr($source,3);
            parent::add('');
        }
        else if (file_exists($source))
        {
            parent::__construct('img');
            // assign the image path
            $this-> src = $source;
            $this-> border = 0;
        }
        else if (file_exists("app/images/$source"))
        {
            parent::__construct('img');
            // assign the image path
            $this-> src = "app/images/$source";
            $this-> border = 0;
        }
        else if (file_exists("lib/adianti/images/$source"))
        {
            parent::__construct('img');
            // assign the image path
            $this-> src = "lib/adianti/images/$source";
            $this-> border = 0;
        }
        else
        {
            parent::__construct('i');
        }
    }
}
