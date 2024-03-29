<?php

    try {

        $VerificarAtendimentos = "SELECT a.id_atendimento AS ID, a.cod AS Codigo, CONCAT(c.nome, ' ', c.sobrenome) AS Cliente, CONCAT(t.nome, ' ', t.sobrenome) AS Tecnico, t.id_tecnico AS IDT, DATE_FORMAT(a.inicio, '%d-%M-%Y') AS Inicio, IF(a.fim = '0000-00-00 00:00:00', 'Não Finalizado', DATE_FORMAT(a.fim, '%d-%M-%Y')) AS Termino, IF(a.id_reabertura = 0, 'Não Reaberto', CONCAT('OS ', a.id_reabertura)) AS Reaberto FROM atendimento AS a INNER JOIN cliente AS c INNER JOIN tecnico AS t ON c.id_cliente = a.id_cliente AND t.id_tecnico = a.id_tecnico WHERE c.id_cliente = :id ORDER BY id_atendimento DESC;";
        $Atendimentos = $conexao->prepare($VerificarAtendimentos);
        $Atendimentos->bindParam(':id', $_SESSION['id_cliente']);
        $Atendimentos->execute();
        
        while ($linha = $Atendimentos->fetchObject()) {
            if($linha->Reaberto == "Não Reaberto"){
                if($linha->Termino == "Não Finalizado"){
                    $reaberto = "<span class='btntable-ind' href=''>Indisponível</span>";
                }
                else{
                    $ver_atend = $conexao->prepare("SELECT id_atendimento FROM atendimento WHERE id_cliente = :id AND fim = '0000-00-00 00:00:00';");
                    $ver_atend->bindParam(':id', $_SESSION['id_cliente']);
                    $ver_atend->execute();

                    if($ver_atend->rowCount()){
                        $reaberto = "<span class='btntable-ind' href=''>Indisponível</span>";
                    }
                    else{
                        $reaberto = "<a class='btntable' href='../../../../controller/Atendimentos/Cliente/Reabrir/Reabrir.php?idatend={$linha->ID}&reabrir={$linha->Codigo}&tecnico={$linha->IDT}'>Reabrir</a>";
                    }
                }
            }
            else{
                $reaberto = "<span class='btntable-ind' href=''>Indisponível</span>";
            } 
            echo "<tr>
                    <td>
                        {$linha->Codigo}
                    </td>
                    <td>
                        {$linha->Tecnico}
                    </td>
                    <td>
                        {$linha->Inicio}
                    </td>
                    <td>
                        {$linha->Termino}
                    </td>
                    <td>
                        {$linha->Reaberto}
                    </td>
                    <td>
                    " . $reaberto . "
                    </td>
                </tr>";
        }
        echo "</tbody>
            </table>";
    
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conexao = null;
    echo "</tbody>
            </table>";

?>