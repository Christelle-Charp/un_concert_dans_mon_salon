<?php

/*

Templates de fragment

Rôle : Mettre en forme l'affichage de la div formulaire_message
Paramètre : 
    $message
*/
$id_conversation = $id_conversation ?? "";
if (!$id_conversation) {
    $conversation = $message->get("conversation");
    if ($conversation && method_exists($conversation, "id")) {
        $id_conversation = $conversation->id();
    }
}
?>
<h3>Message:</h3>
<p><?= htmlentities($message->get("contenu"))  ?></p>
<form action="envoyer_message.php?id_conversation=<?= $id_conversation ?>" method="POST">
    <label>Nouveau message:
        <input type="text" name="contenu" value="">
    </label>
    <input type="submit" value="Envoyer">
</form>
