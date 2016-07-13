<?php
require_once 'init.php';

class TApplication extends AdiantiCoreApplication
{
    static public function run($debug = FALSE)
    {
        new TSession;
        if ($_REQUEST)
        {
            $class = isset($_REQUEST['class']) ? $_REQUEST['class']   : '';
            
            if (TSession::getValue('logged')) // logged
            {
                $programs = (array) TSession::getValue('programs'); // programs with permission
                $programs = array_merge($programs, array('Adianti\Base\TStandardSeek' => TRUE, 'LoginForm' => TRUE, 'AdiantiMultiSearchService' => TRUE, 'AdiantiUploaderService' => TRUE, 'EmptyPage' => TRUE, 'MessageList'=>TRUE, 'SearchBox' => TRUE)); // default programs
                
                if( isset($programs[$class]) )
                {
                    parent::run($debug);
                }
                else
                {
                    new TMessage('error', _t('Permission denied') );
                }
            }
            else if ($class == 'LoginForm')
            {
                parent::run($debug);
            }
            else
            {
                new TMessage('error', _t('Permission denied'), new TAction(array('LoginForm','onLogout')) );
            }
        }
    }
}

TApplication::run(TRUE);
