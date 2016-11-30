<?php

require_once './Ordre.php';

class LagsService {

    private $listOrdre = array();

    // lit le fihier des ordres et calcule le CA
    public function getFichierOrder($fileName){
        try{
            $lines = file($fileName);
            foreach ($lines as $line){
                $champs = explode(';', $line);
                $chp1 = $champs[0];
                $chp2 = intval($champs[1]);
                $champ3 = intval($champs[2]);
                $chp4 = doubleval($champs[3]);
                $ordre = new Ordre($chp1, $chp2, $champ3, $chp4);
                $this->listOrdre[] = $ordre;

            }
        }
        catch (Exception $e) {
                echo "FICHIER ORDRES.CSV NON TROUVE. CREATION FICHIER.";
                $this->writeOrdres($fileName);
        }
    }

        // écrit le fichier des ordres
    private function writeOrdres($nomFich){
        $lines = array();
        for($i=0; $i<count($this->listOrdre); $i++) {
            $ordre = $this->listOrdre[$i];
            $ligneCSV =  $ordre->getId() . ';' . $ordre->getDebut()  . ';' . $ordre->getDuree()  . ';' . $ordre->prix();
            $lines[] = $ligneCSV;
        }
        try{
            if($file=fopen($nomFich,'w+')){
                for($i=0;$i<sizeof($lines);$i++){
                    $val_a_ecrire=$lines[$i];
                    fputs($file,$val_a_ecrire."\r\n");
                }
                fclose($file);
            }
        }
        catch (Exception $e){
            echo "Erreur dans l'ecriture du fichier";
        }
    }

    public function compare (Ordre $o1, Ordre $o2) {
        return $o1->getDebut() - $o2->getDebut();  // use your logic, Luke
    }


        // affiche la liste des ordres
    public function liste(){
        usort($this->listOrdre,array($this,"compare"));
        echo "LISTE DES ORDRES\n";
        echo "ID DEBUT DUREE PRIX\n";
        echo "------------------------------\n";
        for($i=0; $i<sizeof($this->listOrdre); $i++) {
            $ordre = $this->listOrdre[$i];
            $this->afficherOrdre($ordre);
        }
        echo "------------------------------\n";
    }

    public function afficherOrdre(Ordre $ordre){
            echo ''. $ordre->getId() . ';' . $ordre->getDebut()  . ';' . $ordre->getDuree() . ';'. $ordre->prix() . PHP_EOL;
    }
    // Ajoute un ordre; le CA est recalculé en conséquence
    public function ajouterOrdre(){
        echo "AJOUTER UN ORDRE \r\n";
        echo "FORMAT = ID;DEBUT;FIN;PRIX\r\n";
        $line = trim(strtoupper(fgets(STDIN)));
        $champs = explode(';', $line);
        $chp1 = $champs[0];
        $chp2 = intval($champs[1]);
        $champ3 = intval($champs[2]);
        $chp4 = doubleval($champs[3]);
        $ordre = new Ordre($chp1, $chp2, $champ3, $chp4);
        $this->listOrdre[] = $ordre;
        $this->writeOrdres("ORDRES.CSV");
    }
    // MAJ du fichier
    public function suppression(){
        echo "SUPPRIMER UN ORDRE \r\n";
        echo "ID:\r\n";
        $id = trim(strtoupper(fgets(STDIN)));
        $offset = -1;
        $newList = [];
        for ($i=0; $i<count($this->listOrdre);$i++){
            $o = $this->listOrdre[$i];
            if ($o->getId() !== $id){
                $newList[] = $this->listOrdre[$i];
            }
        }
        $this->listOrdre = $newList;
        $this->writeOrdres("ORDRES.CSV");
    }

        private function ca($ordres, $debug)
        {
            // si aucun ordre, job done, TROLOLOLO..
            if (count($ordres) === 0)
                return 0;
            $order = $ordres[0];
            // attention ne marche pas pour les ordres qui depassent la fin de l'année
            // voir ticket PLAF nO 4807
            $liste = array();
            for ($i=0; $i<count($this->listOrdre);$i++){
                $o = $this->listOrdre[$i];
                if ($o->getDebut()>=$order->getDebut() + $order->getDuree()) {
                    $liste[] = $o;
                }
            }
            $liste2 = array();
            for($i=1; $i<count($ordres); $i++) {
                $liste2[] = $ordres[$i];
            }
            $ca = $order->prix()+ $this->ca($liste, $debug);
            // Lapin compris?
            $ca2 = $this->ca($liste2, $debug);
            if($debug) {
                echo number_format(max([$ca, $ca2]),2);
            }
            else
                echo ".";
            return max([$ca, $ca2]); // LOL
        }




        public function calculerLeCA($debug)
        {
            echo ("CALCUL CA..\r\n");
            usort($this->listOrdre,array($this,"compare"));
            $ca = $this->ca($this->listOrdre, $debug);
            echo "CA: ".number_format($ca,2)."\r\n";
}
}
