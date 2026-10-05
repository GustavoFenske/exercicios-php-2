<?php

function contar_pacientes_unicos($consultas) {
    $pacientes = array_column($consultas, 'paciente');
    return count(array_unique($pacientes));
}

function contar_por_especialidade($consultas) {
    $especialidades = array_column($consultas, 'especialidade');
    return array_count_values($especialidades);
}

function ordenar_por_horario($consultas) {
    usort($consultas, function($a, $b) {
        return strtotime($a['horario']) - strtotime($b['horario']);
    });
    return $consultas;
}

function buscar_paciente($consultas, $nome_paciente) {
    $resultado = [];
    foreach ($consultas as $c) {
        if (strcasecmp($c['paciente'], $nome_paciente) === 0) {
            $resultado[] = $c;
        }
    }
    return $resultado;
}

function verificar_horarios_duplicados($consultas) {
    $horarios = array_column($consultas, 'horario');
    return count($horarios) !== count(array_unique($horarios));
}

function obter_extremos_atendimento($consultas_ordenadas) {
    if (empty($consultas_ordenadas)) {
        return ["primeiro" => null, "ultimo" => null];
    }
    return [
        "primeiro" => $consultas_ordenadas[0],
        "ultimo" => $consultas_ordenadas[count($consultas_ordenadas) - 1]
    ];
}

function organizar_agenda($consultas, $paciente_busca = "") {
    $total_consultas = count($consultas);
    $pacientes_unicos = contar_pacientes_unicos($consultas);
    $por_especialidade = contar_por_especialidade($consultas);
    
    $consultas_ordenadas = ordenar_por_horario($consultas);
    $extremos = obter_extremos_atendimento($consultas_ordenadas);
    
    $pesquisa = !empty($paciente_busca) ? buscar_paciente($consultas, $paciente_busca) : [];
    $horarios_duplicados = verificar_horarios_duplicados($consultas);

    return [
        "total_consultas" => $total_consultas,
        "pacientes_unicos" => $pacientes_unicos,
        "por_especialidade" => $por_especialidade,
        "primeiro_atendimento" => $extremos["primeiro"],
        "ultimo_atendimento" => $extremos["ultimo"],
        "lista_ordenada" => $consultas_ordenadas,
        "resultado_pesquisa" => $pesquisa,
        "tem_duplicados" => $horarios_duplicados
    ];
}

$agenda_consultas = [
    ["paciente" => "Ícaro", "especialidade" => "Cardiologia", "data" => "2026-10-05", "horario" => "14:30"],
    ["paciente" => "Djneffier", "especialidade" => "Dermatologia", "data" => "2026-10-05", "horario" => "08:00"],
    ["paciente" => "Roeder", "especialidade" => "Cardiologia", "data" => "2026-10-05", "horario" => "10:15"],
    ["paciente" => "Gustavo", "especialidade" => "Pediatria", "data" => "2026-10-05", "horario" => "16:00"],
    ["paciente" => "João Arduino", "especialidade" => "Ortopedia", "data" => "2026-10-05", "horario" => "09:30"]
];

$paciente_pesquisado = "Djneffier";
$relatorio = organizar_agenda($agenda_consultas, $paciente_pesquisado);

echo "<h2>Relatório da Agenda de Consultas</h2>";

echo "Total de consultas: " . $relatorio["total_consultas"] . "<br>";
echo "Quantidade de pacientes diferentes: " . $relatorio["pacientes_unicos"] . "<br>";
echo "Horários duplicados na agenda: " . ($relatorio["tem_duplicados"] ? "Sim" : "Não") . "<br><br>";

echo "Consultas por Especialidade:<br><ul>";
foreach ($relatorio["por_especialidade"] as $especialidade => $qtd) {
    echo "<li>$especialidade: $qtd</li>";
}
echo "</ul>";

echo "Primeiro Atendimento: " . $relatorio["primeiro_atendimento"]["horario"] . " - " . $relatorio["primeiro_atendimento"]["paciente"] . " (" . $relatorio["primeiro_atendimento"]["especialidade"] . ")<br>";
echo "Último Atendimento: " . $relatorio["ultimo_atendimento"]["horario"] . " - " . $relatorio["ultimo_atendimento"]["paciente"] . " (" . $relatorio["ultimo_atendimento"]["especialidade"] . ")<br><br>";

echo "Lista de Consultas Ordenada por Horário:<br><ol>";
foreach ($relatorio["lista_ordenada"] as $c) {
    echo "<li>" . $c["horario"] . " - " . $c["paciente"] . " (" . $c["especialidade"] . ")</li>";
}
echo "</ol>";

echo "Pesquisa pelo paciente '$paciente_pesquisado':<br>";
if (empty($relatorio["resultado_pesquisa"])) {
    echo "Nenhuma consulta encontrada.<br>";
} else {
    echo "<ul>";
    foreach ($relatorio["resultado_pesquisa"] as $p) {
        echo "<li>Horário: " . $p["horario"] . " | Especialidade: " . $p["especialidade"] . "</li>";
    }
    echo "</ul>";
}

?>