<?php include "../inc/dbinfo.inc"; ?>
<?php

// trait to define useful mysql functions for this project
trait SqlQueries {
    /* get sum count for each option chosen for a column
    $q: the column you are selecting
    $conn: the MySql connection 
    */
    function modeFunc($q, $conn) {
        $sql = "SELECT $q AS vert, COUNT(*) AS num FROM surveysite.Responses GROUP BY $q;";        
        $result = $conn->query($sql);
        $types = array();
        while ($row = $result->fetch_assoc()) {
            $types[] = array("label"=>$row['vert'], "y"=>(int)$row['num']);
        }
        return $types;
    }

    /* get sum count for each option chosen for a column where criteria is not a string
        as well as selecting based on a condition, such as 'age=20'
    q: what column you are selecting
    cat: the column you are using as criteria (age, country, state, etc.)
    val: the value of the criteria you want all answers to be equal to (i.e. $cat=$val where cat is country, val is US)
    conn: MySql connection
    */
    function valModeFunc($q, $cat, $val, $conn) {        $sql = "SELECT $q AS vert, COUNT(*) AS num FROM surveysite.Responses WHERE $cat=$val GROUP BY $q;";
        $result = $conn->query($sql); //pass results into a variable
        $types = array();
        while ($row = $result->fetch_assoc()) { // loop and get each result
            $types[] = array("label"=>$row['vert'], "y"=>$row['num']); //pass in to an array to be used with graphing software
        }
        return $types; //return formatted array
    }
    /** get sum count for each option chosen for a column where criteria is a string
     * q: what column you are selecting
     * cat: the column you are using as criteria (age, country, state, etc.)
     * val: the value of the criteria you want all answers to be equal to (i.e. $cat=$val where cat is country, val is US)
     * conn: MySql connection
    */
    function strModeFunc($q, $cat, $val, $conn) {
        $sql = "SELECT $q AS vert, COUNT(*) AS num FROM Responses WHERE $cat='$val' GROUP BY $q";
        $result = $conn->query($sql); //pass results to a variable
        $types = array();
        while ($row = $result->fetch_assoc()) {
            $types[] = array("label"=>$row['vert'], "y"=>$row['num']); //pass each row into an array for graphing
        }
        return $types; //return the formatted array
    }
}

/*******  BEGINNING OF SECTION FOR RECEIVING/CLEANING FORM DATA *******/

    // start mysql connection
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    /* clean input passed in from forms */
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = strtolower($data);
        return $data; // return cleaned input
    }
    $stmt = $conn->prepare("INSERT INTO Responses (nickname, age, country, state, q1, q2, q3, q4, q5, q6) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("sissssssss", $name, $age, $country, $state, $q1, $q2, $q3, $q4, $q5, $q6);

    //get each name value from form and clean it
    $name = clean_input($_POST["name"]);
    $age = clean_input($_POST["age"]);
    $country = clean_input($_POST["country"]);    
    $state = clean_input($_POST["state"]);
    $q1 = clean_input($_POST["q1"]);
    $q2 = clean_input($_POST["q2"]);
    $q3 = clean_input($_POST["q3"]);
    $q4 = clean_input($_POST["q4"]);
    $q5 = clean_input($_POST["q5"]);
    $q6 = clean_input($_POST["q6"]);
    $stmt->execute(); // send info to database
    //echo "New record added successfully<br>";
    $stmt->close(); // close prepared statement

    /******* SECTION FOR GENERATINGS GRAPHS FOR EACH Questions *******/
    $overallQ = null;
    $ageQ = null;
    $countryQ = null;
    $stateQ = null;
    function qGen($q) {
        global $conn, $overallQ, $ageQ, $countryQ, $stateQ;
        global $age, $country, $state;
        $overallQ = sqlQueries::modeFunc($q, $conn);
        echo $overallQ;
        $ageQ = sqlQueries::valModeFunc($q, "age", $age, $conn);
        $countryQ = sqlQueries::strModeFunc($q, "country", $country, $conn);
        $stateQ = sqlQueries::strModeFunc($q, "state", $state, $conn);
    }
    
    $overallQ1 = sqlQueries::modeFunc("q1", $conn);
    $ageQ1 = sqlQueries::valModeFunc("q1", "age", $age, $conn);
    $countryQ1 = sqlQueries::strModeFunc("q1", "country", $country, $conn);
    $stateQ1 = sqlQueries::strModeFunc("q1", "state", $state, $conn);
    
    $overallQ2 = sqlQueries::modeFunc("q2", $conn);
    $ageQ2 = sqlQueries::valModeFunc("q2", "age", $age, $conn);
    $countryQ2 = sqlQueries::strModeFunc("q2", "country", $country, $conn);
    $stateQ2 = sqlQueries::strModeFunc("q2", "state", $state, $conn);

    $overallQ3 = sqlQueries::modeFunc("q3", $conn);
    $ageQ3 = sqlQueries::valModeFunc("q3", "age", $age, $conn);
    $countryQ3 = sqlQueries::strModeFunc("q3", "country", $country, $conn);
    $stateQ3 = sqlQueries::strModeFunc("q3", "state", $state, $conn);

    $overallQ4 = sqlQueries::modeFunc("q4", $conn);
    $ageQ4 = sqlQueries::valModeFunc("q4", "age", $age, $conn);
    $countryQ4 = sqlQueries::strModeFunc("q4", "country", $country, $conn);
    $stateQ4 = sqlQueries::strModeFunc("q4", "state", $state, $conn);

    $overallQ5 = sqlQueries::modeFunc("q5", $conn);
    $ageQ5 = sqlQueries::valModeFunc("q5", "age", $age, $conn);
    $countryQ5 = sqlQueries::strModeFunc("q5", "country", $country, $conn);
    $stateQ5 = sqlQueries::strModeFunc("q5", "state", $state, $conn);

    $overallQ6 = sqlQueries::modeFunc("q6", $conn);
    $ageQ6 = sqlQueries::valModeFunc("q6", "age", $age, $conn);
    $countryQ6 = sqlQueries::strModeFunc("q6", "country", $country, $conn);
    $stateQ6 = sqlQueries::strModeFunc("q6", "state", $state, $conn);

    
    $conn->close();
?>

<html>
    <head>
        <link rel="stylesheet" href="styles.css">   
        <link href="https://fonts.googleapis.com/css2?family=Baloo+Thambi+2&display=swap" rel="stylesheet"> 
        <script>
let validCharts = null

function makeQ1() {
if (validCharts) {
/*    for (var x = 0; x < 4; x++) {
    	if (validCharts[x]) {
	    setTimeout(()=>{validCharts[x].destroy()}, 250);
	    validCharts[x] = null;
	}
    }*/
} else {
  validCharts = [null, null, null, null];
}

   /* if (validCharts) {
        for (x of validCharts) {
            x.destroy();
            x = null
        }
    }*/
    showCharts('q1-charts');
   
    validCharts[0] = new CanvasJS.Chart("overallChartQ1", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 1 from all users"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($overallQ1, JSON_NUMERIC_CHECK); ?>
	}]
});



validCharts[1] = new CanvasJS.Chart("ageChartQ1", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 1 from people your age"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($ageQ1, JSON_NUMERIC_CHECK); ?>
	}]
});



validCharts[2] = new CanvasJS.Chart("countryChartQ1", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 1 from your country"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($countryQ1, JSON_NUMERIC_CHECK)?>
	}]
});



validCharts[3] = new CanvasJS.Chart("stateChartQ1", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 1 from your state"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($stateQ1, JSON_NUMERIC_CHECK); ?>
	}]
});
/*
setTimeout(()=>{validCharts[0].render()}, 250);
setTimeout(()=>{validCharts[1].render()}, 250);
setTimeout(()=>{validCharts[2].render()}, 250);
setTimeout(()=>{validCharts[3].render()}, 250);
*/
validCharts[0].render();
validCharts[1].render();
validCharts[2].render();
validCharts[3].render();

//validCharts = [chart1, chart2, chart3, chart4]
}
function makeQ2() {
if (validCharts) {
/*    for (var x = 0; x < 4; x++) {
    	if (validCharts[x]) {
	    validCharts[x].destroy();
	    validCharts[x] = null;
	}
    }*/
}

/*if (validCharts) {
    for (x of validCharts) {
        x.destroy();
        x = null;
    }
}*/
showCharts('q2-charts');
validCharts[0] = new CanvasJS.Chart("overallChartQ2", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 2 from all users"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($overallQ2, JSON_NUMERIC_CHECK); ?>
	}]
});

validCharts[1] = new CanvasJS.Chart("ageChartQ2", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 2 from people your age"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($ageQ2, JSON_NUMERIC_CHECK); ?>
	}]
});

validCharts[2] = new CanvasJS.Chart("countryChartQ2", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 2 from your country"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($countryQ2, JSON_NUMERIC_CHECK)?>
	}]
});

validCharts[3] = new CanvasJS.Chart("stateChartQ2", {

	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 2 from your state"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($stateQ2, JSON_NUMERIC_CHECK); ?>
	}]
});
/*
setTimeout(()=>{validCharts[0].render()}, 250);
setTimeout(()=>{validCharts[1].render()}, 250);
setTimeout(()=> {validCharts[2].render()}, 250);
setTimeout(()=> {validCharts[3].render()}, 250);
*/
validCharts[0].render();
validCharts[1].render();
validCharts[2].render();
validCharts[3].render();

}
function makeQ3() {
if (validCharts) {
/*    for (var x = 0; x < 4; x++) {
    	if (validCharts[x]) {
	    validCharts[x].destroy();
	    validCharts[x] = null;
	}
    }*/
}
/*
if (validCharts) {
    for (x of validCharts) {
        x.destroy();
        x = null;
    }
}*/
showCharts('q3-charts');
setTimeout( () => {}, 500);
validCharts[0] = new CanvasJS.Chart("overallChartQ3", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 3 from all users"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($overallQ3, JSON_NUMERIC_CHECK); ?>
	}]
});



validCharts[1] = new CanvasJS.Chart("ageChartQ3", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 3 from people your age"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($ageQ3, JSON_NUMERIC_CHECK); ?>
	}]
});



validCharts[2] = new CanvasJS.Chart("countryChartQ3", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 3 from your country"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($countryQ3, JSON_NUMERIC_CHECK);?>
	}]
});



validCharts[3] = new CanvasJS.Chart("stateChartQ3", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 3 from your state"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
	type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($stateQ3, JSON_NUMERIC_CHECK); ?>
	}]
});
/*
setTimeout(()=>{validCharts[0].render()}, 250);
setTimeout(()=>{validCharts[1].render()}, 250);
setTimeout(()=> {validCharts[2].render()}, 250);
setTimeout(()=> {validCharts[3].render()}, 250);
*/
validCharts[0].render();
validCharts[1].render();
validCharts[2].render();
validCharts[3].render();

}
function makeQ4() {
if (validCharts) {
/*    for (var x = 0; x < 4; x++) {
    	if (validCharts[x]) {
	    validCharts[x].destroy();
	    validCharts[x] = null;
	}
    }*/
}
/*
if (validCharts) {
    for (x of validCharts) {
        x.destroy();
        x = null;
    }
}*/
showCharts('q4-charts');    
validCharts[0] = new CanvasJS.Chart("overallChartQ4", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 4 from all users"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($overallQ4, JSON_NUMERIC_CHECK); ?>
	}]
});



validCharts[1] = new CanvasJS.Chart("ageChartQ4", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 4 from people your age"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($ageQ4, JSON_NUMERIC_CHECK); ?>
	}]
});



validCharts[2] = new CanvasJS.Chart("countryChartQ4", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 4 from your country"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
	type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($countryQ4, JSON_NUMERIC_CHECK)?>
	}]
});



validCharts[3] = new CanvasJS.Chart("stateChartQ4", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 4 from your state"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($stateQ4, JSON_NUMERIC_CHECK); ?>
	}]
});
validCharts[0].render();
validCharts[1].render();
validCharts[2].render();
validCharts[3].render();
/*
setTimeout(()=>{validCharts[0].render()}, 250);
setTimeout(()=>{validCharts[1].render()}, 250);
setTimeout(()=> {validCharts[2].render()}, 250);
setTimeout(()=> {validCharts[3].render()}, 250);
*/
}
function makeQ5() {
if (validCharts) {
/*    for (var x = 0; x < 4; x++) {
    	if (validCharts[x]) {
	    validCharts[x].destroy();
	    validCharts[x] = null;
	}
    }*/
}
showCharts('q5-charts')
validCharts[0] = new CanvasJS.Chart("overallChartQ5", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 5 from all users"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($overallQ5, JSON_NUMERIC_CHECK); ?>
	}]
});



validCharts[1] = new CanvasJS.Chart("ageChartQ5", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 5 from people your age"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($ageQ5, JSON_NUMERIC_CHECK); ?>
	}]
});



validCharts[2] = new CanvasJS.Chart("countryChartQ5", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 5 from your country"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($countryQ5, JSON_NUMERIC_CHECK)?>
	}]
});



validCharts[3] = new CanvasJS.Chart("stateChartQ5", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 5 from your state"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($stateQ5, JSON_NUMERIC_CHECK); ?>
	}]
});
/*
setTimeout(()=>{validCharts[0].render()}, 250);
setTimeout(()=>{validCharts[1].render()}, 250);
setTimeout(()=> {validCharts[2].render()}, 250);
setTimeout(()=> {validCharts[3].render()}, 250);
*/
validCharts[0].render();
validCharts[1].render();
validCharts[2].render();
validCharts[3].render();

}
function makeQ6() {
if (validCharts) {
/*    for (var x = 0; x < 4; x++) {
    	if (validCharts[x]) {
	    validCharts[x].destroy();
	    validCharts[x] = null;
	}
    }*/
}
showCharts('q6-charts');
validCharts[0] = new CanvasJS.Chart("overallChartQ6", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 6 from all users"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($overallQ6, JSON_NUMERIC_CHECK); ?>
	}]
});



validCharts[1] = new CanvasJS.Chart("ageChartQ6", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 6 from people your age"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($ageQ6, JSON_NUMERIC_CHECK); ?>
	}]
});



validCharts[2] = new CanvasJS.Chart("countryChartQ6", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 6 from your country"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($countryQ6, JSON_NUMERIC_CHECK)?>
	}]
});



validCharts[3] = new CanvasJS.Chart("stateChartQ6", {
	animationEnabled: false,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Answers to question 6 from your state"
    },
    axisY: {
        title: "Answer",
        includeZero: false
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		dataPoints: <?php echo json_encode($stateQ6, JSON_NUMERIC_CHECK); ?>
	}]
});
/*
setTimeout(()=>{validCharts[0].render()}, 250);
setTimeout(()=>{validCharts[1].render()}, 250);
setTimeout(()=> {validCharts[2].render()}, 250);
setTimeout(()=> {validCharts[3].render()}, 250);
*/
validCharts[0].render();
validCharts[1].render();
validCharts[2].render();
validCharts[3].render();


}

window.onload = function () {
makeQ1();
graphsStartup();
}
        </script>
    </head>
    <script type='text/javascript' src='/script.js'></script>
<body id="bodyID">


<h4 style="margin-bottom: 1vh; text-align: center;"> <u>Thanks for filling out our questions <?php echo $_POST["name"]; ?></u></h4>
<h5 style="margin-top: 0; text-align: center;">To show you're not alone in this, here are some stats about other people who've filled out this form.</h5>

<div id="button-group" >
    <button class="button" onclick="makeQ1()">Q1</button>
    <button class="button" onclick="makeQ2()">Q2</button>
    <button class="button" onclick="makeQ3()">Q3</button>
    <button class="button" onclick="makeQ4()">Q4</button>
    <button class="button" onclick="makeQ5()">Q5</button>
    <button class="button" onclick="makeQ6()">Q6</button>
</div>
<div id="q1-charts" class="graph-groups" style="display: block;">
    <div class="chart-text-con"><p class="chart-text"> 
        In question 1, we asked you how you felt in quarantine. 
        You told us that you felt <?php echo $q1?>.
        Here's how everyone else who filled out the survey felt.  
    </div>
    <div class="graph" id="overallChartQ1"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's how people your age felt.  
    </div>
    <div class="graph" id="ageChartQ1"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's how people from your country felt.  
    </div>
    <div class="graph" id="countryChartQ1"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's how people from your state felt.  
    </div>
    <div class="graph" id="stateChartQ1"></div>
</div>
<div id="q2-charts" class="graph-groups" style="display: block;">
    <div class="chart-text-con"><p class="chart-text"> 
        In question 2, we asked you how you've been filling your time.
        You told us that your activity of choice was: <?php echo $q2?>.
        Here's what everyone else is doing.
    </div>
    <div class="graph" id="overallChartQ2"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's what people your age are doing.  
    </div>
    <div class="graph" id="ageChartQ2"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's what people from your country are doing.
    </div>
    <div class="graph" id="countryChartQ2"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's what people from your state are doing.  
    </div>
    <div class="graph" id="stateChartQ2"></div>
</div>
<div id="q3-charts" class="graph-groups" style="display: block;">
    <div class="chart-text-con"><p class="chart-text"> 
        In question 3, we asked you what has brought you the most joy
        during this time. You told us that it was <?php echo $q3?>
        Here's what everyone else said.
    </div>
    <div class="graph" id="overallChartQ3"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's what people your age said.
    </div>
    <div class="graph" id="ageChartQ3"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's what people from your country said.
    </div>
    <div class="graph" id="countryChartQ3"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's what people from your state said.
    </div>
    <div class="graph" id="stateChartQ3"></div>
</div>
<div id="q4-charts" class="graph-groups" style="display: block;">
    <div class="chart-text-con"><p class="chart-text"> 
        In question 4, we asked you how often you check the news.
        You told us that you checked the news
        <?php if ($q4 == 'multiple') {
            echo 'every few hours';
        } else {
            echo $q4;
        }?>.
        Here's what everyone else said.
    </div>
    <div class="graph" id="overallChartQ4"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's what people your age said.
    </div>
    <div class="graph" id="ageChartQ4"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's what people from your country said.
    </div>
    <div class="graph" id="countryChartQ4"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's what people from your state said. 
    </div>
    <div class="graph" id="stateChartQ4"></div>
</div>
<div id="q5-charts" class="graph-groups" style="display: block;">
    <div class="chart-text-con"><p class="chart-text"> 
        In question 5, we asked you how you felt after checking the news.
        You told us that you felt <?php echo $q5 ?>.
        Here's what everyone else felt.
    </div>
    <div class="graph" id="overallChartQ5"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's how people your age felt.  
    </div>
    <div class="graph" id="ageChartQ5"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's how people from your country felt.  
    </div>
    <div class="graph" id="countryChartQ5"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's how people from your state felt.  
    </div>
    <div class="graph" id="stateChartQ5"></div>
</div>
<div id="q6-charts" class="graph-groups" style="display: block;">
    <div class="chart-text-con"><p class="chart-text"> 
        In question 6, we asked you what gives you hope for the future.
        You told us <?php echo $q6 ?>.
        Here's what everyone else said.
    </div>
    <div class="graph" id="overallChartQ6"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's what people your age said.  
    </div>
    <div class="graph" id="ageChartQ6"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's what people from your country said.
    </div>
    <div class="graph" id="countryChartQ6"></div>
    <div class="chart-text-con"><p class="chart-text"> 
        Here's what people from your state said.  
    </div>
    <div class="graph" id="stateChartQ6"></div>
</div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<div id="footer" class="center-text">
    <h2 style="margin-bottom: 0;">made in the midwest</h2>
    <a href="https://www.github.com/ciege99" style="text-decoration: none; color: inherit"><h2 style="text-decoration: none; margin-top: 0">:p</h2></a>
</div>



</body>
</html>
