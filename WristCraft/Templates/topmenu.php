<div class="divTopMenu">
    <nav>
        <ul id="ulTopMenu">
            <li><a href="index.php" target="_self" style="font-size: 20px;">Home Page</a></li>
            <li class="dropdown">
                <a onclick="toggleDropdown(event)" style="font-size: 20px;">Products</a>
                <ul class="dropdown-content" id="ulLeftMenu">
                <script language="javascript">category()</script>
                </ul>
            </li>
            <li><a href="contact.php" target="_self" style="font-size: 20px;">Contact</a></li>
            <li><a href="usercontrolGuest.php" target="_self" style="font-size: 20px;">Profile</a></li>
        </ul>
    </nav>
</div>

<script>
        // Call the function to populate the dropdown after the DOM is loaded
        document.addEventListener("DOMContentLoaded", function () {
            category();
        });

        // Function to toggle the dropdown on click
        function toggleDropdown(event) {
            event.preventDefault(); // Prevent the default link behavior
            const dropdownContent = document.getElementById("ulLeftMenu");
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        }

        // Close the dropdown if the user clicks outside of it
        window.addEventListener("click", function (event) {
            const dropdownContent = document.getElementById("ulLeftMenu");
            if (!event.target.matches(".dropdown > a")) {
                dropdownContent.style.display = "none";
            }
        });
    </script>