<?php

function contar_maiusculas($senha) {
    preg_match_all('/[A-Z]/', $senha, $ocorrencias);
    return count($ocorrencias[0]);
}

function contar_minusculas($senha) {
    preg_match_all('/[a-z]/', $senha, $ocorrencias);
    return count($ocorrencias[0]);
}

function contar_numeros($senha) {
    preg_match_all('/[0-9]/', $senha, $ocorrencias);
    return count($ocorrencias[0]);
}

function contar_especiais($senha) {
    preg_match_all('/[^a-zA-Z0-9]/', $senha, $ocorrencias);
    return count($ocorrencias[0]);
}

function classificar_nivel_senha($tamanho, $qtd_maiusculas, $qtd_minusculas, $qtd_numeros, $qtd_especiais) {
    $criterios_atendidos = 0;

    if ($qtd_maiusculas > 0) $criterios_atendidos++;
    if ($qtd_minusculas > 0) $criterios_atendidos++;
    if ($qtd_numeros > 0) $criterios_atendidos++;
    if ($qtd_especiais > 0) $criterios_atendidos++;

    if ($tamanho < 8 || $criterios_atendidos <= 1) {
        return "Fraca";
    } elseif ($criterios_atendidos == 2) {
        return "Média";
    } elseif ($criterios_atendidos == 3) {
        return "Forte";
    } else {
        return "Muito Forte";
    }
}

function analisar_senha($senha) {
    $tamanho = strlen($senha);
    $maiusculas = contar_maiusculas($senha);
    $minusculas = contar_minusculas($senha);
    $numeros = contar_numeros($senha);
    $especiais = contar_especiais($senha);

    $nivel = classificar_nivel_senha($tamanho, $maiusculas, $minusculas, $numeros, $especiais);

    return [
        "tamanho" => $tamanho,
        "maiusculas" => $maiusculas,
        "minusculas" => $minusculas,
        "numeros" => $numeros,
        "especiais" => $especiais,
        "nivel" => $nivel
    ];
}

$senha_usuario = "guguproontop123@";

$relatorio = analisar_senha($senha_usuario);

echo "<h2>Análise de Segurança da Senha</h2>";
echo "Senha analisada: <strong>$senha_usuario</strong> <br><br>";

echo "Tamanho total: " . $relatorio["tamanho"] . " caracteres<br>";
echo "Letras maiúsculas: " . $relatorio["maiusculas"] . "<br>";
echo "Letras minúsculas: " . $relatorio["minusculas"] . "<br>";
echo "Números: " . $relatorio["numeros"] . "<br>";
echo "Caracteres especiais: " . $relatorio["especiais"] . "<br>";
echo "Nível de Segurança: <strong>" . $relatorio["nivel"] . "</strong><br>";

?>