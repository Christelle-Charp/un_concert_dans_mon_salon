<?php

/*

Templates de fragment

Rôle : Mettre en forme l'affichage de la div liste_messages
Paramètre : 
    $conversation: objet conversation contenant les messages à afficher
*/
//Récupérer $list qui contient les messages de la conversation
$list = $conversation->getMessages();
$utilisateur = utilisateurConnecte();
$role = $utilisateur->get("type_utilisateur");
$expediteur = "";

?>
<?php
    //Pour chaque ligne du tableau $list, je crée un tr
    foreach ($list as $message) {
        if($role == 1){
            //Si mon utilisateur est un artiste:
            //Je récupere l'id_artiste et je le compare à l'attribut expediteur du message
            $id_artiste = $utilisateur->get("artiste")->id();
            $id_expediteur = $message->get("expediteur");
            if ($id_artiste == $id_expediteur){
                //Si ca correspond ca veut dire que l'utilisateur connecté est expéditeur du message
                $expediteur = "Moi";
            } else {
                //Sinon, l'utilisateur est destinataire
                $expediteur = $conversation->get("organisateur")->get("nom");
            }
        }else {
            //Si mon utilisateur est un organisateur:
            //Je récupere l'id_organisateur et je le compare à l'attribut expediteur du message
            $id_organisateur = $utilisateur->get("organisateur")->id();
            $id_expediteur = $message->get("expediteur");
            if ($id_organisateur == $id_expediteur){
                //Si ca correspond ca veut dire que l'utilisateur connecté est expéditeur du message
                $expediteur = "Moi";
            } else {
                //Sinon, l'utilisateur est destinataire
                $expediteur = $conversation->get("artiste")->get("nom");
            }
        }
        echo "<tr>";
        echo "<td>" . htmlentities($expediteur) . "</td>";
        echo "<td>" . htmlentities($message->get("creation")) . "</td>";
        echo "<td>" . htmlentities($message->get("contenu")) . "</td>";
        echo "<td><button onclick='demanderMessage(" . json_encode($message->id()) . ")'>Répondre</button></td>";//json_encode est mieux que htmlentities() pour la transmission de données en javascript
        echo "</tr>";
    }
?>