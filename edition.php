<?php
session_start();
require "ressources/article.php";
include "ressources/log.php";
if (isset($_SESSION['username']) and isset($_SESSION['id_prof'])) {
    $article = new article();
    $id_article = $_POST['modifier'];

    if (isset($_POST['titre']) and isset($_POST['contenu'])) {
        echo "<script>alert('ok')</script>";
        $article->edit($id_article, $_POST['titre'], $_POST['contenu']);
        logging("Edited an article (id=$id_article)");
        header('Location: dashboard.php');
        die();
    }
    if (!isset($_POST['modifier'])) {
        header('Location: dashboard.php');
        die();
    }
    $content = $article->lire_article($id_article);
    $edit_title = $content[0]['titre'];
    $edit_text = $content[0]['article_content'];
 ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Creation D'article</title>
    <link rel="stylesheet" href="assets/css/reset.min.css" />
    <link rel="stylesheet" href="assets/css/style_header.css" />
    <link rel="stylesheet" href="assets/css/header-1.css" />
    <link rel="stylesheet" href="assets/css/modif.css">
    <script src="vendor\tinymce\tinymce\tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#input',
            placeholder : "Écrivez votre article",
            language: 'fr_FR',
            plugins: ['lists', 'link', 'autosave','anchor','insertdatetime', 'wordcount', 'media', 'image'
  ],
  toolbar: 'undo redo | blocks | ' + 'bold italic backcolor | bullist numlist outdent indent | restoredraft',
  file_picker_types: 'image',
  /* and here's our custom image picker*/
  file_picker_callback: (cb, value, meta) => {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');

    input.addEventListener('change', (e) => {
      const file = e.target.files[0];

      const reader = new FileReader();
      reader.addEventListener('load', () => {
        /*
          Note: Now we need to register the blob in TinyMCEs image blob
          registry. In the next release this part hopefully won't be
          necessary, as we are looking to handle it internally.
        */
        const id = 'blobid' + (new Date()).getTime();
        const blobCache =  tinymce.activeEditor.editorUpload.blobCache;
        const base64 = reader.result.split(',')[1];
        const blobInfo = blobCache.create(id, file, base64);
        blobCache.add(blobInfo);

        /* call the callback and populate the Title field with the file name */
        cb(blobInfo.blobUri(), { title: file.name });
      });
      reader.readAsDataURL(file);
    });

    input.click();
  },
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
});
    </script>
</head>

<body>
    <?php
    require "ressources/navbar.php";?>
    <form method="post" action="edition.php" style="text-align: center;"><br />
        <div class="but">
            <div class="but1" style="padding:15px;">
                <input type="text" placeholder="titre" name="titre" style="width:500px;" value="<?=$edit_title?>"
                    required /><br>
            </div>
            <div class="but2" style="padding: 15px;">
                <textarea placeholder="contenu" name="contenu" style="margin-left:auto; margin-right:auto; width:75%;" id="input"><?=$edit_text?></textarea><br>
            </div>
            <div class="but4">
                <button class="btn" name="modifier" value="<?=$id_article?>"><i class="fa fa-upload" style="margin-right: 6px; ;"></i>Envoyer</button>
            </div>
        </div>
    </form>
</body>

</html>
<?php    
}?>