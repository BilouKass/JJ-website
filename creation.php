<?php
session_start();

if (isset($_SESSION['username']) and isset($_SESSION['id_prof'])) {
    require 'ressources/article.php';
    require 'ressources/log_db.php';
    include "ressources/log.php";
    include "ressources/webpush.php";
    if ($_SESSION['username'] === 'root') {
        $type = 'annonce';
    } else {
        $type = 'article';
    }
    if (isset($_POST['titre']) and isset($_POST['contenu']) and isset($_SESSION['id_prof'])) {
        $art = new article();
        $art->nouvel_article($_SESSION['id_prof'], $_POST['titre'], $_POST['contenu'], $type);
        $images = $_FILES['images'];
        $r2 = $bdd->prepare("SELECT MAX(id_article) FROM article");
        $r2->execute();
        $primary = $r2->fetchAll(\PDO::FETCH_ASSOC)[0];
        if ($images['error'][0] !== 4) {
            for ($i = 0; $i < count($images['name']); $i++) {
                $break_str = explode('.', $images['name'][$i]);
                $extension = strtolower(end($break_str));

                if ($images['error'][$i] < 1 and in_array($extension, array('png', 'jpeg', 'jpg', 'gif', 'pdf', 'doc', 'docx', 'txt'))) {
                    $uniqueName = uniqid('', true);
                    $file = $uniqueName . "." . $extension;
                    move_uploaded_file($images['tmp_name'][$i], './upload/' . $file);

                    $r = $bdd->prepare("INSERT INTO images (image_name, path, article) VALUES (:img_name, :path, :article)");
                    $r->execute(array('img_name' => $images['name'][$i], 'path' => './upload/' . $file, 'article' => $primary['MAX(id_article)']));
                } else {
                    echo "<script>alert(\"{$images['name'][$i]} est invalide ou a une erreur\")</script>";
                    logging("Image Error (id_article={$primary['MAX(id_article)']}, img={$images['name']})");
                }
            }
        }
        if ($_SESSION['username'] === 'root') {
            make_notification($_POST['titre'], $primary['MAX(id_article)']);
        }
        logging("Created an article (id={$primary['MAX(id_article)']})");
        header("Location: dashboard");
        die();
    }
?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <!--<link rel="stylesheet" href="/assets/css/style_admin.css">-->
        <title>Creation D'article</title>
        <link rel="stylesheet" href="assets/css/reset.min.css" />
        <link rel="stylesheet" href="assets/css/style_header.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- test nouvelle input -->
        <script src="vendor\tinymce\tinymce\tinymce.min.js" referrerpolicy="origin"></script>
        <script>
            tinymce.init({
                selector: '#input',
                placeholder: "Écrivez votre article",
                language: 'fr_FR',
                plugins: ['lists', 'link', 'autosave', 'anchor', 'insertdatetime', 'wordcount', 'media'],
                toolbar: 'undo redo | blocks | ' + 'bold italic backcolor | bullist numlist outdent indent | restoredraft',
                //file_picker_types: 'image',
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
                            const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            const base64 = reader.result.split(',')[1];
                            const blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);

                            /* call the callback and populate the Title field with the file name */
                            cb(blobInfo.blobUri(), {
                                title: file.name
                            });
                        });
                        reader.readAsDataURL(file);
                    });

                    input.click();
                },
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
            });
        </script>
        <link rel="stylesheet" href="assets/css/creation_art.css">
    </head>

    <body>
        <?php
        require "ressources/navbar.php";
        ?>
        <form method="post" action="creation" style="text-align: center;" enctype="multipart/form-data"><br/>
            <div class="but">
                <div class="but1" style="padding:15px;">
                    <input class="limit-50" type="text" placeholder="titre" name="titre" style="width:75%;" required />
                </div>
                <div class="but2" style="padding: 15px; text-align:center">
                    <textarea name="contenu" id="input"></textarea><br>
                </div>
                <div class="container">
                    <div class="but3">
                        <input class="file" id="file" type="file" name="images[]" multiple accept=" .png, .jpg, .jpeg, .gif, .pdf, .txt, .docx,">
                        <label for="file">selectionnez vos fichiers</label><br>
                    </div>
                    <div id="preview" style="display: inline-block;"></div>
                </div>
                <div class="but4">
                    <button class="btn"><i class="fa fa-upload" style="margin-right: 6px; ;"></i>Envoyer</button>
                </div>
            </div>
        </form>
    </body>
    <script src="assets/js/apercu.js"></script>

<?php } else {
    header('Location: index');
    die();
} ?>

    </html>