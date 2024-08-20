
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