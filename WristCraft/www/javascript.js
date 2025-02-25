document.addEventListener("deviceready", function () {
    document.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent opening in an external browser
            let url = this.href;
            window.open(url, "_self"); // Opens inside the app
        });
    });
});

function category() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4 && xhttp.status === 200) {
            xmlDoc = xhttp.responseXML;

            htmlOptions = "";
            menuoptions = xmlDoc.getElementsByTagName("Name");
            for (i = 0; i < menuoptions.length; i++) {
                htmlOptions += "<li><a href='"
                        + xmlDoc.getElementsByTagName("Link")[i].firstChild.nodeValue + "'></li>"
                        + xmlDoc.getElementsByTagName("Name")[i].firstChild.nodeValue + "</a>";
            }

            document.getElementById("ulLeftMenu").innerHTML = htmlOptions;
        }
    };

    xhttp.open("get", "data/leftmenu.xml", true);
    xhttp.send();
}
function ShowContact() {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4 && xhttp.status === 200) {
            xmlDoc = xhttp.responseXML;

            document.getElementById("CompanyName").innerHTML = xmlDoc.getElementsByTagName("CompanyName")[0].firstChild.nodeValue;
            document.getElementById("Address").innerHTML = xmlDoc.getElementsByTagName("Address")[0].firstChild.nodeValue;
            document.getElementById("PhoneNumber").innerHTML = xmlDoc.getElementsByTagName("PhoneNumber")[0].firstChild.nodeValue;
            document.getElementById("Email").innerHTML = xmlDoc.getElementsByTagName("Email")[0].firstChild.nodeValue;
        }
    };

    xhttp.open("get", "data/contact.xml", true);
    xhttp.send();
  }
