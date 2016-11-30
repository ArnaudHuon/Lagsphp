<?php

require_once './LagsService.php';

$debug = false;
$flag = false;
while (!$flag){
    $service = new LagsService();
    $service->getFichierOrder("ORDRES.CSV");
    $commande = 'Z';
    while ($commande !== 'A' && $commande !== 'L' && $commande !== 'S' && $commande !== 'Q' && $commande !== 'C') {
        fwrite(STDOUT,"A)JOUTER UN ORDRE  L)ISTER   C)ALCULER CA  S)UPPRIMER  Q)UITTER \r\n");
        try {
            $keyInfo = fgets(STDIN);
            $commande = trim(strtoupper($keyInfo));
            fwrite(STDOUT,$commande);
            fwrite(STDOUT,"\r\n");
        } catch (Exception $e) {
            fwrite(STDOUT, $e);
            fwrite(STDOUT,"\r\n");
        }
        switch ($commande) {
            case 'Q':
                $flag = true;
                break;
            case 'L':
                $service->liste();
                break;
            case 'A':
                $service->ajouterOrdre();
                break;
            case 'S':
                $service->suppression();
                break;
            case 'C':
                $service->calculerLeCA($debug);
                break;
            default :
                break;
        }

    }
}
