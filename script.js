// Récupérer les éléments de la fenêtre modale
var modal = document.getElementById("modal");
var span = document.getElementsByClassName("close")[0];
var matiereTitre = document.getElementById("matiere-titre");
var matiereInput = document.getElementById("matiere");
var formAjout = document.getElementById("form-ajout");

// Fonction pour ouvrir la fenêtre modale
function ouvrirModal(matiere) {
    modal.style.display = "block";
    matiereTitre.textContent = "Ajouter une entrée pour " + matiere;
    matiereInput.value = matiere;
}

// Fermer la fenêtre modale lorsque l'utilisateur clique sur <span> (x)
span.onclick = function () {
    modal.style.display = "none";
}

// Fermer la fenêtre modale lorsque l'utilisateur clique en dehors de la fenêtre modale
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Événement pour ouvrir la fenêtre modale lorsque l'utilisateur clique sur le bouton "+"
var boutonsAjout = document.querySelectorAll('.ajouter');
boutonsAjout.forEach(function (bouton) {
    bouton.addEventListener('click', function () {
        var matiere = bouton.getAttribute('data-matiere');
        ouvrirModal(matiere);
    });
});
