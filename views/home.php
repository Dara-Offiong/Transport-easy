<?php require 'header.php'; ?>

<!-- Page header / hero section -->
<div class="wrapper">
	<svg>
		<text x="50%" y="50%" dy=".35em" text-anchor="middle">
			Bienvenue sur TransportEasy!
		</text>
	</svg>
</div>

<!-- Intro text -->
<div class="introduction">
    <h2 style="text-align: center;">
        Planifiez votre voyage en utilisant notre formulaire intuitif ci-dessous. Que vous soyez un voyageur régulier
    </h2>
    <h2 style="text-align: center;">
        ou un explorateur occasionnel, TransportEasy vous accompagne à chaque étape de votre trajet.
    </h2>
</div>

<main>

    <!-- Left side: random image display -->
    <aside id="left">
        <?php
        $dossier = "photos/";
        $images = array(
            "gare.avif",
            "gare_du_nord.jpeg",
            "Impression_Gare_de_ST_Lazare.jpg",
            "station.jpg",
            "painting.jpeg"
        );

        // Shuffle images and pick one randomly
        shuffle($images);
        $imagePath = $dossier . $images[0];

        echo '<figure style="text-align: center;">';
        echo '<img src="' . $imagePath . '" alt="Random image" class="random-image">';
        echo '</figure>';
        ?>
    </aside>

    <!-- Right side: search form -->
    <div class="intro" id="right">
        <img src="/photos/location.png" alt="Your Image">
        <h3>Où allons-nous ?</h3>

        <form action="search.php" method="get" id="searchForm">

            <!-- Departure station input -->
            <label for="departure_station">DÉPART (adresse, arrêt, lieu...) (obligatoire)</label>
            <input type="text" id="departure_station" name="departure_station" list="departure_list" required>

            <!-- Autocomplete list for departure -->
            <datalist id="departure_list">
                <?php foreach ($stations as $station): ?>
                    <?php if ($station[8] === 'railStation'): ?>
                        <option value="<?php echo $station[4]; ?>">
                    <?php endif; ?>
                <?php endforeach; ?>
            </datalist>

            <!-- Arrival station input -->
            <label for="arrival_station">ARRIVÉE (adresse, arrêt, lieu...) (obligatoire)</label>
            <input type="text" id="arrival_station" name="arrival_station" list="arrival_list" required onchange="fetchStationId()">

            <!-- Autocomplete list for arrival -->
            <datalist id="arrival_list">
                <?php foreach ($stations as $station): ?>
                    <?php if ($station[8] === 'railStation'): ?>
                        <option value="<?php echo $station[4]; ?>">
                    <?php endif; ?>
                <?php endforeach; ?>
            </datalist>

            <!-- Departure time mode selector -->
            <label for="datetime_option">Quand :</label>
            <select id="datetime_option" onchange="showDateTimeFields()">
                <option value="now">Partir maintenant</option>
                <option value="custom">Choisir une date et une heure</option>
            </select>

            <!-- Custom date/time inputs -->
            <div id="datetime_fields" style="display: none;">
                <label for="date">Date :</label>
                <input type="date" id="date" name="date">

                <label for="time">Heure :</label>
                <input type="time" id="time" name="time">
            </div>

            <!-- Hidden field for internal station ID -->
            <input type="hidden" name="station_id" id="station_id">

            <!-- Detail level selector -->
            <label for="detail_level">Niveau de détail :</label>
            <select id="detail_level" name="detail_level" onchange="toggleStationDetailsInput()">
                <option value="general">Informations générales</option>
                <option value="detailed">Info trafic</option>
                <option value="details">Détails de la station</option>
            </select>

            <!-- Station details mode -->
            <div id="station_details_input" style="display: none;">
                <label for="station_name">Nom de la station :</label>
                <input type="text" id="station_name" list="station_list">

                <!-- Elevator station list -->
                <datalist id="station_list">
                    <?php
                    $elevatorStations = [];
                    $elevatorFile = fopen("etat-des-ascenseurs.csv", "r");

                    // Extract station names from CSV column 12
                    while (($data = fgetcsv($elevatorFile, 1000, ";")) !== FALSE) {
                        $elevatorStations[] = $data[12];
                    }
                    fclose($elevatorFile);

                    // Remove duplicates
                    $uniqueStations = array_unique($elevatorStations);

                    // Render options
                    foreach ($uniqueStations as $station) {
                        echo "<option value=\"$station\">";
                    }
                    ?>
                </datalist>

                <button onclick="fetchStationDetails(); clearSearchBars();">
                    Obtenir les détails
                </button>
            </div>

            <!-- Submit form -->
            <input type="submit" value="Rechercher">
        </form>
    </div>

</main>

<script>

/* Clear input fields */
function clearSearchBars() {
    document.getElementById("departure_station").value = "";
    document.getElementById("arrival_station").value = "";
}

/* Show/hide station detail input */
function toggleStationDetailsInput() {
    var detailOption = document.getElementById("detail_level").value;
    document.getElementById("station_details_input").style.display =
        (detailOption === "details") ? "block" : "none";
}

/* Show/hide custom date/time fields */
function showDateTimeFields() {
    var option = document.getElementById("datetime_option").value;
    document.getElementById("datetime_fields").style.display =
        (option === "custom") ? "block" : "none";
}

/* Redirect to station details page */
function fetchStationDetails() {
    var stationName = document.getElementById("station_name").value;
    window.location.href = "details.php?station_name=" + encodeURIComponent(stationName);
}

/* Convert station name to station ID */
function fetchStationId() {
    var arrivalStation = document.getElementById("arrival_station").value;
    var stations = <?php echo json_encode($stations); ?>;

    for (var i = 0; i < stations.length; i++) {
        if (stations[i][4] === arrivalStation) {
            document.getElementById("station_id").value = stations[i][0];
            break;
        }
    }
}

</script>

<!-- Theme-based image -->
<?php if(isset($_COOKIE['mode']) && $_COOKIE['mode'] === 'night'): ?>
    <div class="image-container">
        <img src="/photos/rerb.png" style="max-width: 500px; height: auto;">
    </div>
<?php endif; ?>

<?php if(isset($_COOKIE['mode']) && $_COOKIE['mode'] === 'day'): ?>
    <div class="image-container">
        <img src="/photos/rer.png" style="max-width: 500px; height: auto;">
    </div>
<?php endif; ?>

<?php require 'footer.php'; ?>