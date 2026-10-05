<?php

function limpar_espacos($texto) {
    return trim(preg_replace('/\s+/', ' ', $texto));
}

function formatar_primeira_maiuscula($texto) {
    return ucwords(mb_strtolower($texto));
}

function contar_frases($texto) {
    preg_match_all('/[.!?]+/', $texto, $ocorrencias);
    return count($ocorrencias[0]);
}

function obter_extremos_palavras($palavras) {
    if (empty($palavras)) {
        return ["maior" => "", "menor" => ""];
    }

    $maior = $palavras[0];
    $menor = $palavras[0];

    foreach ($palavras as $palavra) {
        if (mb_strlen($palavra) > mb_strlen($maior)) {
            $maior = $palavra;
        }
        if (mb_strlen($palavra) < mb_strlen($menor)) {
            $menor = $palavra;
        }
    }

    return ["maior" => $maior, "menor" => $menor];
}

function contar_palavras_repetidas($contagem_frequencia) {
    $repetidas = 0;
    foreach ($contagem_frequencia as $qtd) {
        if ($qtd > 1) {
            $repetidas++;
        }
    }
    return $repetidas;
}

function obter_top_palavras($contagem_frequencia) {
    arsort($contagem_frequencia);
    return array_slice($contagem_frequencia, 0, 5, true);
}

function processar_texto($texto_original) {
    $texto_limpo = limpar_espacos($texto_original);
    
    $texto_sem_pontuacao = preg_replace('/[^\w\sà-úÀ-Ú]/u', '', mb_strtolower($texto_limpo));
    $palavras = array_filter(explode(' ', $texto_sem_pontuacao));

    $quantidade_caracteres = mb_strlen($texto_limpo);
    $quantidade_palavras = count($palavras);
    $quantidade_frases = contar_frases($texto_original);

    $extremos = obter_extremos_palavras(array_values($palavras));
    
    $frequencia_palavras = array_count_values($palavras);
    $palavras_repetidas = contar_palavras_repetidas($frequencia_palavras);
    $top_5_palavras = obter_top_palavras($frequencia_palavras);
    
    $texto_formatado = formatar_primeira_maiuscula($texto_limpo);

    return [
        "caracteres" => $quantidade_caracteres,
        "palavras" => $quantidade_palavras,
        "frases" => $quantidade_frases,
        "palavra_longa" => $extremos["maior"],
        "palavra_curta" => $extremos["menor"],
        "palavras_repetidas" => $palavras_repetidas,
        "top_5" => $top_5_palavras,
        "texto_sem_espacos" => $texto_limpo,
        "texto_formatado" => $texto_formatado
    ];
}

$texto_exemplo = " Toda atividade que der para colocar texto como esse, eu vou colocar o nome do icaro pelo menos uma vez porque é engraçado :P";

$relatorio = processar_texto($texto_exemplo);

echo "<h2>Estatísticas do Processador de Texto</h2>";
echo "<strong>Texto Original:</strong> \"$texto_exemplo\"<br><br>";

echo "Quantidade de caracteres: " . $relatorio["caracteres"] . "<br>";
echo "Quantidade de palavras: " . $relatorio["palavras"] . "<br>";
echo "Quantidade de frases: " . $relatorio["frases"] . "<br>";
echo "Palavra mais longa: " . $relatorio["palavra_longa"] . "<br>";
echo "Palavra mais curta: " . $relatorio["palavra_curta"] . "<br>";
echo "Quantidade de palavras repetidas: " . $relatorio["palavras_repetidas"] . "<br>";

echo "<br><strong>Top 5 palavras mais frequentes:</strong><br>";
echo "<ul>";
foreach ($relatorio["top_5"] as $palavra => $qtd) {
    echo "<li>$palavra: $qtd vez</li>";
}
echo "</ul>";

echo "<strong>Texto sem espaços duplicados:</strong> " . $relatorio["texto_sem_espacos"] . "<br>";
echo "<strong>Texto formatado:</strong> " . $relatorio["texto_formatado"] . "<br>";

?>