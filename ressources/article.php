<?php
class article
{
    public $bdd;
    public function __construct(){
        require 'log_db.php';
        include_once 'log.php';
        $this->bdd = $bdd;
    }
    public function nouvel_article($auteur, $titre, $contenu, $type) {
         
        if (empty($titre) or empty($contenu) or empty($auteur)) { # Si jamais il manque un argument, la fonction ne s'exécute pas
          logging('Incomplete req data');
          return;
      }
    
    $req = $this->bdd->prepare("INSERT INTO article (auteur, titre, article_content, type) VALUES (:auteur,:titre,:article, :type)");
    $req->execute(array('auteur' => $auteur,'titre'=>$titre, 'article'=>$contenu, 'type'=>$type));

    logging($req->$http_response_header);
    }
    public function lire_root($id_prof) {
      if (empty($id_prof)) {
        return;
      }
      if ($id_prof != 1000) {
          $articles = $this->bdd->prepare("SELECT titre, nom, id_article, article_content,type,date, views from article 
          INNER JOIN prof ON article.auteur = prof.id_prof WHERE article.auteur = :id_prof ORDER BY date DESC");}
          else {
          $articles = $this->bdd->prepare("SELECT titre, nom, id_article, article_content,date, views from article 
                                          INNER JOIN prof ON article.auteur = prof.id_prof ORDER BY date DESC");
          }
          $articles->execute(array('id_prof'=>intval($id_prof)));
          $c = $articles->fetchAll(\PDO::FETCH_ASSOC);
          return $c;
    }
    public function delete($id) {
      if (isset($id)) {
        $req = $this->bdd->prepare("DELETE FROM article WHERE id_article = :id");
        $req->execute(array('id'=>$id));
      }
    }
    public function lire_article($id_article) {
      $r = $this->bdd->prepare('SELECT titre,id_article,article_content,date,nom,path,image_name,type from article INNER JOIN prof ON prof.id_prof = article.auteur
      LEFT JOIN images ON images.article = article.id_article WHERE id_article = :id_article');
      $r->execute(array('id_article'=>intval($id_article)));
      $content = $r->fetchAll(\PDO::FETCH_ASSOC);
      return $content;
    }
    public function edit($id_article, $titre, $text){
      $r = $this->bdd->prepare('UPDATE article SET titre = :new_titre, article_content= :new_text WHERE id_article = :id_article');
      $r->execute(array('new_titre'=>$titre,'new_text'=>$text,'id_article'=>$id_article));
    }

    public function get_and_sort_article($id_prof) {
        $articles = $this->bdd->prepare("SELECT titre, nom, id_article, article_content,date from article 
        INNER JOIN prof ON article.auteur = prof.id_prof WHERE auteur = :auteur ORDER BY date DESC");
        
        $articles->execute(array('auteur'=>$id_prof));
        $content = $articles->fetchAll(\PDO::FETCH_ASSOC);
        return $content;
    }
    public function sort_annonce() {
      $articles = $this->bdd->prepare("SELECT titre, id_article, article_content, date from article 
      WHERE type = 'annonce' ORDER BY date DESC");
      $articles->execute();
      $d = $articles->fetchAll(\PDO::FETCH_ASSOC);
      return $d;
    }
    public function add_viewer($id) {
      $article = $this->bdd->prepare("UPDATE article SET views = views + 1 WHERE id_article = :id");
      $article->execute(array('id'=>$id));
    }
}

function change_url2button($string){
  $extension = array('.com', '.net', '.fr', '.gouv', '.org');
  $lenght = strlen($string);
  $count = substr_count($string, 'http');
  for ($i=0; $i < $count; $i++) {
    $link = '';
    $pos= strpos($string, 'http');
    while (!($string[$pos] === ' ') and $pos <= $lenght) {
      $link .= $string[$pos];
      $pos++;
      if (!isset($string[$pos])) {
        break;
      }
    }
    $text_short = str_replace($extension, '',explode('/', $link)[2]);
    $str = sprintf('<a class="text-link" href="%s" target="_blank">%s</a>', $link, $text_short);
    $string = str_replace($link, $str, $string);

  }
  return $string;
}
?>