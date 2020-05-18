const player2_text = "Player 2, choose your option";

const player1_win = "Player 1, has won!";
const player2_win = "Player 2, has won!";
const draw = "Draw!";

var player1_choice;
var player2_choice;

function choice(type) {
    if(player1_choice == null) {
        player1_choice = type;
        document.getElementById("instructions").innerHTML = player2_text;
    }else if(player2_choice == null) {
        player2_choice = type;
        document.getElementById("choices_table").innerHTML = "";
        checkWinCondition();
    }
}

function checkWinCondition(){
    let title = document.getElementById("instructions");
    if(player1_choice == player2_choice){
        title.innerHTML = draw;
    }else if(player1_choice == "rock") {
        if(player2_choice == "scissors") {
            title.innerHTML = player1_win;
        }else {
            title.innerHTML = player2_win;
        }
    }else if(player1_choice == "paper") {
        if(player2_choice == "rock") {
            title.innerHTML = player1_win;
        }else {
            title.innerHTML = player2_win;
        }
    }else if(player1_choice == "scissors") {
        if(player2_choice == "paper") {
            title.innerHTML = player1_win;
        }else {
            title.innerHTML = player2_win;
        }
    }
}