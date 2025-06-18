<?php

/*

Templates de fragment

Rôle : Mettre en forme l'affichage de la div liste_conversations_artiste
Paramètre : 
    Neant
*/
$utilisateurConnecte = utilisateurConnecte();
// Recuperer un tableau $list qui contient toutes les conversations de l'utilisateur connecté
?>
<h2>Mes conversations</h2>
<table>
    <thead>
        <tr>
            <th>Organisateur</th>
            <th>Statut</th>
            <th>Nombre de nouveau message</th>
        </tr>
    </thead>
    <tbody>
        <?php
            //Pour chaque ligne du tableau $list, je crée un tr
            foreach ($list as $conversation) {
                $statutTexte = ($conversation->get("statut") == 1) ? "En cours" : "Archivée";
                $id_conversation=$conversation->id();
                echo "<tr onclick=\"window.location='afficher_conversation.php?id_conversation=$id_conversation'\" style=\"cursor: pointer;\">";
                echo "<td>" . htmlentities($conversation->get("organisateur")->get("nom")) . "</td>";
                echo "<td>" . htmlentities($statutTexte) . "</td>";
                echo "<td>" . htmlentities($conversation->getNbMessagesNonLus($utilisateurConnecte)) . "</td>";
                echo "</tr>";
            }
        ?>
    </tbody>
    <div class="cta">
        <button onclick="demanderConversationsArchiveesArtiste(<?= htmlentities($utilisateurConnecte->id()) ?>)">Conversations Archivées</button>
        <button onclick="demanderConversationsEncoursArtiste(<?= htmlentities($utilisateurConnecte->id()) ?>)">Conversation En cours</button>
    </div>
</table>