window.onload = () => {
    /*=====================================================================================
	INITIALISATION
	=======================================================================================*/
    let Game = {
        score : document.getElementById("score"),
        image : document.getElementById("image"),
        scoreJS : 0,
        incrementeur: 1,
    }
	/*=====================================================================================
	VERIF DES POINTS USER
	=======================================================================================*/
    /*=====================================================================================
	FONCTION CLIC / SAVE DE GAME
	=======================================================================================*/
    image.addEventListener("click", function(){
        Game.scoreJS = Game.scoreJS + Game.incrementeur;
        score.innerHTML = "<p>Le nombre d'erreur(s) est de \n" + Game.scoreJS + "</p>";
		localStorage.getItem(Game.scoreJS);
		console.log(localStorage.getItem(Game.scoreJS));
    })
	/*=====================================================================================
	PASSAGE DES LEVELS
	=======================================================================================*/
    /*=====================================================================================
	TEST ANTI-CHEAT
	=======================================================================================*/
    if (!Game.ready)
	{
		if (top!=self) Game.ErrorFrame();
		else
		{
			console.log('[=== '+choose([
				'Oh, hello!',
				'hey, how\'s it hangin',
				'About to cheat in some cookies or just checking for bugs?',
				'Remember : cheated cookies taste awful!',
				'Hey, LAG here. Cheated error destroy pc... or do they?',
			])+' ===]');
			Game.Load();
			//try {Game.Load();}
			//catch(err) {console.log('ERROR : '+err.message);}
		}
	}
}