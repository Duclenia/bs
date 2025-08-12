<?php

if (!function_exists('getNameUser')) {

    function getNameUser() {
        $user = auth()->user();

        $name = '';

        if ($user->user_type == 'Cliente') {

            $name = $user->cliente->full_name;
        } else {

            $name = $user->admin->pessoasingular->nome . ' ' . $user->admin->pessoasingular->sobrenome;
        }
        return $name;
    }

}

if (!function_exists('getTaskStatusList')) {

    function getTaskStatusList() {
        $taskArr = array(
            'em curso' => 'Em curso',
            'concluída' => 'Concluída',
            'pendente' => 'Pendente',
        );
        return $taskArr;
    }

}

if (!function_exists('getTaskPriorityList')) {

    function getTaskPriorityList() {
        $taskPriorityArr = array(
            'Baixa' => 'Baixa',
            'Média' => 'Média',
            'Alta' => 'Alta',
            'Urgente' => 'Urgente',
        );
        return $taskPriorityArr;
    }

    if (!function_exists('formatarData')) {

        function formatarData($data, $format = 'd/m/Y') {
            // Utiliza a classe de Carbon para converter ao formato de data ou hora desejado
            return Carbon\Carbon::parse($data)->format($format);
        }

    }

    if (!function_exists('verificarData')) {

        function add_months(DateTime $date, int $months) {
            // Clona o objeto $date para mantê-lo inalterado
            $future = clone $date;

            // Define o modificador
            $modifier = "{$months} months";

            // Modifica a data $future
            $future->modify($modifier);

            // Clona o objeto $future para corrigir o limite dos dias
            $pass = clone $future;
            $pass->modify("-{$modifier}");

            // Enquanto o mes atual for diferente do mês do passado do futuro
            while ($date->format('m') != $pass->format('m')) {
                // Modifica as datas em -1 dia
                $future->modify("-1 day");
                $pass->modify("-1 day");
            }

            // Retorna a data desejada
            return $future;
        }

    }


    if (!function_exists('verificarData')) {

        function verificarData($data) {
            $data_actual = date('Y-m-d');

            return (strtotime($data) >= strtotime($data_actual));
        }

    }

    if (!function_exists('gerarPalavraPasse'))
    {
        function gerarPalavraPasse()
        {
            $caracteres = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXZ@#%?';
            $retorno = '';

            $len = strlen($caracteres);

            for ($n = 1; $n <= 8; $n++) {

                $rand = mt_rand(1, $len);

                $retorno .= $caracteres[$rand - 1];
            }

            return $retorno;
        }

    }
}
