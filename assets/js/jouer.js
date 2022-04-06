const { default: axios } = require('axios');

let game = {
	score : document.getElementById("score"),
    image : document.getElementById("image"),
	saveButton: document.querySelector('.savePoints'),
    scoreJS : 0,
    incrementeur: 1,
	url: "http://localhost:1234/axios",

	init: function() {
		game.image.addEventListener("click", game.handleClick);
		game.saveButton.addEventListener("click", game.loadGame);
		console.log(game.saveButton);
	},

	handleClick: function() {
		game.scoreJS = game.scoreJS + game.incrementeur;
		
		game.score.innerHTML = "<p>Le nombre d'erreur(s) est de \n" + game.scoreJS + "</p>";	

		game.saveGame();
	},

	saveGame: function() {
		var score = game.scoreJS;
		// https://stackoverflow.com/questions/47817325/storing-my-game-score-in-local-storage
		localStorage.setItem("points", JSON.stringify(score));
		// console.log(score);

		// game.loadGame();
	},

	loadGame: function () {
		// Get data from localStorage
		let savedGame = JSON.parse(localStorage.getItem("points"));
		// console.log(savedGame);

		// Vérification si localStorage n'est pas vide
		if (localStorage.getItem("points") !== null) {
			if (typeof savedGame.points !== "undefined" ) {
				game.scoreJS = savedGame.points;
			}
			// console.log(game.scoreJS);
			game.postData(game.scoreJS);
		}
	},

	// https://blog.logrocket.com/using-axios-set-request-headers/
	postData: function(data) {
		axios.post(game.url, {
			points: data
		})
		.then(res => console.log(res))
		.catch(err => console.log(err));
 	}
}
document.addEventListener('DOMContentLoaded', game.init);
// const axios = require('axios');
// window.onload = () => {
//     /*=====================================================================================
// 	INITIALISATION
// 	=======================================================================================*/
//     let Game = {
//         score : document.getElementById("score"),
//         image : document.getElementById("image"),
//         scoreJS : 0,
//         incrementeur: 1,
// 		url: "http://localhost:1234/",
//     }
// 	/*=====================================================================================
// 	VERIF DES POINTS USER
// 	=======================================================================================*/
//     /*=====================================================================================
// 	FONCTION CLIC / SAVE DE GAME
// 	=======================================================================================*/
//     image.addEventListener("click", function(){
//         Game.scoreJS = Game.scoreJS + Game.incrementeur;

// 		// https://stackoverflow.com/questions/47817325/storing-my-game-score-in-local-storage
// 		localStorage.setItem("score", Game.scoreJS);

// 		// Get data from localStorage
// 		let score1 = localStorage.getItem("score");
// 		console.log(score1);

//         Game.score.innerHTML = "<p>Le nombre d'erreur(s) est de \n" + Game.scoreJS + "</p>";

// 		// https://blog.logrocket.com/using-axios-set-request-headers/
// 		function sendData(data) {
// 			axios.post(Game.url, {
// 				data: data
// 			})
// 			.then(res => console.log(res))
// 			.catch(err => console.log(err));

//  		}
// 		sendData(score1);
//     })
	
	

	/*=====================================================================================
	PASSAGE DES LEVELS
	=======================================================================================*/
    /*=====================================================================================
	TEST ANTI-CHEAT
	=======================================================================================*/
    // if (!Game.ready)
	// {
	// 	if (top!=self) Game.ErrorFrame();
	// 	else
	// 	{
	// 		console.log('[=== '+choose([
	// 			'Oh, hello!',
	// 			'hey, how\'s it hangin',
	// 			'About to cheat in some cookies or just checking for bugs?',
	// 			'Remember : cheated cookies taste awful!',
	// 			'Hey, LAG here. Cheated error destroy pc... or do they?',
	// 		])+' ===]');
	// 		Game.Load();
	// 		//try {Game.Load();}
	// 		//catch(err) {console.log('ERROR : '+err.message);}
	// 	}
	// }
