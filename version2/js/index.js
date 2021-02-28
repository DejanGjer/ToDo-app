function toggle(source, id, view, user_id){
	console.log(source, id, view, user_id);
	$.ajax({
		url: "update_todo.php",
		method: "POST",
		data:{
			id: id,
			checked: (source.checked) ? 1 : 0,
			view: view,
			user_id: user_id
		},
		success: function(res){
			$("#div_view").html(res);
			$("#div_details").html("");
			if(view === "today"){
				setCookies()
			}
			console.log("uspelo");
		}
	})
}

function checkButton(source, id, view, user_id){
	console.log(source, id, view, user_id);
	$.ajax({
		url: "update_todo.php",
		method: "POST",
		data:{
			id: id,
			checked: ($(source).text() === "Check") ? 1 : 0,
			view: view,
			user_id: user_id
		},
		success: function(res){
			$("#div_view").html(res);
			$("#div_details").html("");
			if(view === "today"){
				setCookies()
			}
			console.log("uspelo");
		}
	})
}

function deleteButton(id){
	$.ajax({
		url: "delete_todo.php",
		method: "POST",
		data:{
			id: id
		},
		success: function(res){
			$("#todo" + id).remove();
			$("#div_details").html("");
			console.log("uspelo");
		}
	})
}

function show_details(id, view){
	$.ajax({
		url: "details.php",
		method: "POST",
		data:{
			show_id: id,
			view: view
		},
		success: function(res){
			$("#div_details").html(res);
		}
	})
}

function quick_add(user_id, view){
	$.ajax({
		url: "update_todo.php",
		method: "POST",
		data:{
			user_id: user_id,
			view: view,
			quick_text: $("#quick_text").val()
		},
		success: function(res){
			$("#div_view").html(res);
			$("#quick_text").val('');
			if(view === "today"){
				setCookies()
			}
		}
	})
}

function up(source){
	console.log("Up")
	console.log($(source))
	console.log($(source).closest("div").attr("id"))
	var wrapper = $(source).closest("div")
	wrapper.insertBefore(wrapper.prev())
	setCookies()
}

function down(source){
	console.log("Down")
	console.log($(source))
	console.log($(source).closest("div").attr("id"))
	var wrapper = $(source).closest("div")
	wrapper.insertAfter(wrapper.next())
	setCookies()
}

function setCookies(){
	var arr = []
	$("#div_uncompleted").children("div").each(function(idx, itm){
		arr.push(parseInt($(this).attr("id").substring(4)))
	})
	var arr_json = JSON.stringify(arr)
	console.log(arr_json)
	var d = new Date()
	d.setTime(d.getTime() + (30*24*60*60*1000))
	var expires = "expires=" + d.toUTCString()
	document.cookie = "order=" + arr_json + ";" + expires + ";path=/"
	/*tasks.forEach( function(element, index) {
		console.log(parseInt(element.attr("id").substring(4)) + "\n")
	});*/
}

/*$(function(){
	$(".up").on("click", function(e){
		console.log("Up")
		console.log($(this).closest("div").attr("id"))
		var wrapper = $(this).closest("div")
		wrapper.insertBefore(wrapper.prev())
	})
	$(".down").on("click", function(e){
		console.log("Down")
		console.log($(this)) 
		console.log($(this).closest("div").attr("id"))
		var wrapper = $(this).closest("div")
		wrapper.insertAfter(wrapper.next())
	})
})*/
