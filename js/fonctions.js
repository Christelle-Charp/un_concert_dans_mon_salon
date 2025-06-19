/*
Librairies des fonctions spécifiques générales du projet
*/
let formulaireAffiche = false;

document.addEventListener("DOMContentLoaded", function() {
    //Afficher les messages de la conversation au chargement de la page et ensuite toutes les 10 secondes
    //Récupérer l'id_conversation
    let container = document.querySelector('.contenair[data-conversation-id]');
    if (container){
        let id_conversation = container.getAttribute('data-conversation-id');

        // Charger les messages au chargement de la page
        demanderMessagesConversation(id_conversation);

        // Actualiser les messages toutes les 10 secondes
        setInterval(() => {
        demanderMessagesConversation(id_conversation);
        }, 10000);
    }
    
    //Afficher les conversations en cours de l'organisateur  au chargement de la page
    //Récupérer l'id_utilisateur
    let container1 = document.querySelector('.contenair[data-profil-organisateur-utilisateur-id]');
    if (container1) {
        let id_utilisateur = container1.getAttribute('data-profil-organisateur-utilisateur-id');
        demanderConversationsEncoursOrga(id_utilisateur);
    } 

    //Afficher les conversations en cours de l'artiste et la liste de ses salles au chargement de la page
    //Récupérer l'id_utilisateur
    let container2 = document.querySelector('.contenair[data-profil-artiste-utilisateur-id]');
    if(container2){
        let id_utilisateur = container2.getAttribute('data-profil-artiste-utilisateur-id');
        demanderConversationsEncoursArtiste(id_utilisateur);
    }
    //Afficher les messages non lu dans les headers
    demanderMessagesNonLu();

    // Actualiser les messages toutes les minutes

        setInterval(() => {
        demanderMessagesNonLu();
        }, 60000);

});

function demanderMessage(id_message) {
    // Rôle : demander au serveur le détail d'un message et tansmettre le retour à afficherMessage
    // Paramètres : 
    //      id_message : id du message et de la conversation si pas de message
    // Retour : néant
    let container = document.querySelector('.contenair[data-conversation-id]');
    let id_conversation = container ? container.getAttribute('data-conversation-id') : "";

    //Construire l'url à appeler (celle du controleur ajax)
    let url = "recuperer_message_ajax.php?id_message=" + id_message;
    //Si pas de message il faut quand même afficher le formulaire pour le 1er message
    if(id_message == 0 && id_conversation) {
        url += "&id_conversation=" + encodeURIComponent(id_conversation);
    }
    fetch(url)
        .then(function(response){
            return response.text();     //Le retour de la fonction est du text brut pour le HTML
        })
        .then(afficherMessage); 
}

function afficherMessage(fragment) {
    // Rôle : affiche dans le cadre #formulaire_message le contenu html reçu
    // Paramètres : 
    //      fragment : code HTML à afficher
    // Retour : néant

    //On remplace le contenu de la div #formulaire_message par le fragment
    document.getElementById("formulaire_message").innerHTML = fragment;
}

function demanderMessagesConversation(id_conversation) {
    // Rôle : demander au serveur le détail des messages d'une conversation toutes les 10 secondes et tansmettre le retour à afficherMessagesConversation
    // Paramètres : 
    //      id_conversation : id de la conversation
    // Retour : néant

    //Construire l'url à appeler (celle du controleur ajax)
    let url = "recuperer_conversation_ajax.php?id_conversation=" + id_conversation;
    fetch(url).then(function(response){
        return response.text();     //Le retour de la fonction est du text brut pour le HTML
    }).then(afficherMessagesConversation); 
}

function afficherMessagesConversation(fragment) {
    // Rôle : affiche dans le cadre #liste_messages le contenu html reçu
    // Paramètres : 
    //      fragment : code HTML à afficher
    // Retour : néant
    let listeMessages = document.getElementById("liste_messages");
    //On remplace le contenu de la div #liste_messages par le fragment
    listeMessages.innerHTML = fragment;
    //Afficher le formulaire seulement une fois
    if (!listeMessages.hasChildNodes() && !formulaireAffiche) {
        demanderMessage(0);
        formulaireAffiche = true; // une fois que le formulaire s'affiche, on change sa valeur pour éviter qu'il ne se réactualise
    }
}

function demanderConversationsArchiveesOrga(id_utilisateur) {
    // Rôle : demander au serveur les conversations archivées d'un organisateur et tansmettre le retour à afficherConversationsOrga
    // Paramètres : 
    //      id_utilisateur : id de l'utilisateur
    // Retour : néant

    //Construire l'url à appeler (celle du controleur ajax)
    let url = "recuperer_conversations_archivees_orga_ajax.php?id_utilisateur" + id_utilisateur;
    fetch(url)
        .then(function(response){
            return response.text();     //Le retour de la fonction est du text brut pour le HTML
        })
        .then(afficherConversationsOrga); 
}

function demanderConversationsEncoursOrga(id_utilisateur) {
    // Rôle : demander au serveur les conversations en cours d'un organisateur et tansmettre le retour à afficherConversationsOrga
    // Paramètres : 
    //      id_utilisateur : id de l'utilisateur
    // Retour : néant

    //Construire l'url à appeler (celle du controleur ajax)
    let url = "recuperer_conversations_encours_orga_ajax.php?id_utilisateur=" + id_utilisateur;
    fetch(url)
        .then(function(response){
            return response.text();     //Le retour de la fonction est du text brut pour le HTML
        })
        .then(afficherConversationsOrga); 
}

function afficherConversationsOrga(fragment) {
    // Rôle : affiche dans le cadre #liste_conversations_organisateur le contenu html reçu
    // Paramètres : 
    //      fragment : code HTML à afficher
    // Retour : néant

    //On remplace le contenu de la div #liste_conversations_organisateur par le fragment
    document.getElementById("liste_conversations_organisateur").innerHTML = fragment;
}
//
function demanderConversationsArchiveesArtiste(id_utilisateur) {
    // Rôle : demander au serveur les conversations archivées d'un artiste et tansmettre le retour à afficherConversationsArtiste
    // Paramètres : 
    //      id_utilisateur : id de l'utilisateur
    // Retour : néant

    //Construire l'url à appeler pour le fragment liste_conversation_artiste
    let urlConversations = "recuperer_conversations_archivees_artiste_ajax.php?id_utilisateur=" + id_utilisateur;
    //Construire l'url à appeler pour le fragment liste_organisateur
    let urlOrganisateurs = "recuperer_organisateurs_pour_artiste_ajax.php?id_utilisateur=" + id_utilisateur;
    //Recuperer les conversations
    fetch(urlConversations)
        .then(function(response){
            return response.text();     //Le retour de la fonction est du text brut pour le HTML
        })
        .then(function(fragment){
            afficherConversationsArtiste("liste_conversations_artiste", fragment)
        }); 
    //Recuperer les organisateurs
    fetch(urlOrganisateurs)
        .then(function(response){
            return response.text();     //Le retour de la fonction est du text brut pour le HTML
        })
        .then(function(fragment){
            afficherConversationsArtiste("liste_organisateurs", fragment)
        });
}

function demanderConversationsEncoursArtiste(id_utilisateur) {
    // Rôle : demander au serveur les conversations en cours d'un artiste et tansmettre le retour à afficherConversationsArtiste
    // Paramètres : 
    //      id_utilisateur : id de l'utilisateur
    // Retour : néant

    //Construire l'url à appeler pour le fragment liste_conversation_artiste
    let urlConversations = "recuperer_conversations_encours_artiste_ajax.php?id_utilisateur=" + id_utilisateur;
    //Construire l'url à appeler pour le fragment liste_organisateur
    let urlOrganisateurs = "recuperer_organisateurs_pour_artiste_ajax.php?id_utilisateur=" + id_utilisateur;
    //Recuperer les conversations
    fetch(urlConversations)
        .then(function(response){
            return response.text();     //Le retour de la fonction est du text brut pour le HTML
        })
        .then(function(fragment){
            afficherConversationsArtiste("liste_conversations_artiste", fragment)
        }); 
    //Recuperer les organisateurs
    fetch(urlOrganisateurs)
        .then(function(response){
            return response.text();     //Le retour de la fonction est du text brut pour le HTML
        })
        .then(function(fragment){
            afficherConversationsArtiste("liste_organisateurs", fragment)
        }); 
}

function afficherConversationsArtiste(idFragment, fragment) {
    // Rôle : affiche dans les cadres #liste_organisateurs et #liste_conversations_artiste le contenu html reçu
    // Paramètres : 
    //      fragment : code HTML à afficher
    // Retour : néant

    let aRemplir = document.getElementById(idFragment);
    //On remplace le contenu de la div par le fragment
    aRemplir.innerHTML = fragment;
}


function demanderMessagesNonLu() {
    // Rôle : demander au serveur les messages non lu de l'utilisateur connecté et tansmettre le retour à afficherMessagesNonLu
    // Paramètres : 
    //      Néant
    // Retour : néant

    //Construire l'url à appeler (celle du controleur ajax)
    let url = "recuperer_messages_non_lu.php?";
    fetch(url)
        .then(function(response){
            return response.text();     //Le retour de la fonction est du text brut pour le HTML
        })
        .then(afficherMessagesNonLu); 
}

function afficherMessagesNonLu(fragment) {
    // Rôle : affiche dans le cadre #compteurMessage le contenu html reçu
    // Paramètres : 
    //      fragment : code HTML à afficher
    // Retour : néant

    //On remplace le contenu de la div #compteurMessage par le fragment
    document.getElementById("compteurMessage").innerHTML = fragment;
}