<?php
/*
Template de page complète : Mettre en forme l'affichage de la page d'accueil
Paramètres : 
    $list: liste de tous les artistes
*/
?>
<!DOCTYPE html>
<html lang="fr">
<?php // Inclure le "head"
    $titre = "Accueil";       // Valoriser la variable $titre atendue par le fragment
    include "templates/fragments/head.php";
?>
<body>
    <?php // Inclure le "header_accueil"
    include "templates/fragments/header_accueil.php";
    ?>
    <div class="contenair">
        <div class="row">
            <h1><?= $titre ?></h1>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quo nam aut, eveniet, fugit nihil placeat ullam in itaque laborum rerum ratione cupiditate aperiam provident ad ea explicabo quasi! Blanditiis totam, eum sapiente, iusto incidunt officiis laborum tenetur maiores eligendi quidem, itaque aut doloremque. Hic laboriosam dolore voluptates aliquam consequuntur dolor odit nihil culpa labore recusandae voluptatem, incidunt eum enim qui odio modi tenetur sed temporibus velit dicta. Optio, officia eligendi sed at quod eius ad consectetur consequatur dolorum unde adipisci? Totam quod velit quam autem magni voluptatibus labore, voluptas quis repudiandae corporis exercitationem, accusamus impedit iste ducimus beatae adipisci vitae modi neque. Atque repellendus minima nobis laudantium commodi? Est, error? Veniam incidunt, fuga obcaecati, molestias ratione praesentium porro aliquam quis neque ipsam adipisci, illo sint possimus eius ipsum alias vel quos eligendi odit accusamus inventore in dolor consequatur error! Cumque illum explicabo repellendus perferendis nulla. Distinctio nemo dicta voluptates eveniet beatae repellendus adipisci sunt, optio placeat velit enim aspernatur neque dignissimos debitis harum magnam eligendi ut. Laboriosam quisquam in veritatis totam, quasi deserunt id vel atque nam recusandae similique, libero minus esse quam assumenda inventore provident, dolor rerum cum nostrum! Ipsa rem, libero magni vel sit neque? Optio, non deleniti.</p>
            <div class="tab-artistes">
                <h2>Liste des artistes</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nom de scène</th>
                            <th>Type de musique</th>
                            <th>Localisation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //Pour chaque ligne du tableau $list, je crée un tr
                            foreach ($list as $artiste) {
                                include "templates/fragments/tr_liste_accueil.php";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</body>
</html>