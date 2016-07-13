<?php
class MessageList extends TElement
{
    public function __construct()
    {
        parent::__construct('ul');
        $this->class = 'dropdown-menu dropdown-messages';
        
        $messages = array();
        $messages[] = array(TSession::getValue('login'), 'Yesterday', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...');
        $messages[] = array(TSession::getValue('login'), 'Yesterday', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...');
        $messages[] = array(TSession::getValue('login'), 'Yesterday', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...');
        
        $a = new TElement('a');
        $a->{'class'} = "dropdown-toggle";
        $a->{'data-toggle'}="dropdown";
        $a->{'href'} = "#";
        
        $a->add( TElement::tag('i',    '', array('class'=>"fa fa-envelope fa-fw")) );
        $a->add( TElement::tag('span', count($messages), array('class'=>"badge badge-notify")) );
        $a->add( TElement::tag('i',    '', array('class'=>"fa fa-caret-down")) );
        $a->show();
        
        foreach ($messages as $message)
        {
            $name = $message[0];
            $date = $message[1];
            $body = $message[2];
            
            $li  = new TElement('li');
            $a   = new TElement('a');
            $div = new TElement('div');
            
            $a->href = '#';
            $li->add($a);
            $a->add($div);
            $div->add( TElement::tag('strong', $name) );
            $div->add( TElement::tag('span', TElement::tag('em', $date), array('class' => 'pull-right text-muted') ) );
            
            $div2 = new TElement('div');
            $div2->add($body);
            $a->add($div2);
            
            parent::add($li);
            parent::add( TElement::tag('li', '', array('class' => 'divider') ) );
        }
        
        $li = new TElement('li');
        $a = new TElement('a');
        $li->add($a);
        $a->class='text-center';
        $a->href = '#';
        $a->add( TElement::tag('strong', 'Read messages') );
        $a->add( $i = TElement::tag('i', '', array('class'=>'fa fa-inbox') ));
        parent::add($li);
        
        parent::add( TElement::tag('li', '', array('class' => 'divider') ) );
        
        $li = new TElement('li');
        $a = new TElement('a');
        $li->add($a);
        $a->class='text-center';
        $a->href = '#';
        $a->add( TElement::tag('strong', 'Send message') );
        $a->add( $i = TElement::tag('i', '', array('class'=>'fa fa-envelope-o') ));
        parent::add($li);
    }
}
