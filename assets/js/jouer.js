const axios = require('axios');
window.onload = () => {
    /*=====================================================================================
	INITIALISATION
	=======================================================================================*/
    let Game = {
        score : document.getElementById("score"),
        image : document.getElementById("image"),
        scoreJS : 0,
        incrementeur: 1,
		url: "http://localhost:1234",
    }
	/*=====================================================================================
	VERIF DES POINTS USER
	=======================================================================================*/
    /*=====================================================================================
	FONCTION CLIC / SAVE DE GAME
	=======================================================================================*/
    image.addEventListener("click", function(){
        Game.scoreJS = Game.scoreJS + Game.incrementeur;

		// https://stackoverflow.com/questions/47817325/storing-my-game-score-in-local-storage
		localStorage.setItem("score", Game.scoreJS);

		// Get data from localStorage
		let score1 = localStorage.getItem("score");
		console.log(score1);

        score.innerHTML = "<p>Le nombre d'erreur(s) est de \n" + Game.scoreJS + "</p>";

		// https://blog.logrocket.com/using-axios-set-request-headers/
		function sendData($data) {
			axios.post(Game.url, $data)
			.then(res => console.log(res))
			.catch(err => console.log(err));

 		}
		sendData(score1);
    })
	
	

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
}