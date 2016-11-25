<?php


class Ordre{

    private $id;
    private $debut;
    private $duree;
    private $prix;

    public function __construct($id, $debut, $duree, $prix)
    {
        $this->id = $id;
        $this->debut = $debut; // au format AAAAJJJ par exemple 25 février 2015 = 2015056
        $this->duree = $duree;
        $this->prix = $prix;
    }

    //id de l'ordre
    public function getId() {
       return $this->id;
    }
    // debut
    public function getDebut() {
        return $this->debut;
    }
    // duree
    public function getDuree() {
        return $this->duree;
    }
    // valeur
    public function prix() {
        return $this->prix;
    }
    public function compareTo(Ordre $other) {
        return $this->debut - $other->getDebut();
    }
}