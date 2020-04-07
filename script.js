//validate all of the input fields from the form
function validateForm() {
    // get all input fields
    const a = document.forms["Form"]["name"].value;
    const b = document.forms["Form"]["age"].value;
    const c = document.forms["Form"]["country"].value;
    const d = document.forms["Form"]["q1"].value;
    const e = document.forms["Form"]["q2"].value;
    const f = document.forms["Form"]["q3"].value;
    const g = document.forms["Form"]["q4"].value;
    const h = document.forms["Form"]["q5"].value;
    const i = document.forms["Form"]["q6"].value;
    //check that they are all truthy
    if (!(a && b && c && d && e && f && g && h && i)) {
        alert("Please fill all required fields");
        return false
    } 
    //check that b is an int and valid
    bVal = parseInt(b)
    if (! bVal || bVal < 0 || bVal > 130 ) {
        alert('Please enter a valid number for age')
        return false
    } 
    return true
}

function setActive(elem) {
    if (elem.className.includes("active")) {
        return; // we clicked on an already active button, return 
    }
    active = elem.className + " active"; //get the active string
    elements = document.getElementsByClassName(active); //get the active element
    if (elements.length == 0) {
        elem.className = active; // if no buttons are active, select this one
    }
    for (x of elements) {
        x.className = elem.className; //swap the active to passive
        elem.className = active; //swap the passive to active
    }
}
/*
function getCharts(id) {
    if (id == 'q1-charts') {
	chart1.render();
	chart2.render();
	chart3.render();
	chart4.render();
    } else if (id == 'q2-charts') {
	chart5.render();
	chart6.render();
	chart7.render();
	chart8.render();
    } else if (id == 'q3-charts') {
	chart9.render();
	chart10.render();
	chart11.render();
	chart12.render();
    } else if (id == 'q4-charts') {
	chart13.render();
	chart14.render();
	chart15.render();
	chart16.render();
    } else if (id == 'q5-charts') {
	chart17.render();
	chart18.render();
	chart19.render();
	chart20.render();
    } else if (id == 'q6-charts') {
	chart21.render();
	chart22.render();
	chart23.render();
	chart24.render();
    }

}
*/

function showCharts(iden) {
    let arr = ['q1-charts', 'q2-charts', 'q3-charts', 'q4-charts', 'q5-charts', 'q6-charts']
    for (x of arr) {
        state = document.getElementById(x).style.display
        console.log(state);
        if (x != iden && (state == 'block')) {
            console.log('here');
            console.log(x)
            document.getElementById(x).style.display = 'none';
//	    getCharts(x);
            document.getElementById(iden).style.display = 'block';
            return; //return if we've changed from one display to other
        }
    }
//    getCharts(iden);
    x = document.getElementById(iden)
    if (x.style.display == 'none') {
        x.style.display = 'block';
    } else {
        x.style.display = 'none';
    }
}

function graphsStartup() {
    let arr = ['q2-charts', 'q3-charts', 'q4-charts', 'q5-charts', 'q6-charts']
    for (x of arr) {
        document.getElementById(x).style.display = 'none';
    }
}
