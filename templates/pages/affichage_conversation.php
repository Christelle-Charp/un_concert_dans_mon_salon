<?php
/*
Template de page complète : Mettre en forme l'affichage de la page d'une conversation
Paramètres : 
    $conversation: objet conversation contenant les messages à afficher
*/
//Je récupere les infos de mon $utilisateurConnecte 
$utilisateur = utilisateurConnecte();
$role = $utilisateur->get("type_utilisateur");
//Je cherche qui est le correspondant
if($role == 1) {
    $correspondant = $conversation->get("organisateur")->get("nom");
}else{
    $correspondant = $conversation->get("artiste")->get("nom");
}
?>
<!DOCTYPE html>
<html lang="fr">
<?php // Inclure le "head"
    $titre = "Conversation en cours";       // Valoriser la variable $titre atendue par le fragment
    include "templates/fragments/head.php";
?>
<body>
    <div class="contenair" data-conversation-id="<?=$conversation->id() ?>">
        <div class="row">
            <div class="gauche-conversation">
                <h2>Votre correspondant: <?= $correspondant ?></h2>
                <div class="container-messages">
                    <h3>Les messages:</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Expéditeur</th>
                                <th>Date</th>
                                <th>Contenu</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="liste_messages">
                        </tbody>
                    </table>
                </div>
                <div class="cta">
                    <button><a href="modifier_statut_conversation.php?id_conversation=<?= $conversation->id() ?>">Changer le statut de la conversation</a></button>
                </div>
            </div>
            <div class="droite-conversation">
                <div id="formulaire_message"></div>
            </div>
        </div>
    </div>
    <script src="js/fonctions.js"></script>
</body>
</html>