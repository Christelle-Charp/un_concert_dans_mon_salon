<?php

/*

Templates de fragment

Rôle : Mettre en forme l'affichage de la div liste_organisateurs
Paramètre : 
    Neant
*/
//Créer le tableau $liste qui contient toutes les conversations en cours de l'artiste
?>
<table>
    <thead>
        <tr>
            <th>Organisateur</th>
            <th>Ville</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <?php
            //Pour chaque ligne du tableau $list, je crée un tr
            foreach ($list as $conversation) {
                echo "<tr>";
                echo "<td>" . htmlentities($conversation->get("organisateur")->get("nom")) . "</td>";
                echo "<td>" . htmlentities($conversation->get("organisateur")->get("ville")) . "</td>";
                echo "<td>" . htmlentities($conversation->get("organisateur")->get("description_salle")) . "</td>";
                echo "</tr>";
            }
        ?>
    </tbody>
</table>