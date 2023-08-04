<?php
class Classe {
    var $bdd;
    function __construct(){
        require 'log_db.php';
        include_once 'log.php';
        $this->bdd = $bdd;
    }
    public function get_prof_and_parent($classe) {
        if ($classe === null) {
            $req = $this->bdd->query("SELECT * FROM `classe` INNER JOIN prof ON prof.id_prof = classe.professeur INNER JOIN parent ON parent.id_parent = classe.parents");
            return $req->fetchAll(PDO::FETCH_ASSOC);
        }
        else{
            $req = $this->bdd->prepare("SELECT * FROM `classe` INNER JOIN prof ON prof.id_prof = classe.professeur INNER JOIN parent ON parent.id_parent = classe.parents WHERE id = :id");
            $req->execute(array('id'=>$classe));
            return $req->fetchAll(PDO::FETCH_ASSOC);
        } 
    }

    private function add_prof($prof_username, $prof_password) {
        $pass = password_hash($prof_password, PASSWORD_DEFAULT);
        $r = $this->bdd->prepare("INSERT INTO `prof` (`nom`, `mdp`) VALUES (:username, :pass)");
        $r->execute(array('username'=>$prof_username, 'pass'=>$pass));
        logging("à créer un prof");
        $q = $this->bdd->query('SELECT MAX(id_prof) FROM prof');
        $return_val = $q->fetchAll();
        return $return_val;
    }

    private function add_parent($parent_username, $parent_password) {
        $pass = password_hash($parent_password, PASSWORD_DEFAULT);
        $r = $this->bdd->prepare("INSERT INTO `parent` (`user`, `password`) VALUES (:username, :pass)");
        $r->execute(array('username'=>$parent_username, 'pass'=>$pass));
        logging("à créer un prof");
        $q = $this->bdd->query('SELECT MAX(id_parent) FROM parent');
        $return_val = $q->fetchAll();
        return $return_val;
    }

    public function add_new_classe($prof_username, $prof_password, $parent_username, $parent_password, $classe) {
        $id_prof = $this->add_prof($prof_username, $prof_password);
        $id_parent = $this->add_parent($parent_username, $parent_password);
        $request = $this->bdd->prepare("INSERT INTO classe (niveau, professeur, parents) VALUES (:niveau, :prof, :parent)");
        $request->execute(array('niveau'=>$classe, 'prof'=>$id_prof[0][0], 'parent'=>$id_parent[0][0]));
    }
    public function delete_classe($id_classe) {
        $pre_req = $this->bdd->prepare("SELECT professeur, parents FROM classe WHERE id_classe = :id_classe");
        $pre_req->execute(array('id_classe'=>$id_classe));
        $val = $pre_req->fetchAll(PDO::FETCH_ASSOC)[0];
        $request = $this->bdd->prepare("DELETE FROM classe WHERE id_classe = :id_classe;
                                        DELETE FROM prof WHERE id_prof = :id_prof;
                                        DELETE FROM `parent` WHERE id_parent = :id_parents;");
        $request->execute(array('id_classe'=>$id_classe, 'id_prof'=>$val['professeur'], 'id_parents'=>$val['parents']));
    }
    
    public function edit_classe($id_classe=null, $prof_username=null, $prof_password=null, $parent_username=null, $parent_password=null, $classe=null) {
        if ($id_classe !== null) {
        if ($classe !== null) {
            $request_classe = $this->bdd->prepare("UPDATE classe SET niveau = :classe WHERE id_classe = :id_classe");
            $request_classe->execute(array('classe'=>$classe, 'id_classe'=>$id_classe));
            }
        if ($prof_username !== null and $prof_password !== null) {
            $pass = password_hash($prof_password, PASSWORD_DEFAULT);
            $request_prof = $this->bdd->prepare("UPDATE prof, classe SET nom = :nom_prof, mdp = :password WHERE classe.professeur = prof.id_prof AND classe.id_classe = :id_classe");
            $request_prof->execute(array('nom_prof'=>$prof_username, 'password'=>$pass, 'id_classe'=>$id_classe));
            if ($request_prof->errorCode() !== '00000') {
                return 400;
            }
        }
        if ($parent_username !== null and $parent_password !== null) {
            $pass = password_hash($parent_password, PASSWORD_DEFAULT);
            $request_parent = $this->bdd->prepare("UPDATE parent, classe SET user = :user, password = :password WHERE classe.parents = parent.id_parent AND classe.id_classe = :id_classe");
            $request_parent->execute(array('user'=>$parent_username, 'password'=>$pass, 'id_classe'=>$id_classe));
            if ($request_parent->errorCode() !== '00000') {
                return 400;
            }
            return 200;
        }
        }
    }
    public function get_id_prof_from_parent($id_parent) {
        $query = $this->bdd->prepare("SELECT professeur FROM classe WHERE parents = :parent_id");
        $query->execute(array('parent_id'=>$id_parent));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_id_parent_from_prof($id_prof) {
        $query = $this->bdd->prepare("SELECT parent FROM classe WHERE professeur = :prof_id");
        $query->execute(array('prof_id'=>$id_prof));
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}