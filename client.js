$(document).ready(function () {

	var data 		= "";
	var logFlag 	= "LOG";
	// do not run unnecessary lines 
	// if it's not layout page
	if($('body#body-layout').length) {
		var searchFlag 	= "SEARCH";
		var insertFlag 	= "INSERT";
		var updateFlag 	= "UPDATE";
		validationForm();
	}

	$("button[class='accordion']").on('click', function () {
		$(this).toggleClass("active");
		$(this).next().toggleClass("show");
	});

	$("#logout").on('click', function () {

		do_ajax('server.php', 'logout', null, 'text').done(function () {
			window.setTimeout(function() {
				window.location.href = "index.php";
			}, 1000);
		});
	});

	$("button[type='submit']").on('click', function (e) {

		e.preventDefault();
		switch (this.id) {
			case 'btn-login':
				data = setJSONData(logFlag);
				checkLogin(data);
				break;
			case 'btn-search':
				checkFields();
				data = setJSONData(searchFlag);
				searchValues(data);
				break;
			case 'btn-add':
				if(!$('.val-form-add').valid()) break;
				data = setJSONData(insertFlag);
				insertValues(data);
				break;
			case 'btn-upd':
				if(!$('.val-form-upd').valid()) break;
				data = setJSONData(updateFlag);
				updateValues(data);
				break;
			case 'btn-rslt-upd':
				usernamePick();
				break;
			default:
				break;
		}
	});

	function setJSONData(flag) {

		var data = "";
		if(flag == "LOG") {
			data = JSON.stringify({
					username 		: 	$("#username").val(),
					password 		: 	$("#password").val()
			});
		}
		else if(flag == "SEARCH") {
			data = JSON.stringify({
					accountName 	: 	$("#search-account-name").val(),
					username 		: 	$("#search-username").val(),
					url 			: 	$("#search-url").val()
			});
		}
		else if(flag == "INSERT") {
			data = JSON.stringify({
					accountName 	: 	$("#add-account-name").val(),
					username 		: 	$("#add-username").val(),
					password 		: 	$("#add-password").val(),
					comment 		: 	$("#add-comment").val(),
					url 			: 	$("#add-url").val(),
			});
		}
		else if(flag == "UPDATE") {

			passwordVal = $("#upd-new-password").val();
			// if new password is empty post the old val
			if (!$("#upd-new-password").val()) 
				passwordVal = $("#upd-password").val();
			data = JSON.stringify({
					old_username 	: 	$("#upd-rslt-username").val(),
					accountName 	: 	$("#upd-account-name").val(),
					username 		: 	$("#upd-username").val(),
					password 		: 	passwordVal,
					comment 		: 	$("#upd-comment").val(),
					url 			: 	$("#upd-url").val(),
			});
		}
		return data;
	}

	function searchValues(data) {

		var check = checkFields();
		$("tbody").empty();

		if(check == false) {
			// hide the table
			$('.table-responsive').hide();
			// find the content, replace it and show it
			$('.no-rslt').find('i').html("No results yet");
			$('.no-rslt').show();
			// hide update if needed
			$('.form-update').hide();
			// enable the require update message
			$('#update-msg').show();
			return;
		}
		// Clean input fields for the next requests
		$('#btn-search').closest('form').find('input[type=text]').val('');

		do_ajax('server.php', 'search', data, 'json').done(function (data) {
			var returned = JSON.parse(JSON.stringify(data));

			if(returned.length == 0) {
				// hide the table
				$('.table-responsive').hide();
				// find the content, replace it and show it
				$('.no-rslt').find('i').html("No results found");
				$('.no-rslt').show();
				// hide update if needed
				$('.form-update').hide();
				// enable the require update message
				$('#update-msg').show();
				return;
			}
			// hide the error
			$('.no-rslt').hide();
			// show the record that client asked
			$('.table-responsive').show();
			// enable update if needed
			$('.form-update').show();
			// hide the require update message
			$('#update-msg').hide();
			
			for(val in returned) {
				$('#table-rslt > tbody:last-child').append(
					'<tr>'+
					'<td>'+returned[val]['id']+'</td>'+
					'<td>'+returned[val]['account_name']+'</td>'+
					'<td>'+returned[val]['username']+'</td>'+
					'<td>'+returned[val]['password']+'</td>'+
					'<td>'+returned[val]['comment']+'</td>'+
					'<td>'+returned[val]['url']+'</td>'+
					'</tr>');
			}
		});
	}

	function insertValues(data) {

		do_ajax('server.php', 'insert', data, 'json').done(function (data) {
			var returned = JSON.parse(JSON.stringify(data));

			if(!duplicateCheck(returned)) return;
			$("tbody").empty();
			for(val in returned) {
				$('#table-rslt > tbody:last-child').append(
					'<tr>'+
					'<td>'+returned[val]['id']+'</td>'+
					'<td>'+returned[val]['account_name']+'</td>'+
					'<td>'+returned[val]['username']+'</td>'+
					'<td>'+returned[val]['password']+'</td>'+
					'<td>'+returned[val]['comment']+'</td>'+
					'<td>'+returned[val]['url']+'</td>'+
					'</tr>');
			}
			$('.no-rslt').hide();
			$('.table-responsive').show();
			$('#btn-add').closest('form').find('input').val('');
		});
	}

	function updateValues(data) {

		do_ajax('server.php', 'update', data, 'json').done(function(data) {
			var returned = JSON.parse(JSON.stringify(data));

			if(!duplicateCheck(returned)) return;
			$("tbody").empty();
			for(val in returned) {
				$('#table-rslt > tbody:last-child').append(
					'<tr>'+
					'<td>'+returned[val]['id']+'</td>'+
					'<td>'+returned[val]['account_name']+'</td>'+
					'<td>'+returned[val]['username']+'</td>'+
					'<td>'+returned[val]['password']+'</td>'+
					'<td>'+returned[val]['comment']+'</td>'+
					'<td>'+returned[val]['url']+'</td>'+
					'</tr>');
			}
			$('.no-rslt').hide();
			$('.table-responsive').show();
			$('#btn-upd').closest('form').find('input').val('');

		});
	}

	function usernamePick() {

		$('#table-rslt > tbody > tr').each(function () {
			var tableUsername = $(this).children('td:eq(2)').html();
			var inputUsername = $('#upd-rslt-username').val();
			if(tableUsername == inputUsername ) {
				$("#upd-account-name")	.val($(this).children('td:eq(1)').html());
				$("#upd-username") 		.val($(this).children('td:eq(2)').html());
				$("#upd-password") 		.val($(this).children('td:eq(3)').html());
				$("#upd-comment") 		.val($(this).children('td:eq(4)').html());
				$("#upd-url") 			.val($(this).children('td:eq(5)').html());
			}
		});
	}

	function do_ajax(url, action, data, dataType) {

		return $.ajax({
			url 	: url,
			type 	: 'POST',
			dataType: dataType,
			data 	: { "action": action, data }
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			console.log(jqXHR, textStatus, errorThrown);
		});
	}

	function duplicateCheck(data) {

		var duplicateCheck = data[0];
		if(!(duplicateCheck == 'Duplicate')) return true;

		var columnDuplicate = data[5].replace(/\'/g, '');
		var valueDuplicate 	= data[2].replace(/\'/g, '');
		$('.no-rslt').find('i').html(columnDuplicate+": "+valueDuplicate+" already exists!");
		$('.no-rslt').show();
		$('.table-responsive').hide();
		return false;
	}

	function checkFields() {

		if(!$("#search-account-name").val() && !$("#search-username").val() && !$("#search-url").val()) return false;
		return true;
	}

	function checkLogin(data) {

		do_ajax('server.php', 'login', data, 'json').done(function (data) {
			var returned = JSON.parse(JSON.stringify(data));
			if(!returned) {
				console.log("Login wasn't successful!");
				$("#login-error").show();
				$("#login-successful").hide();
				return;
			}
			$("#login-error").hide();
			$("#login-successful").show();
			console.log("Login was successful!");
			//Direct the user to another page
			window.location.href = "layout.php"; 
		});
	}

	function validationForm() {

		$.validator.setDefaults({
			rules: {
				accountName: {
					regex: "^[A-Za-z0-9\_]*[A-Za-z0-9][A-Za-z0-9\_\ ]*$",
					minlength: 4,
	        		required: true
				},
				username: {
					regex: "^[A-Za-z0-9\_]*[A-Za-z0-9][A-Za-z0-9\_]*$",
					minlength: 4,
					required: true
				}
			},
			messages: {
				accountName: {
				 	required: "account name is required",
				 	minlength: "Please enter at least 4 chars"
				},
				username: {
				 	required: "username is required",
				 	minlength: "Please enter at least 4 chars"
				},
				password: {
					required: "password is required",
					minlength: "Please enter at least 4 chars"
				},
				confirmPassword: {
					required: "confirm password is required",
					minlength: "Please enter at least 4 chars",
					equalTo: "Please enter the same password"
				}
			},
			errorElement: 'span',
			errorClass: 'help-block',
			errorPlacement: function(error, element) {
				error.insertAfter(element);
			},
			highlight: function (element) {
				$(element).closest('.form-group').addClass('has-error');
				$(element).closest('.form-group').removeClass('has-success');
			},
			unhighlight: function(element) {
				$(element).closest('.form-group').removeClass('has-error');
				$(element).closest('.form-group').addClass('has-success');
			}

		});

		$.validator.addMethod(
	        "regex",
	        function(value, element, regexp) {
	            var re = new RegExp(regexp);
	            return this.optional(element) || re.test(value);
	        },
	        "Please check your input."
		);

		$(".val-form-add, .val-form-upd").validate({
			rules: {
				password: {
					regex: "^[A-Za-z0-9\_]+$",
					minlength: 4,
					required: true
				},
				confirmPassword: {
					regex: "^[A-Za-z0-9\_]+$",
					minlength: 4,
					// just a trick for a dynamic equalTo, see var "count"
					equalTo: ".checkpass1",
					required: true
				}
			}
		});

		$(".val-form-upd").validate({
			rules: {
				password: {
					regex: "^[A-Za-z0-9\_]+$",
					minlength: 4,
					required: {
						depends:function () {
							if(!$("input[id='upd-password']").val()) {
								return true;
							}
							return false;
						}
					}
				},
				confirmPassword: {
					regex: "^[A-Za-z0-9\_]+$",
					minlength: 4,
					// just a trick for a dynamic equalTo, see var "count"
					equalTo: ".checkpass2",
					required: {
						depends:function () {
							if(!$("input[id='upd-password']").val()) {
								return true;
							}
							return false;
						}
					}
				}
			}
		});
	}
});