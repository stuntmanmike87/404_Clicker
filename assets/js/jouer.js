window.onload = () => {
    let Game = {
        score : document.getElementById("score"),
        image : document.getElementById("image"),
        scoreJS : 0,
        incrementeur: 1,
    }

    image.addEventListener("click", function(){
        Game.scoreJS = Game.scoreJS + Game.incrementeur;
        score.innerHTML = "<p>Le nombre d'erreur(s) est de \n" + Game.scoreJS + "</p>";
    })
}