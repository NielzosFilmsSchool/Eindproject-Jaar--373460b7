const requestUrl =
	"https://mdn.github.io/learning-area/javascript/oojs/json/superheroes.json";

function requestJSON(url) {
	let request = new XMLHttpRequest();
	request.open("GET", url);
	request.responseType = "json";
	request.send();
	request.onload = function () {
		let response = request.response;
		processResponse(response);
	};
}

function sendRequest() {
	requestJSON(requestUrl);
}

function processResponse(response) {
	// TODO schrijf hier je code
	//console.log(response);

	let table = document.getElementById("overview-table");
	let member_table = document.getElementById("member-table");
	let members = response["members"];
	let row = "<tr>";
	for (var key in response) {
		if (key != "members") {
			row += "<td>";
			row += response[key];
			row += "</td>";
		}
	}
	row += "</tr>";
	table.innerHTML += row;

	for (var i = 0; i < members.length; i++) {
		let member_row = "<tr>";
		for (var key in members[i]) {
			member_row += "<td>";
			member_row += members[i][key];
			member_row += "</td>";
		}
		member_row += "</tr>";
		member_table.innerHTML += member_row;
	}
}

sendRequest();
