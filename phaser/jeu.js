document.addEventListener('DOMContentLoaded', () => {
    // Sélectionner les éléments du DOM pour le son et le contrôle
    const backgroundMusic = document.getElementById('background-music');
    const soundToggleButton = document.getElementById('sound-toggle-button');
    const soundIcon = document.getElementById('sound-icon');
    let isFormSubmitted = false; // Pour vérifier si le formulaire a été soumis une fois

    // Fonction pour soumettre le formulaire de score
    function submitScoreForm(event) {
        event.preventDefault(); // Empêcher l'envoi standard du formulaire

        if (isFormSubmitted) return; // Si le formulaire est déjà soumis, empêcher une nouvelle soumission

        const form = document.getElementById('save-score-form');
        const formData = new FormData(form);

        const pseudo = formData.get('pseudo'); // Récupérer le pseudo
        const temps = formData.get('temps');   // Récupérer le temps

        // Log des données récupérées pour déboguer
        console.log('Pseudo:', pseudo, 'Temps:', temps);

        fetch('jeu.php', { // Envoyer les données au fichier jeu.php
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                console.log('Score envoyé avec succès :', data);
                if (pseudo && temps) {
                    updateScoreBoard(pseudo, temps); // Mettre à jour l'affichage du tableau des scores
                    isFormSubmitted = true;
                    document.getElementById('save-score-form').style.display = 'none'; // Cacher le formulaire
                } else {
                    console.error('Le pseudo ou le temps est indéfini.');
                }
            })
            .catch(error => {
                console.error('Erreur lors de l\'envoi du score :', error);
            });
    }

    // Fonction pour démarrer le jeu
    function startGame() {
        // Réinitialiser le jeu pour un nouveau départ
        resetGameVariables();
        welcomeScreen.style.display = 'none';
        gameScreen.style.display = 'block';
        startTimer();
        initGame();

        // Démarrer la musique lorsque le jeu commence
        backgroundMusic.play();
    }

    // Ajouter un écouteur pour activer/désactiver le son
    soundToggleButton.addEventListener('click', () => {
        if (backgroundMusic.muted) {
            // Si la musique est coupée, la réactiver
            backgroundMusic.muted = false;
            soundIcon.textContent = '🔊'; // Changer l'icône pour le son activé
        } else {
            // Si la musique est activée, la couper
            backgroundMusic.muted = true;
            soundIcon.textContent = '🔇'; // Changer l'icône pour le son coupé
        }
    });

    // Fonction pour gérer la fin du jeu (victoire ou défaite)
    function endGame(won) {
        clearInterval(timer);
        gameScreen.classList.add('inactive');
        endGameModal.style.display = 'flex'; // Affiche le modal de fin de jeu

        if (won) {
            console.log('Formulaire de score affiché');
            endGameMessage.textContent = 'Bravo !'; // Message de victoire
            topScores.push(timeLeft);
            launchConfetti(); // Afficher les confettis en cas de victoire

            // Afficher le formulaire et assigner la valeur du temps au champ caché
            if (!isFormSubmitted) { // N'affiche le formulaire que s'il n'a pas encore été soumis
                document.getElementById('save-score-form').style.display = 'block';
                document.getElementById('temps').value = timeLeft; // Mettre le temps dans le champ caché du formulaire
            }

        } else {
            endGameMessage.textContent = 'Perdu, désolé 😢';
            document.getElementById('save-score-form').style.display = 'none'; // Cacher le formulaire si défaite
            launchDefeatAnimation();
        }
    }

    // Confettis de ballons de rugby pour une victoire
    function launchConfetti() {
        const confettiContainer = document.createElement('div');
        confettiContainer.classList.add('confetti-container');
        document.body.appendChild(confettiContainer);

        for (let i = 0; i < 30; i++) {
            const confetti = document.createElement('span');
            confetti.classList.add('confetti');
            confetti.textContent = '🏉';
            confetti.style.left = `${Math.random() * 100}vw`;
            confetti.style.animationDelay = `${Math.random()}s`;
            confettiContainer.appendChild(confetti);
        }

        setTimeout(() => {
            document.body.removeChild(confettiContainer);
        }, 4000);
    }

    // Icônes sablier et smiley triste pour une défaite
    function launchDefeatAnimation() {
        const defeatContainer = document.createElement('div');
        defeatContainer.classList.add('defeat-container');
        document.body.appendChild(defeatContainer);

        const icons = ['⏳', '😢'];

        for (let i = 0; i < 30; i++) {
            const defeatIcon = document.createElement('span');
            defeatIcon.classList.add('defeat-icon');
            defeatIcon.textContent = icons[Math.floor(Math.random() * icons.length)];
            defeatIcon.style.left = `${Math.random() * 100}vw`;
            defeatIcon.style.animationDelay = `${Math.random()}s`;
            defeatContainer.appendChild(defeatIcon);
        }

        setTimeout(() => {
            document.body.removeChild(defeatContainer);
        }, 4000);
    }

    // Données et éléments du DOM
    const clubsData = [
        { "image": "clubs/crec.jpeg", "titre": "Club de Rugby Educatif et Citoyen", "localité": "Nouméa" },
        { "image": "clubs/dumbea.png", "titre": "Union Rugby Club de Dumbéa", "localité": "Dumbéa" },
        { "image": "clubs/sc.jpeg", "titre": "Association Stade Calédonien", "localité": "Nouméa" },
        { "image": "clubs/rugby-mont-dore.png", "titre": "Rugby Club de Mont Dore", "localité": "Mont-Dore" },
        { "image": "clubs/viking-988.jpeg", "titre": "Vikins 988", "localité": "Nouméa" },
        { "image": "clubs/olympique-noumea.png", "titre": "Olympique de Nouméa", "localité": "Nouméa" },
        { "image": "clubs/paita.jpeg", "titre": "Le Petit Train Section Rugby", "localité": "Paita" },
        { "image": "clubs/rugby-club-cal.jpeg", "titre": "Rugby Club Calédonien", "localité": "Nouméa" },
        { "image": "clubs/magenta.jpeg", "titre": "Association Sportive de Magenta", "localité": "Nouméa" },
        { "image": "clubs/kunie.png", "titre": "Association Sportive Kunie", "localité": "Île des Pins" },
        { "image": "clubs/bourail.jpeg", "titre": "Comite Sportif Bouraillais", "localité": "Bourail" },
        { "image": "clubs/nepoui.png", "titre": "Association Omnisports Nepoui", "localité": "Poya" },
        { "image": "clubs/crevette.png", "titre": "Crevettes Musclées Nord Rugby", "localité": "Pouembout" },
        { "image": "clubs/houps.png", "titre": "Les Houps Club de Rugby de Poindimie", "localité": "Poindimie" },
        { "image": "clubs/nengone.jpeg", "titre": "Nengone Ovalie Rugby Ensemble Saisissons l’Avenir", "localité": "Maré" },
        { "image": "clubs/RCLaFoa.png", "titre": "RC La Foa", "localité": "La Foa" }
    ];

    const localitiesTopContainer = document.getElementById('localities-top-container');
    const localitiesBottomContainer = document.getElementById('localities-bottom-container');
    const clubsContainer = document.getElementById('clubs-container');
    const startButton = document.getElementById('start-button');
    const gameScreen = document.getElementById('game-screen');
    const welcomeScreen = document.getElementById('welcome-screen');
    const endGameModal = document.getElementById('end-game-modal');
    const endGameMessage = document.getElementById('end-game-message');
    const replayButton = document.getElementById('replay-button');
    const saveScoreForm = document.getElementById('save-score-form');
    const timerElement = document.getElementById('timer');
    const resetButton = document.getElementById('reset-button');

    let timer; // Chronomètre
    let timeLeft = 80; // 80 secondes
    let matchedClubs = 0; // Compteur des clubs associés
    const totalClubs = clubsData.length; // Nombre total de clubs
    let topScores = []; // Stocker le top 5 des scores
    let groupedByLocality = {}; // Stocker les localités groupées
    let isPaused = false; // Indicateur de pause

    let selectedClub = null; // Pour stocker le club sélectionné

    // Fonction pour gérer le premier toucher (sélection du club)
    function handleClubTouch(event) {
        if (isPaused) return;

        // Récupérer l'élément du club touché
        let target = event.target;
        while (!target.classList.contains('club')) {
            target = target.parentElement;
        }

        // Si un club est déjà sélectionné, on le désélectionne
        if (selectedClub) {
            selectedClub.classList.remove('selected');
        }

        // Marquer ce club comme sélectionné
        selectedClub = target;
        selectedClub.classList.add('selected'); // Ajouter une classe CSS pour indiquer la sélection
    }

    // Fonction pour gérer le second toucher (sélection de la localité)
    function handleLocalityTouch(event) {
        if (isPaused || !selectedClub) return; // Si aucun club n'est sélectionné, on ne fait rien

        let target = event.target;
        while (!target.classList.contains('locality')) {
            target = target.parentElement;
        }

        const localityName = target.dataset.locality;
        const clubId = selectedClub.dataset.club;

        // Vérifier si le club sélectionné correspond à la localité
        const clubMatchesLocality = groupedByLocality[localityName].some(club => club.titre === clubId);

        if (clubMatchesLocality) {
            target.classList.add('success');
            target.classList.remove('failure');
            selectedClub.style.display = 'none'; // Masquer le club une fois qu'il est correctement associé
            onClubMatched();
        } else {
            target.classList.add('failure');
            setTimeout(() => {
                target.classList.remove('failure');
            }, 1000);
        }

        // Désélectionner le club après le match
        selectedClub.classList.remove('selected');
        selectedClub = null;
    }

    // Initialiser la logique de toucher pour sélectionner les clubs et les localités
    function initTouchForTouchScreens() {
        const clubs = document.querySelectorAll('.club');
        const localitiesElements = document.querySelectorAll('.locality');

        // Ajouter un événement de toucher pour sélectionner un club
        clubs.forEach(club => {
            club.addEventListener('touchstart', handleClubTouch, { passive: true });
        });

        // Ajouter un événement de toucher pour sélectionner une localité
        localitiesElements.forEach(locality => {
            locality.addEventListener('touchstart', handleLocalityTouch, { passive: true });
        });
    }

    // Initialisation du jeu
    function initGame() {
        // Mélanger l'ordre des clubs de manière aléatoire
        clubsData.sort(() => Math.random() - 0.5);

        // Réinitialiser les conteneurs
        localitiesTopContainer.innerHTML = '';
        localitiesBottomContainer.innerHTML = '';
        clubsContainer.innerHTML = '';

        // Regrouper les clubs par localité
        groupedByLocality = clubsData.reduce((acc, club) => {
            if (!acc[club.localité]) {
                acc[club.localité] = [];
            }
            acc[club.localité].push(club);
            return acc;
        }, {});

        // Diviser les localités en deux groupes
        const localities = Object.keys(groupedByLocality);

        // Mélanger les localités de manière aléatoire
        localities.sort(() => Math.random() - 0.5);

        // Diviser les localités en deux groupes après mélange
        const topLocalities = localities.slice(0, Math.ceil(localities.length / 2));
        const bottomLocalities = localities.slice(Math.ceil(localities.length / 2));

        // Générer les éléments HTML pour les localités en haut et en bas
        topLocalities.forEach(locality => createLocalityElement(locality, localitiesTopContainer));
        bottomLocalities.forEach(locality => createLocalityElement(locality, localitiesBottomContainer));

        // Générer les éléments HTML pour les clubs
        clubsData.forEach(club => createClubElement(club));

        // Logique de glisser-déposer
        initDragAndDrop();

        // Initialiser la gestion des interactions tactiles
        initTouchForTouchScreens();
    }

    // Fonction pour créer un élément de localité
    function createLocalityElement(locality, container) {
        const localityElement = document.createElement('div');
        localityElement.className = 'locality';
        localityElement.dataset.locality = locality;
        localityElement.textContent = locality;
        container.appendChild(localityElement);
    }

    // Fonction pour créer un élément de club
    function createClubElement(club) {
        const clubElement = document.createElement('div');
        clubElement.className = 'club';
        clubElement.draggable = true;
        clubElement.dataset.club = club.titre;

        const imgElement = document.createElement('img');
        imgElement.src = club.image;
        imgElement.alt = club.titre;

        clubElement.appendChild(imgElement);
        clubsContainer.appendChild(clubElement);
    }

    // Initialiser la logique de glisser-déposer
    function initDragAndDrop() {
        const clubs = document.querySelectorAll('.club');
        const localitiesElements = document.querySelectorAll('.locality');

        // Drag start
        clubs.forEach(club => {
            club.addEventListener('dragstart', dragStart);
        });

        // Drag over and drop
        localitiesElements.forEach(locality => {
            locality.addEventListener('dragover', dragOver);
            locality.addEventListener('drop', drop);
        });
    }

    // Fonction pour lancer le chronomètre
    function startTimer() {
        clearInterval(timer); // Assurez-vous que tout ancien timer est arrêté
        timer = setInterval(() => {
            if (!isPaused) {
                timeLeft--;
                updateTimerDisplay();
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    endGame(false);
                }
            }
        }, 1000);
    }

    // Mettre à jour l'affichage du chronomètre
    function updateTimerDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.textContent = `Temps: ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    // Mettre à jour l'affichage du score
    function updateScoreBoard(pseudo, temps) {
        const scoreBoard = document.getElementById('score-board');
        if (pseudo && temps) {
            const newScore = `<li>${pseudo} - ${temps} sec</li>`;
            scoreBoard.innerHTML += newScore;
        } else {
            console.error('Impossible de mettre à jour le score. Le pseudo ou le temps est manquant.');
        }
    }

    // Réinitialiser et rejouer
    replayButton.addEventListener('click', resetGame);
    resetButton.addEventListener('click', resetGame);

    // Fonction pour réinitialiser et rejouer
    function resetGame() {
        isFormSubmitted = false; // Réinitialiser l'état du formulaire soumis
        document.getElementById('save-score-form').reset(); // Réinitialiser le formulaire
        document.getElementById('save-score-form').style.display = 'none'; // Cacher le formulaire lors du nouveau jeu
        endGameModal.style.display = 'none';
        gameScreen.classList.remove('inactive'); // Réactiver le jeu en arrière-plan
        resetGameVariables();
        gameScreen.style.display = 'block'; // Afficher l'écran de jeu
        initGame(); // Réinitialiser les éléments du jeu
        startTimer(); // Redémarrer le chronomètre
    }


    // Réinitialiser les variables de jeu
    function resetGameVariables() {
        clearInterval(timer);
        matchedClubs = 0;
        timeLeft = 80;
        isPaused = false;
        updateTimerDisplay(); // Mettre à jour l'affichage du timer immédiatement
    }

    // Fonction appelée chaque fois qu'un club est correctement associé
    function onClubMatched() {
        matchedClubs++;
        if (matchedClubs === totalClubs) {
            endGame(true);
        }
    }

    // Fonction de glisser-déposer
    function dragStart(event) {
        if (isPaused) return;
        let target = event.target;
        while (!target.classList.contains('club')) {
            target = target.parentElement;
        }
        event.dataTransfer.setData('text/plain', target.dataset.club);
    }

    function dragOver(event) {
        event.preventDefault();
    }

    function drop(event) {
        if (isPaused) return;
        event.preventDefault();
        const clubId = event.dataTransfer.getData('text/plain');
        let target = event.target;

        while (!target.classList.contains('locality')) {
            target = target.parentElement;
        }
        const localityName = target.dataset.locality;
        const clubMatchesLocality = groupedByLocality[localityName].some(club => club.titre === clubId);

        if (clubMatchesLocality) {
            target.classList.add('success');
            target.classList.remove('failure');
            const clubElement = document.querySelector(`.club[data-club="${clubId}"]`);
            clubElement.style.display = 'none';
            onClubMatched();
        } else {
            target.classList.add('failure');
            setTimeout(() => {
                target.classList.remove('failure');
            }, 1000);
        }
    }
    saveScoreForm.addEventListener('submit', submitScoreForm);

    // Lancer le jeu lors du clic sur "Commencer le jeu"
    startButton.addEventListener('click', startGame);
});
