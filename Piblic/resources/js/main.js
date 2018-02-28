	/*price range*/

 	$('#sl2').slider();

	var RGBChange = function() {
	  $('#RGB').css('background', 'rgb('+r.getValue()+','+g.getValue()+','+b.getValue()+')')
	};
		
	var d = "";
	var categories = [];
	var products = [];
	var cart = [];
	var currentUser;
	var isConnected = false;

	/*scroll to top*/
	$(document).ready(function(){
		$(function () {
			$.scrollUp({
				scrollName: 'scrollUp', // Element ID
				scrollDistance: 300, // Distance from top/bottom before showing element (px)
				scrollFrom: 'top', // 'top' or 'bottom'
				scrollSpeed: 300, // Speed back to top (ms)
				easingType: 'linear', // Scroll to top easing (see http://easings.net/)
				animation: 'fade', // Fade, slide, none
				animationSpeed: 200, // Animation in speed (ms)
				scrollTrigger: false, // Set a custom triggering element. Can be an HTML string or jQuery object
						//scrollTarget: false, // Set a custom target element for scrolling to the top
				scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
				scrollTitle: false, // Set a custom <a> title if required.
				scrollImg: false, // Set true to use image
				activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
				zIndex: 2147483647 // Z-Index for the overlay
			});

			if (isset($.cookie("MyUser"))) {
				currentUser = JSON.parse($.cookie("MyUser"));
				isConnected = true;
				upDateMenu(true, currentUser["fname"], currentUser["lname"]);
			}


			if (isset($.cookie("eShopCart"))) {
				cart = JSON.parse($.cookie("eShopCart"));
				upDateBadge(cart.length);
			}

			loginResterConfig();
			createCategorySection();
			$("#authentificationAlert").hide();

			$("#checkout").on('click', function (e) {
				checkoutAction();
			});

			// Load content of cart modal
			$("#cartModal").on('shown.bs.modal', function() {
				console.log(cart.length);
				$("#cartLength").html(cart.length);
				showCartContent();
			});

			// Register new costumer
			$("#register-form").submit(function (e) {
				e.preventDefault();

				var firstName = $("#RFirstname").val();
				var lastName = $("#RLastname").val();
				var email = $("#REmail").val();
				var password = $("#RPassword").val();
				var confirmPassword = $("#RConfirmP").val();

				if (validateEmail(email) && validatePassword(password, confirmPassword)) {
					hideError();
					register(firstName, lastName, email, password);
				} else {
					console.log("email or password not valid");
					showError("invalid field(s), thank you for checking again");
				}

				return false;
			});

			// Login costumer
			$("#login-form").submit(function (e) {
				e.preventDefault();

				var email = $("#LEmail").val();
				var password = $("#LPassword").val();

				if (email === "" || password === "" || !validateEmail(email)) {
					console.log("Error!!!");
					showError("invalid field(s), thank you for checking again");
				} else {
					hideError();
					if (login(email, password)) {

					} else {
						cleanFields();
					}
				}
				return false;
			});
		});
	});

	function checkoutAction() {

		if (isConnected) {
			if (isset(cart)) {

				var json = createCheckOutJson();
				$.ajax({
					url: "http://localhost/workspace-TP/ecommerce/application/controllers/orderServices.php/?action=add",
					type: "POST",
					data: json,
					success: function(data) {
						if (isset(data.success)) {
							console.log(data.success);
							cart = [];
							$.cookie("eShopCart", JSON.stringify(cart));
							$("#cartModal").modal('hide');
                            upDateBadge(cart.length);
							alert("Votre commande est passé avec success");
						} else {
							console.log(data.error);
						}
					}
				});
			}
		} else {
			$("#cartModal").modal('hide');
			alert("Vous n'ete pas connecté");
		}

	}

	function createCheckOutJson() {
		var userID = currentUser["id"];
		var json = "{\"order\" : {\"idCustomer\" : \"" + userID + "\", \"listProd\" : [";

		cart.forEach(function (value, index) {
			json += "{\"idProd\": \"" + value.prod.id + "\", \"qut\": \"" + value.prodQut + "\"}";
			if (index !== cart.length - 1) {
				json += ",";
			}
		});
		json += "]}}";

		console.log(json);

		return json;
	}

	function removeFromCart(row) {
		cart.forEach(function (value, index) {
			if (index == row) {
				if (value.prodQut > 1) {
					value.prodQut -= 1;
				} else {
					cart.splice(index, 1);
					if (cart.length == 0) {
						$("#cartModal").modal('hide');
					}
				}
			}
		});
		upDateBadge(cart.length);
		$.cookie("eShopCart", JSON.stringify(cart));
		showCartContent();
	}

	function showCartContent() {
		$("#cartModal tbody").empty();
		resetCartModalContent();

		if (cart.length > 0) {
			setUpTableHeader();
			cart.forEach(function (value, index) {
				console.log(value.prod.img);
				var row = "<tr>" +
					"<th scope=\"row\">" + parseInt(index + 1) + "</th>" +
					"<td><img src=\"resources/images/" + value.prod.img + ".jpg\" style=\" width: 80px;\" alt=\"\" /></td>" +
					"<td>" + value.prod.name + "</td>" +
					"<td>" + value.prod.desc + "</td>" +
					"<td>" + value.prod.price + "</td>" +
					"<td>" + value.prodQut + "</td>" +
					"<td><a onclick='removeFromCart(" + index + ")'> <span class=\"glyphicon glyphicon-minus\"></span> </a></td>" +
					"</tr>";
				$("#cartModal tbody").append(row);
			});
		} else {
			var emptyCart = "<div class='cart-is-empty'>" +
				"  <spam>The cart is empty</spam><br/>" +
				"  <i class=\"fa fa-shopping-cart\"></i>" +
				"</div>";
			$("#cartModal .modal-body").append(emptyCart);
		}
	}

	function setUpTableHeader() {
		var tableHeader = "<tr>\n" +
			"<th scope=\"col\">#</th>\n" +
			"<th scope=\"col\">image</th>\n" +
			"<th scope=\"col\">Name</th>\n" +
			"<th scope=\"col\">Desc</th>\n" +
			"<th scope=\"col\">Price</th>\n" +
			"<th scope=\"col\">Qut</th>\n" +
			"<th scope=\"col\">Remove</th>\n" +
			"</tr>";
		$("#cartModal thead").append(tableHeader);
	}

	function resetCartModalContent() {
		$("#cartModal .cart-is-empty").remove();
		$("#cartModal thead").empty();
		$("#cartModal tbody").empty();
	}

	// show error on login fields are empties
	function showError(msg) {
		$("#authentificationAlert").html("<strong>Error : </strong> " + msg + " .");
		$("#authentificationAlert").show();
	}

	// hire error message
	function hideError() {
		$("#authentificationAlert").hide();
	}

	// Clear all inputs
	function cleanFields() {
		$("#login-form input[type=email]").val("");
		$("#login-form input[type=password]").val("");
		$("#register-form input[type=text]").val("");
		$("#register-form input[type=password]").val("");
		$("#register-form input[type=email]").val("");
	}

	// Validation de l'email bason sur regular expression
	function validateEmail(email) {
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(String(email).toLowerCase());
	}

	// validation mot de pass
	function validatePassword(password, confirmPassword) {
		if (password.length > 7) {
			if (password === confirmPassword) {
				return true;
			}
		}
		return false;
	}

	/**
	 *  call register webservice
	 */
	function register(fname, lname, email, password) {
		hideError();
		var json ='{ "data": ["' + fname + '", "' + lname + '", "' + email + '", "' + password +'"]}';
		$.ajax({
			url: "http://localhost/workspace-TP/ecommerce/application/controllers/costomerServices.php/?action=register",
			type: "POST",
			data: json,
			success: function (data) {
				if (isset(data.success)) {
					console.log(data);
					login(email, password)
					$('#loginModal').modal('hide');
					return true;
				} else if (isset(data.error)) {
					showError(data.error);
					console.log(data.error);
					return false;
				}
			}
		});
	}

	/**
	 *  call login webservice
	 */
	function login(email, password) {
		hideError();
		var json = '{ "data": ["' + email + '","' + password + '"] }';

		$.ajax({
			url: "http://localhost/workspace-TP/ecommerce/application/controllers/costomerServices.php/?action=check",
			type: "POST",
			data: json,
			success: function (data) {
				if (!isset(data.error)) {
					console.log(data.result[0]);

					// NOTE(Team): Create string of data separate with ";" and save the string in cookie
					currentUser = {
						"id": data.result[0].id,
						"fname": data.result[0].firstname,
						"lname": data.result[0].lastname,
						"email": data.result[0].email
					};

					$.cookie("MyUser", JSON.stringify(currentUser));
					isConnected = true;

					upDateMenu(true, data.result[0].firstname, data.result[0].lastname);
					$('#loginModal').modal('hide');
					return true;
				} else {
					showError(data.error);
					console.log(data.error);
					return false;
				}

			}
		})
	}

	function isset(value) {
		if (typeof(value) !== "undefined") {
			return true;
		}
		return false;
	}

	/**
	 * UpDate mainMenu (login / logout)
	 */
	function upDateMenu(isConnected, fname, lname) {
		console.log(fname);
		console.log(lname);
		var login = "<a href=\"#\" data-toggle=\"modal\" data-target=\"#loginModal\"><i class=\"fa fa-lock\"></i> Login</a>";
		var logout = "<a href=\"#\" onclick='logout();'><i class=\"fa fa-lock\"></i> Logout (" + fname + " " + lname + ")</a>";
		var loginSection = $("#mainMenu li");
		loginSection.last().empty();

		if (isConnected) {
			console.log(loginSection);
			loginSection.last().html(logout);
		} else {
			loginSection.last().html(login);
		}
	}

	function logout() {
		$.removeCookie("MyUser");
		isConnected = false;
		upDateMenu(false);
	}

	/**
	 * Configuration of Login/Register Section
	 */
	function loginResterConfig() {
		$('#login-form-link').click(function(e) {
			$("#login-form").delay(100).fadeIn(100);
			$("#register-form").fadeOut(100);
			$('#register-form-link').removeClass('active');
			$(this).addClass('active');
			e.preventDefault();
		});
		$('#register-form-link').click(function(e) {
			$("#register-form").delay(100).fadeIn(100);
			$("#login-form").fadeOut(100);
			$('#login-form-link').removeClass('active');
			$(this).addClass('active');
			e.preventDefault();
		});
	}


	/**
	 * get categories
	 */
	function createCategorySection() {

		$.ajax({
			url: "http://localhost/workspace-TP/ecommerce/application/controllers/categoryServices.php",
			type: "GET",
			success: function (data) {
				if ( data.result != null ) {
					$("#accordian").empty();
					categories = data.result;

					categories.forEach(function (value) {
						d = "<div class=\"panel panel-default\">"
							+ "<div class=\"panel-heading\">"
							+ "<h4 class=\"panel-title\">"
							+ "<a "
							+ "onclick='showProductsByCat(" + value.id + ");'"
							+ ">"
							+ value.name
							+ "</a></h4></div></div>";
						$("#accordian a").css("cursor", "pointer");
						$("#accordian").append(d);

					});

					showProductsByCat(categories[0].id);
				} else {
					console.log("Error : " + data.error);
				}
			}
		});

	}

	/**
	 * Add to card (begin)
	 */
	function addToCart(idProd) {
		if (isset($.cookie("eShopCart"))) {
			cart = JSON.parse($.cookie("eShopCart"));
		}

		products.forEach(function (value) {
			if (value.id == idProd) {
				cartContain(value);
			}
		});

		//console.log();
		upDateBadge(cart.length);

		$.cookie("eShopCart", JSON.stringify(cart));
	}

	function cartContain(prod) {
		var exists = false;
		cart.forEach(function (value) {
			if (value.prod.id == prod.id) {
				value.prodQut += 1;
				exists = true;
			}
		});
		if (!exists) {
			addNewProdToCart(prod);
		}
	}

	function addNewProdToCart(prod) {
		var element = {
			prod: prod,
			prodQut: 1
		};
		cart.push(element);
	}
	/**
	 * Add to card (End)
	 */

	/**
	 * get product by ID
	 */
	function getProductById(idProd) {
		console.log("log: selected prod id : " + idProd);
		$.ajax({
			url: "http://localhost/workspace-TP/ecommerce/application/controllers/productServices.php",
			type: "GET",
			data: {
				action: "readById",
				idprod: idProd
			},
			success: function(data) {
				console.log("returned product : " + data.result[0].id);

			}
		})
	}

	/**
	 * get products by category
	 */
	function showProductsByCat(idcat) {
		console.log("log: Selected category : " + idcat);
		$.ajax({
			url: "http://localhost/workspace-TP/ecommerce/application/controllers/productServices.php/",
			type: "GET",
			data: {
				action: "readByCat",
				idCat: idcat
			},
			success: function(data, status) {
				if ( data.result != null ) {
					products = data.result;
					$("#items").empty();

					products.forEach(function (value) {
						d = "<div class=\"col-sm-4\">" +
							"<div class=\"product-image-wrapper\">" +
							"<div class=\"single-products\">" +
							"<div class=\"productinfo text-center\">" +
							"<img src=\"resources/images/" + value.img + ".jpg\" alt=\"\" />" +
							"<h2>" + value.price + " DH</h2>" +
							"<p>" + value.name + "</p>" +
							"<a href=\"#\" class=\"btn btn-default add-to-cart\" onclick='addToCart(" + value.id + ");'><i class=\"fa fa-shopping-cart\"></i>Add to cart</a>" +
							"</div>" +
							"<div class=\"product-overlay\">" +
							"<div class=\"overlay-content\">" +
							"<h2>"+ value.price +" DH</h2>" +
							"<p>Easy Polo Black Edition</p>" +
							"<a href=\"#\" class=\"btn btn-default add-to-cart\" onclick='addToCart(" + value.id + ");'><i class=\"fa fa-shopping-cart\"></i>Add to cart</a>" +
							"</div></div></div></div></div>";

						$("#items").append(d);

					});

					hidePagination();
					console.log(products)
				} else {
					console.log("Error : " + data.error);
				}
			}
		});
	}

	function upDateBadge(numOfProducts) {
		$(".badge").empty();
		if (numOfProducts != 0) {
			$(".badge").html(numOfProducts);
		}
	}

	/*
	 * hide pagination if the count of products less than 13
	 */
	function hidePagination() {
		if (products.length <= 12) {
			$(".pagination").hide();
		}
	}
