<!doctype html>
<html lang="cs">

<head>
	<meta charset="utf8_czech_ci">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>RemanTe - Test App</title>
	<meta name="description" content="Testovací app - Vue,PHP, MySqli">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Add this to <head> -->

	<!-- Load required Bootstrap and BootstrapVue CSS -->
	<link type="text/css" rel="stylesheet" href="//unpkg.com/bootstrap/dist/css/bootstrap.min.css" />
	<link type="text/css" rel="stylesheet" href="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css" />

	<!-- Load polyfills to support older browsers -->
	<script src="//polyfill.io/v3/polyfill.min.js?features=es2015%2CIntersectionObserver" crossorigin="anonymous"></script>

	<!-- Load Vue followed by BootstrapVue -->
	<script src="//unpkg.com/vue@latest/dist/vue.min.js"></script>
	<script src="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.js"></script>

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">

<body>
	<!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->

	<div id="app">

		<div class="containter-fluid">
			<div class="row bg-green">
				<div class="col-12">
					<p class="text-center display-3">
						RemanTe Test App - CRUD -> vue.js , php, MySQLi
					</p>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="row mt-3">
				<div class="col-lg-6">
					<h3 class="text-info mr-1">Produkty</h3>
				</div>
				<div class="col-lg-6">
					<button class="btn btn-info float-right" @click="showAddModal=true">
						<i class="fas fa-box">&nbsp;&nbsp;Přidej produkt</i>
					</button>
				</div>
			</div>
			<hr class="bg-danger">
			<div class="alert alert-danger" v-if="errorMsg">
				{{ errorMsg }}
			</div>
			<div class="alert alert-success" v-if="successMsg">
				{{ successMsg }}
			</div>
			<div class="row">
				<div class="col-lg-12">
					<table class="table table-bordered table-striped">
						<thead>
							<tr class="text-center bg-secondary">
								<th scope="col" @click="sort('product_name')">Název</th>
								<th scope="col" @click="sort('product_id')">ID</th>
								<th scope="col" @click="sort('product_description')">Popis</th>
								<th scope="col" @click="sort('brand_name')">Výrobce</th>
								<th scope="col" @click="sort('category_name')">Kategorie</th>
								<th scope="col" @click="sort('product_ean')">EAN</th>
								<th scope="col" @click="sort('product_price')">Cena</th>
								<th scope="col">Edituj</th>
								<th scope="col">Smaž</th>
							</tr>
						</thead>
						<tbody>
							<tr class="text-center" v-for="product in sortedProducts">
								<td class="align-middle">{{ product.product_name }}</td>
								<td class="align-middle">{{ product.product_id }}</td>
								<td class="align-middle">{{ product.product_description }}</td>
								<td class="align-middle">{{ product.brand_name }}</td>
								<td class="align-middle">{{ product.category_name }}</td>
								<td class="align-middle">{{ product.product_ean }}</td>
								<td class="align-middle">{{ product.product_price }} Kč</td>
								<td class="align-middle"><a href="#" class="text-success" @click="showEditModal=true; selectProduct(product);"><i class="fas fa-edit"></i></a></td>
								<td class="align-middle"><a href="#" class="text-danger" @click="showDeleteModal=true; selectProduct(product)"><i class="fas fa-trash"></i></a></td>
							</tr>
						</tbody>
						<p>
							<button @click="prevPage">Předchozí</button>
							<button @click="nextPage">Další</button>
						</p>
						<p>
							Záznamů:
							<input type="number" name="pageSize" class="imputPageSize" placeholder="Počet produktu" v-model="pageSize">
						</p>
						<!-- debug: sort={{currentSort}}, dir={{currentSortDir}} -->
					</table>
				</div>
			</div>
		</div>
		<!-- Add product -->
		<div id="overlay" v-if="showAddModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Přidej nový produkt</h4>
						<button type="button" class="close" @click="showAddModal=false">
							<span arian-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body p-4">
						<form action="#" method="post">
							<div class="form-group">
								<input type="text" name="name" class="form-control form-control-lg" placeholder="Název" v-model="newProduct.product_name">
							</div>
							<div class="form-group">
								<input type="text" name="description" class="form-control form-control-lg" placeholder="Popis" v-model="newProduct.product_des">
							</div>
							<div class="form-group">
								<label for="exampleFormControlSelect1">Výrobce</label>
								<select name="brand" id="exampleFormControlSelect1" class="form-control" v-model="newProduct.product_brand">
									<option value="1">1 - Bosch</option>
									<option value="2">2 - Denso</option>
									<option value="3">3 - Zexel</option>
									<option value="4">4 - Delphi</option>
									<option value="5">5 - Siemens</option>
									<option value="6">6 - Toyota</option>
									<option value="7">7 - Mitsubishi</option>
									<option value="8">8 - Garrett</option>
								</select>
							</div>
							<div class="form-group">
								<label for="exampleFormControlSelect1">Kategorie:</label>
								<select name="category" id="exampleFormControlSelect1" class="form-control" v-model="newProduct.product_category">
									<option value="1">1 - Vstřikovací čerpadla</option>
									<option value="2">2 - Vysokotlaká čerpadla</option>
									<option value="3">3 - Turbodmychadla</option>
									<option value="4">4 - Vstřikovače</option>
								</select>
							</div>
							<div class="form-group">
								<input type="number" name="ean" class="form-control form-control-lg" placeholder="EAN" v-model="newProduct.product_ean">
							</div>
							<div class="form-group">
								<input type="number" name="price" class="form-control form-control-lg" placeholder="Cena" v-model="newProduct.product_price">
							</div>
							<div class="form-group">
								<button class="btn btn-info btn-block btn-lg" @click="showAddModal=false; addProduct(); clearMsg();">Přidej produkt</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- Edit product -->
		<div id="overlay" v-if="showEditModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Edituj produkt</h4>
						<button type="button" class="close" @click="showEditModal=false">
							<span arian-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body p-4">
						<form action="" method="post">
							<div class="form-group">
								<input type="text" name="name" class="form-control form-control-lg" v-model="currentProduct.product_name">
							</div>
							<div class="form-group">
								<input type="text" name="description" class="form-control form-control-lg" v-model="currentProduct.product_des">
							</div>
							<div class="form-group">
								<label for="exampleFormControlSelect1">Výrobce</label>
								<select name="brand" id="exampleFormControlSelect1" class="form-control" v-model="currentProduct.product_brand">
									<option value="1">1 - Bosch</option>
									<option value="2">2 - Denso</option>
									<option value="3">3 - Zexel</option>
									<option value="4">4 - Delphi</option>
									<option value="5">5 - Siemens</option>
									<option value="6">6 - Toyota</option>
									<option value="7">7 - Mitsubishi</option>
									<option value="8">8 - Garrett</option>
								</select>
							</div>
							<div class="form-group">
								<label for="exampleFormControlSelect1">Kategorie:</label>
								<select name="category" id="exampleFormControlSelect1" class="form-control" v-model="currentProduct.product_category">
									<option value="1">1 - Vstřikovací čerpadla</option>
									<option value="2">2 - Vysokotlaká čerpadla</option>
									<option value="3">3 - Turbodmychadla</option>
									<option value="4">4 - Vstřikovače</option>
								</select>
							</div>
							<div class="form-group">
								<input type="number" name="ean" class="form-control form-control-lg" v-model="currentProduct.product_ean">
							</div>
							<div class="form-group">
								<input type="number" name="price" class="form-control form-control-lg" v-model="currentProduct.product_price">
							</div>
							<div class="form-group">
								<button class="btn btn-info btn-block btn-lg" @click="showEditModal=false; updateProduct(); clearMsg();">Edituj produkt</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- Delete product -->
		<div id="overlay" v-if="showDeleteModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Smaž produkt</h4>
						<button type="button" class="close" @click="showEditModal=false">
							<span arian-hidden="true" @click="showDeleteModal=false">&times;</span>
						</button>
					</div>
					<div class="modal-body p-4">
						<h3 class="text-danger">Opravdu chcete produkt smazat?</h3>
						<h4> Mažete produkt: {{ currentProduct.product_name }}</h4>
						<button class="btn btn-danger btn-lg" @click="showDeleteModal=false; deleteProduct(); clearMsg();">Ano</button>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<button class="btn btn-success btn-lg" @click="showDeleteModal=false">Ne</button>
					</div>
				</div>
			</div>
		</div>

	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>
	<script src="main.js"></script>

</body>

</html>