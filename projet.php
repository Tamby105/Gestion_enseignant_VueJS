<?php 
ob_start(); 
?>

<div id="myApp">
	<div class="container">
		<h1 class="text-center">Create</h1>

		<div class="row">
			<div class="offset-md-3 col-md-6">
				<form method="POST" action="create.php" v-on:submit.prevent="doCreate">
					

					<div class="form-group">
						<label>Nom</label>
						<input type="text" name="nom" class="form-control" />
					</div>

					<div class="form-group">
						<label>Nombre d'heure</label>
						<input type="text" name="nbheures" class="form-control" />
					</div>
					<div class="form-group">
						<label>Taux horaire</label>
						<input type="text" name="tauxhoraire" class="form-control" />
					</div>
					<input type="submit" value="Create User" class="btn btn-primary" />
				</form>
			</div>
		</div>

		<h1 class="text-center">Read</h1>

		<table class="table">
			<tr>
				<th>NUMERO</th>
				<th>NOM</th>
				<th>NOMBRE D'HEURES</th>
				<th>Taux Horaire</th>
				<th>SALAIRE</th>
				<th>ACTION</th>

			</tr>

			<tr v-for="(user, index) in users">
				<td v-text="user.numens"></td>
				<td v-text="user.nom"></td>
				<td v-text="user.nbheures"></td>
				<td v-text="user.tauxhoraire"></td>

				<td v-text="user.nbheures * user.tauxhoraire"></td>
				<td>
					<button type="button" v-bind:data-id="user.numens" v-on:click="showEditUserModal" class="btn btn-primary">Edit</button>

					<form method="POST" action="delete.php" v-on:submit.prevent="doDelete" style="display: contents;">
						<input type="hidden" name="numens" v-bind:value="user.numens" />
						<input type="submit" name="submit" class="btn btn-danger" value="Delete" />
					</form>
				</td>
			</tr>
		</table>
		<p>TOTAL HEURES : {{total}}</p>
		<p>MIN : {{min}}</p>
		<p>MAX : {{max}}</p>
		<div class=row>
			<div class=col-5>
				<canvas id="siH"></canvas>
			</div>

			<div 
				class=col-2>
			</div>
			<div class=col-5>
				<canvas id="siC"></canvas>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal" id="editUserModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Edit User</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				
				<div class="modal-body">
					<form method="POST" action="update.php" v-on:submit.prevent="doUpdate" id="form-edit-user" v-if="user != null">
						<input type="hidden" name="numens" v-bind:value="user.numens" >

						<div class="form-group">
							<label>Nom</label>
							<input type="text" name="nom" v-bind:value="user.nom" class="form-control" >
						</div>

						<div class="form-group">
							<label>Salaire</label>
							<input type="text" name="nbheures" v-bind:value="user.nbheures" class="form-control" >
						</div>
						<div class="form-group">
							<label>Taux Horaire</label>
							<input type="text" name="tauxhoraire" v-bind:value="user.tauxhoraire" class="form-control" >
						</div>
					</form>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" name="submit" class="btn btn-primary" form="form-edit-user">Save changes</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	// initialize Vue JS
	const myApp = new Vue({
		el: "#myApp",
		data: {
			users: [],
			user: null,
			min: 0,
			max: 0,
			total: 0
		},
		methods: {
			// delete user
			doDelete: function () {
				const self = this;
				const form = event.target;

				const ajax = new XMLHttpRequest();
				ajax.open("POST", form.getAttribute("action"), true);

				ajax.onreadystatechange = function () {
					if (this.readyState == 4) {
						if (this.status == 200) {
							// console.log(this.responseText);
							// remove from local array
							for (var a = 0; a < self.users.length; a++) {
								if (self.users[a].id == form.id.value) {
									self.users.splice(a, 1);
									break;
								}
							}
							let i;
							
							// console.log(this.responseText);
							//const users = JSON.parse(this.responseText);
							self.users = users;
							self.total = 0;
							self.min = self.users[0]["nbheures"];
							self.max = self.users[0]["nbheures"];
							for (i = 0; i < self.users.length; i++) {
								self.total = (self.total * 1) + (self.users[i]["nbheures"]*1);
								if((self.min*1)>(self.users[i]["nbheures"]*1)){self.min = self.users[i]["nbheures"];}
								if((self.max*1)<(self.users[i]["nbheures"]*1)){self.max = self.users[i]["nbheures"];}
								
							}
							let val = self.total;

							/*****GRAPHE */
							const siCC = document.getElementById('siC');
							new Chart(siCC, {
								type: 'bar',
								data: {
								//: x,
								labels: ['total', 'max', 'min'],
								datasets: [{
									data: [val, self.max, self.min],
									//data: y,
									backgroundColor: ['#0001ff', '#00ffff', '#00f00f']
								}]
								}
							});

							const siHH = document.getElementById('siH');
							new Chart(siHH, {
								type: 'pie',
								data: {
								//: x,
								labels: ['total', 'max', 'min'],
								datasets: [{
									data: [self.total, self.max, self.min],
									//data: y,
									backgroundColor: ['#0001ff', '#00ffff', '#00f00f']
								}]
								}
							});
							/*****GRAPHE */
						}
					}
				};

				const formData = new FormData(form);
				ajax.send(formData);
			},

			// update the user
			doUpdate: function () {
				const self = this;
				const form = event.target;

				const ajax = new XMLHttpRequest();
				ajax.open("POST", form.getAttribute("action"), true);

				ajax.onreadystatechange = function () {
					if (this.readyState == 4) {
						if (this.status == 200) {
							// console.log(this.responseText);
							const user = JSON.parse(this.responseText);
							// console.log(user);
							// update in local array
							// get index from local array
							var index = -1;
							for (var a = 0; a < self.users.length; a++) {
								if (self.users[a].id == user.id) {
									index = a;
									break;
								}
							}

							// create temporary array
							const tempUsers = self.users;
							// update in local temporary array
							tempUsers[index] = user;
							// update the local array by removing all old elements and inserting the updated users
							self.users = [];
							self.users = tempUsers;

							let i;
							self.total = 0;
							self.min = self.users[0]["nbheures"];
							self.max = self.users[0]["nbheures"];
							for (i = 0; i < self.users.length; i++) {
								self.total = (self.total * 1) + (self.users[i]["nbheures"]*1);
								if((self.min*1)>(self.users[i]["nbheures"]*1)){self.min = self.users[i]["nbheures"];}
								if((self.max*1)<(self.users[i]["nbheures"]*1)){self.max = self.users[i]["nbheures"];}
								
							}

							/*****GRAPHE */
							const siCC = document.getElementById('siC');
							new Chart(siCC, {
								type: 'bar',
								data: {
								//: x,
								labels: ['total', 'max', 'min'],
								datasets: [{
									data: [self.total, self.max, self.min],
									//data: y,
									backgroundColor: ['#0001ff', '#00ffff', '#00f00f']
								}]
								}
							});

							const siHH = document.getElementById('siH');
							new Chart(siHH, {
								type: 'pie',
								data: {
								//: x,
								labels: ['total', 'max', 'min'],
								datasets: [{
									data: [self.total, self.max, self.min],
									//data: y,
									backgroundColor: ['#0001ff', '#00ffff', '#00f00f']
								}]
								}
							});
							/*****GRAPHE */
						}
					}
				};

				const formData = new FormData(form);
				ajax.send(formData);

				// hide the modal
				$("#editUserModal").modal("hide");
			},

			showEditUserModal: function () {
				const id = event.target.getAttribute("data-id");
				
				// get user from local array and save in current object
				for (var a = 0; a < this.users.length; a++) {
					if (this.users[a].numens == id) {
						this.user = this.users[a];
						break;
					}
				}

				$("#editUserModal").modal("show");
			},

			// get all users from database
			getData: function () {
				const self = this;

				const ajax = new XMLHttpRequest();
				ajax.open("POST", "read.php", true);

				ajax.onreadystatechange = function () {
					if (this.readyState == 4) {
						if (this.status == 200) {
							
							// console.log(this.responseText);
							const users = JSON.parse(this.responseText);
							self.users = users;

							let i;
							self.min = self.users[0]["nbheures"];
							self.max = self.users[0]["nbheures"];
							for (i = 0; i < self.users.length; i++) {
								self.total = (self.total * 1) + (self.users[i]["nbheures"]*1);
								if((self.min*1)>(self.users[i]["nbheures"]*1)){self.min = self.users[i]["nbheures"];}
								if((self.max*1)<(self.users[i]["nbheures"]*1)){self.max = self.users[i]["nbheures"];}
								
							}
							console.log(self.userstmp);
							//self.users[0][0] = self.userstmp[0][0];
							/*****GRAPHE */
							const siCC = document.getElementById('siC');
							new Chart(siCC, {
								type: 'bar',
								data: {
								//: x,
								labels: ['total', 'max', 'min'],
								datasets: [{
									data: [self.total, self.max, self.min],
									//data: y,
									backgroundColor: ['#0001ff', '#00ffff', '#00f00f']
								}]
								}
							});

							const siHH = document.getElementById('siH');
							new Chart(siHH, {
								type: 'pie',
								data: {
								//: x,
								labels: ['total', 'max', 'min'],
								datasets: [{
									data: [self.total, self.max, self.min],
									//data: y,
									backgroundColor: ['#0001ff', '#00ffff', '#00f00f']
								}]
								}
							});
							/*****GRAPHE */
						}
					}
				};

				const formData = new FormData();
				ajax.send(formData);
			},

			doCreate: function () {
				const self = this;
				const form = event.target;

				const ajax = new XMLHttpRequest();
				ajax.open("POST", form.getAttribute("action"), true);

				ajax.onreadystatechange = function () {
					if (this.readyState == 4) {
						if (this.status == 200) {
							// console.log(this.responseText);
							const user = JSON.parse(this.responseText);
							// prepend in local array
							self.users.unshift(user);
							let i;
							self.total = 0;
							self.min = self.users[0]["nbheures"];
							self.max = self.users[0]["nbheures"];
							for (i = 0; i < self.users.length; i++) {
								self.total = (self.total * 1) + (self.users[i]["nbheures"]*1);
								if((self.min*1)>(self.users[i]["nbheures"]*1)){self.min = self.users[i]["nbheures"];}
								if((self.max*1)<(self.users[i]["nbheures"]*1)){self.max = self.users[i]["nbheures"];}
								
							}

							/*****GRAPHE */
							const siCC = document.getElementById('siC');
							new Chart(siCC, {
								type: 'bar',
								data: {
								//: x,
								labels: ['total', 'max', 'min'],
								datasets: [{
									data: [self.total, self.max, self.min],
									//data: y,
									backgroundColor: ['#0001ff', '#00ffff', '#00f00f']
								}]
								}
							});

							const siHH = document.getElementById('siH');
							new Chart(siHH, {
								type: 'pie',
								data: {
								//: x,
								labels: ['total', 'max', 'min'],
								datasets: [{
									data: [self.total, self.max, self.min],
									//data: y,
									backgroundColor: ['#0001ff', '#00ffff', '#00f00f']
								}]
								}
							});
							/*****GRAPHE */
						}
					}
				};

				const formData = new FormData(form);
				ajax.send(formData);
			}
		},
		// call an AJAX to fetch data when Vue JS is mounted
		mounted: function () {
			this.getData();
		}
	});
</script>

<style>
	table, th, td {
		border: 1px solid black;
		border-collapse: collapse;
	}
	th, td {
		padding: 25px;
	}
</style>

<?php

$content = ob_get_clean();
$titre = "GESTION D'ENSEIGNANT";
require "template.php";
?>







