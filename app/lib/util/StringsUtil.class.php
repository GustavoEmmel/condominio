<?php
class StringsUtil
{
    
    public function formatDate($date)
    {        
        if($date)
        {
            $dt = explode('/', $date);
            $retorno = $dt[2].'-'.$dt[1].'-'.$dt[0];
            return $retorno;    
        }
    }
    
    public function formatDateBR($date)
    {        
        if($date)
        {
            $dt = explode('-', $date);
            $retorno = $dt[2].'/'.$dt[1].'/'.$dt[0];
            return $retorno;    
        }
    }
        
    public function formatHoras($campo, $object, $row)
    {        
        $object->orcamento_horas ? $campo = strstr($object->orcamento_horas, ':', true) : null;
        return $campo;
    }
    
    public function desconverteReais($valor)
    {
        $valor = str_replace('.',  '', $valor);
        $valor = str_replace(',', '.', $valor);
        return $valor;
    }
    
    function time_to_sec($time) 
    {       
        $neg = '';
        if(substr($time, 0, 1) == '-'){
            $time = substr($time, 1);
            $neg = '-';
        }
        
        $hours = substr($time, 0, -6);
        $minutes = substr($time, -5, 2);
        $seconds = substr($time, -2);
    
        $total = $hours * 3600 + $minutes * 60 + $seconds;        
        return $neg.$total;
    }
    
    function sec_to_time($seconds)
    {
        $neg = '';
        if(substr($seconds, 0, 1) == '-'){
            $seconds = substr($seconds, 1);
            $neg = '-';
        }
        
        $hours = floor($seconds / 3600);
        $mins = floor(($seconds - ($hours*3600)) / 60);
        $secs = floor($seconds % 60);
        
        $total = str_pad($hours, 2, "0", STR_PAD_LEFT).':'.str_pad($mins, 2, "0", STR_PAD_LEFT).':'.str_pad($secs, 2, "0", STR_PAD_LEFT);
        return $neg.$total;
    }
    
    function array_meses()
    {
        $meses = array(
                            1 => 'Janeiro',
                            'Fevereiro',
                            'Março',
                            'Abril',
                            'Maio',
                            'Junho',
                            'Julho',
                            'Agosto',
                            'Setembro',
                            'Outubro',
                            'Novembro',
                            'Dezembro'
                        );
       
       return $meses;
    }
    
    function subtrair_datas($data_inicial, $data_final)
    {
        // Usa a função strtotime() e pega o timestamp das duas datas:
        $time_inicial = strtotime($data_inicial);
        $time_final = strtotime($data_final);
        
        // Calcula a diferença de segundos entre as duas datas:
        $diferenca = $time_final - $time_inicial; 
        
        // Calcula a diferença de dias
        $dias = (int)floor( $diferenca / (60 * 60 * 24)); 
    
        return $dias;
    }
    
    function retira_segundos($horario)
    {
        return substr($horario, 0, -3);
    }
    
    function retorna_mes($mes)
    {
        $meses = array(
                            1 => 'Janeiro',
                            'Fevereiro',
                            'Março',
                            'Abril',
                            'Maio',
                            'Junho',
                            'Julho',
                            'Agosto',
                            'Setembro',
                            'Outubro',
                            'Novembro',
                            'Dezembro'
                        );
       
       return $meses[$mes];
        
    }
    
    function calcularCargaHorariaDiaria($campo, $object, $row)
    {
         if($object->hora_saida and $object->hora_saida_tarde){
            $HoraEntrada         = new DateTime($object->hora_entrada);
            $HoraSaida           = new DateTime($object->hora_saida);
                    
            $campo = $HoraSaida->diff($HoraEntrada)->format('%H:%I:%S');
            $totalPrimeiroTurno  = $this->time_to_sec($campo);
            
            $HoraEntradaTarde    = new DateTime($object->hora_entrada_tarde);
            $HoraSaidaTarde      = new DateTime($object->hora_saida_tarde);
                    
            $campo = $HoraSaidaTarde->diff($HoraEntradaTarde)->format('%H:%I:%S');
            $totalSegundoTurno  = $this->time_to_sec($campo);
            
            $total = $totalSegundoTurno + $totalPrimeiroTurno;
            $campo = $this->sec_to_time($total); 
            
            return substr($campo,0,-3);         
        }
        
        if($object->hora_saida and !$object->hora_saida_tarde) {   
            $HoraEntrada         = new DateTime($object->hora_entrada);
            $HoraSaida           = new DateTime($object->hora_saida);
                    
            $campo = $HoraSaida->diff($HoraEntrada)->format('%H:%I');
                                    
            return $campo;
        }
        
        if(!$object->hora_saida and $object->hora_saida_tarde) {   
            $HoraEntrada         = new DateTime($object->hora_entrada_tarde);
            $HoraSaida           = new DateTime($object->hora_saida_tarde);
                    
            $campo = $HoraSaida->diff($HoraEntrada)->format('%H:%I');
                                    
            return $campo;
        }
    
    }
    
    function retornaIntervalo($campo, $object, $row)
    {
         $intervalo = Ponto::horaPreenchidas($object->data_ponto, $object->colaborador_id);
         return substr($intervalo, 0, -3);
    }
    
    function calculaPercentualProdutividade($campo, $object, $row)
    {       
        $intervalo = $this->retornaIntervalo($campo, $object, $row);
        $ponto = new CalculoHorario;
        $horaPonto = $ponto->retornoCargaHorariaDiaria($object);
        
        if($horaPonto){        
            $campo = round($this->time_to_sec($intervalo) * 100 / $this->time_to_sec($horaPonto) );
            if($campo > 59){
                return "<span style='color:#007BFF'><b>".$campo."%</b></span>";
            } else {
                return "<span style='color:#FFB300'><b>".$campo."%</b></span>";
            } 
        }   
    }
    
}

?>