const { default: axios } = require('axios');

let game = {
	score: document.getElementById("score"),
	image: document.getElementById("image"),
	saveButton: document.querySelector('.save-points'),
	modal: document.querySelector(".modal"),
	scoreJS: parseFloat(score.dataset.userPoints),
	incrementeur: 1,
	changeLevelScore: parseInt(score.dataset.userLevel),
	url: "http://localhost:1234/save_axios",

	init: function () {
		// Preloader
		game.modal.style.display = "block";

		setTimeout(() => {
			game.modal.style.display = "none";
		}, 1500);
		
		game.image.addEventListener("click", game.handleClick);
		game.saveButton.addEventListener("click", game.loadGame);
		
	},

	handleClick: function () {
		game.scoreJS = game.scoreJS + game.incrementeur;

		game.score.innerHTML = "<p>Le nombre d'erreur(s) est de \n" + game.scoreJS + "</p>";

		if (game.scoreJS === game.changeLevelScore) {
			// console.log('Changement de niveau');
			game.postData(game.scoreJS);
		}

		game.saveGame();
	},

	saveGame: function () {
		var score = game.scoreJS;
		// https://stackoverflow.com/questions/47817325/storing-my-game-score-in-local-storage
		localStorage.setItem("points", JSON.stringify(score));
	},

	loadGame: function () {
		// Get data from localStorage
		let savedGame = JSON.parse(localStorage.getItem("points"));
		// console.log(savedGame);

		// Vérification si localStorage n'est pas vide
		if (localStorage.getItem("points") !== null) {
			if (typeof savedGame.points !== "undefined") {
				game.scoreJS = savedGame.points;
			}
			// console.log(game.scoreJS);
			game.postData(game.scoreJS);
		}
	},

	// https://blog.logrocket.com/using-axios-set-request-headers/
	postData: function (data) {
		axios.post(game.url, {
			points: data
		})
			.then(
				window.location.reload()
			)
			.catch(err => console.log(err));
	}
}
document.addEventListener('DOMContentLoaded', game.init);

