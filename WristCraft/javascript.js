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
            var categories = JSON.parse(xhttp.responseText);

            var htmlOptions = "<li><a href='products.php'>All</a></li>"; // Add "All Products" option

            for (var i = 0; i < categories.length; i++) {
                htmlOptions += "<li><a href='products.php?type=" + encodeURIComponent(categories[i].Name) + "'>"
                             + categories[i].Name + "</a></li>";
            }

            document.getElementById("ulLeftMenu").innerHTML = htmlOptions;
        }
    };

    xhttp.open("GET", "fetch_categories.php", true);
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
