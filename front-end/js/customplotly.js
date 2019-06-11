function get_api_url(endpoint,optional_param="") {
	let base_url = "http://localhost/moodle/blocks/behaviorlytics/front-end/api/";
	let param = window.location.search;
	return base_url + endpoint + '.php' + param + optional_param;
}


function add_function_endpoint(endpoint,  func) {
	let url = get_api_url(endpoint);
	$.ajax( {'url': url, 'success': func});
} 

function course_quantity(result) {
	let result_json = JSON.parse(result);
	$("#userqty").html(result_json.length);
}


function forum_quantity(result) {
	let result_json = JSON.parse(result);
	console.log(result_json);
	$("#forumqty").html(result_json.length);
}


function course_name(result) {
	let course = JSON.parse(result);
	$("#coursename").html(course.shortname);
}

function nav_section(result) {
	let sections = JSON.parse(result);
	let qty = sections.length;
	for(let index in sections) {
		if(sections[index].name){
			$("#nav-section").append(`<a class="collapse-item" href="cards.html">${index}. ${sections[index].name}</a>`)
		} else {
			$("#nav-section").append(`<a class="collapse-item" href="cards.html">${index}. Sem Nome </a>`)
		}
	}
}

function course_frequency(result) {
	let data = JSON.parse(result);
	var data_plot = [
	 {
	    x: Object.keys(data),
	    y: Object.values(data),
	    type: 'scatter'
	  }
	];

	Plotly.newPlot('frequencyplot', data_plot)
}

function course_frequency_student(event, id) {
	event.preventDefault();
	let url = get_api_url('getfrequency',"&studentid=" + id);
	$.ajax( {'url': url, 'success': (result) => {
		let data = JSON.parse(result);
		var data_plot = [
			{
		    x: Object.keys(data),
		    y: Object.values(data),
		    type: 'scatter'
		  }
		];
		Plotly.newPlot('frequencyplot', data_plot)
	}});

}


function show_student(result) {
	let students = JSON.parse(result);
	$("#menustudent").empty();
	for(let index in students) {
		$("#menustudent").append(`<a class='dropdown-item' onclick="course_frequency_student(event, ${students[index].id})" href=''>${students[index].firstname} ${students[index].lastname} </a>`);
	}

	
}

$(document).ready(function () {
	add_function_endpoint('getstudent', course_quantity);
	add_function_endpoint('getcourse', course_name);
	add_function_endpoint('getsection', nav_section);
	add_function_endpoint('getfrequency', course_frequency);
	add_function_endpoint('getforum', forum_quantity);
});