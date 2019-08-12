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
	$("#forumqty").html(result_json.length);
}

function assign_quantity(result) {
	let result_json = JSON.parse(result);
	$("#assignqty").html(result_json.length);
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
			$("#nav-section").append(`<a class="collapse-item" href="index.php?courseid=${sections[index].course}&section=${sections[index].id}">${index}. ${sections[index].name}</a>`);
		} else {
			$("#nav-section").append(`<a class="collapse-item" href="index.php?courseid=${sections[index].course}&section=${sections[index].id}">${index}. Sem Nome </a>`);
		}
	}
}

function forum_visu(result) {
	let result_json = JSON.parse(result);
	for(let index in result_json) {
		let perc = result_json[index].perc * 100;
		let barcolor = '';
		let warning = '';
		let info = '';
		if( perc <= 0) {
			barcolor = 'bg-danger';
			perc = 100;
			warning = ' Nenhum estudante visualizou este fórum';
		} else if( perc < 50) {
			barcolor = 'bg-danger';
			info = '<i class="fas fa-info-circle"></i>'
		} else if (perc >=  50 && perc < 80 ) {
			barcolor = 'bg-warning';
			info = '<i class="fas fa-info-circle"></i>'
		} else if( perc >= 80 && perc < 100 ) {
			barcolor = 'bg-info';
			info = '<i class="fas fa-info-circle"></i>'
		} else {
			barcolor = 'bg-success';
		}

		let dropd = '';
		if(info) {
			dropd = 
			`<div class="dropdown no-arrow">
          <a class="dropdown-toggle"  onclick="show_student_notview_forum(event,${result_json[index].id})" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           	${info}
           </a>
           <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
           		<div class="dropdown-header student_notview_forum"></div>
           </div>
       </div>`
		}

		$("#forumvisualization").append(
			`<h4 class="small font-weight-bold">${result_json[index].name} <span class="float-right">${perc}% </span> 
			<a href=""> ${dropd} </a></h4>
        <div class="progress mb-4">
        <div class="progress-bar ${barcolor}" role="progressbar" style="width: ${perc}%" aria-valuenow="${perc}" aria-valuemin="0" aria-valuemax="100"><i>${warning}</i></div>
       </div>`);
	}

}

function get_barcolor(perc) {
	let barcolor = ''
	if( perc <= 0) {
			barcolor = 'bg-danger';
		} else if( perc < 50) {
			barcolor = 'bg-danger';
			info = '<i class="fas fa-info-circle"></i>'
		} else if (perc >=  50 && perc < 80 ) {
			barcolor = 'bg-warning';
		} else if( perc >= 80 && perc < 100 ) {
			barcolor = 'bg-info';
			info = '<i class="fas fa-info-circle"></i>'
		} else {
			barcolor = 'bg-success';
		}
		return barcolor;
}

function assign_visu(result) {
	let result_json = JSON.parse(result);
	for(let index in result_json) {
		let perc = result_json[index].perc * 100;
		let barcolor = get_barcolor(perc);
		let warning = '';
		let info = false;

		if( perc <= 0) {
			warning = 'Nenhum estudante visualizou esta Atividade';
		} else if (perc < 100) {
			info = true;
		}

		let dropd = '';
		if(info) {
			dropd = 
			`<div class="dropdown no-arrow">
          <a class="dropdown-toggle"  onclick="show_student_notview_assign(event,${result_json[index].id})" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           	<i class="fas fa-info-circle"></i>
           </a>
           <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
           		<div class="dropdown-header student_notview_assign"></div>
           </div>
       </div>`
		}

		$("#assignmentvisualization").append(
			`<h4 class="small font-weight-bold">${result_json[index].name} <span class="float-right">${perc}% </span> 
			<a href=""> ${dropd} </a></h4>
        <div class="progress mb-4">
        <div class="progress-bar ${barcolor}" role="progressbar" style="width: ${perc}%" aria-valuenow="${perc}" aria-valuemin="0" aria-valuemax="100"><i>${warning}</i></div>
       </div>`);
	}

}

function show_resource_visu(resource) {
	let url = get_api_url('getanyvisu','&resource=' + resource);
	$.ajax( {'url': url, 'success': (response) => {
		resource_visu(response,resource);
	}});
}


function resource_visu(result,resource) {
	console.log(result);
	let result_json = JSON.parse(result);
	for(let index in result_json) {
		let perc = result_json[index].perc * 100;
		let barcolor = get_barcolor(perc);
		let warning = '';
		let info = false;

		if( perc <= 0) {
			warning = 'Nenhum estudante visualizou este Recurso';
			perc = 100;
		} else if (perc < 100) {
			info = true;
		}

		let dropd = '';
		if(info) {
			dropd = 
			`<div class="dropdown no-arrow">
          <a class="dropdown-toggle"  onclick="show_student_notview_resource(event,${result_json[index].id})" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           	<i class="fas fa-info-circle"></i>
           </a>
           <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
           		<div class="dropdown-header student_notview_resource"></div>
           </div>
       </div>`
		}

		$("#resourcevisualization").append(
			`<h4 class="small font-weight-bold">${result_json[index].name} <span class="float-right">${perc}% </span> 
			<a href=""> ${dropd} </a></h4>
        <div class="progress mb-4">
        <div class="progress-bar ${barcolor}" role="progressbar" style="width: ${perc}%" aria-valuenow="${perc}" aria-valuemin="0" aria-valuemax="100"><i>${warning}</i></div>
       </div>`);
	}

}

function show_student_notview_resource(event, resourceid, resource) {
	let url = get_api_url('getanyvisu',"&resourceid=" + resourceid + '&resource='+ resource);
	$.ajax( {'url': url, 'success': (result) => {
		students = JSON.parse(result);
		$('.student_notview_resource').html("Estudantes que não viram este Recurso");
		for(let index in students) {
			if(students[index].view == 0) {
				$('.student_notview_resource').append(`<a class='dropdown-item'>${students[index].firstname} ${students[index].lastname} </a>`);
			}
		}
	}});
}

function show_student_notview_forum(event, forumid) {
	let url = get_api_url('getforumvisu',"&forumid=" + forumid);
	$.ajax( {'url': url, 'success': (result) => {
		students = JSON.parse(result);
		$('.student_notview_forum').html("Estudantes que não viram este forúm");
		for(let index in students) {
			if(students[index].view == 0) {
				$('.student_notview_forum').append(`<a class='dropdown-item'>${students[index].firstname} ${students[index].lastname} </a>`);
			}
		}
	}});
}

function show_student_notview_assign(event, assignid) {
	let url = get_api_url('getassignvisu',"&assignid=" + assignid);
	$.ajax( {'url': url, 'success': (result) => {
		students = JSON.parse(result);
		$('.student_notview_assign').html("Estudantes que não viram esta atividade");
		for(let index in students) {
			if(students[index].view == 0) {
				$('.student_notview_assign').append(`<a class='dropdown-item'>${students[index].firstname} ${students[index].lastname} </a>`);
			}
		}
	}});
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
	add_function_endpoint('getforumvisu', forum_visu);
	add_function_endpoint('getassigment', assign_quantity);
	add_function_endpoint('getassignvisu', assign_visu);
	show_resource_visu('resource');

	$("select#resourceselect").change(function(){
  	var selected = $(this).children("option:selected").val();
  	show_resource_visu(selected);
  	console.log(selected);
  	$("#resourcevisualization").html(" ");
   });

});