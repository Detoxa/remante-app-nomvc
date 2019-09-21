window.app = new Vue({
    el: '#app',
    data: {
        pageTitle: "RemanTe - Test App (Vue.js - PHP - MySQLi)",
        errorMsg: "",
		successMsg: "",
		showAddModal: false,
		showEditModal: false,
		showDeleteModal: false,
        products: [],
		newProduct: {product_name: "", product_des: "", product_brand: "", product_category: "", product_ean: "", product_price: ""},
        currentProduct: {},
        currentSort:'product_name',
        currentSortDir:'asc',
        pageSize:5,
        currentPage:1,
        search: '',
        sortKey: '',
        reverse: false
	},
	mounted: function(){
        this.getAllProducts();
        //console.log(products);
	},
	methods: {
		getAllProducts(){
			axios.get("http://remante-app-nomvc.loc/inc/action.php?action=read").then(function(response){
				if(response.data.error){
					app.errorMsg = response.data.message;
				} else {
					app.products = response.data.products;
				}
			});
		},
		addProduct(){
			var formData = app.toFormData(app.newProduct);
			//console.log(app.newProduct);
			axios.post("http://remante-app-nomvc.loc/inc/action.php?action=create", formData).then(function(response){
				app.newProduct = {name: "", des: "", brand: "", category: "", ean: "", price: ""};
				if(response.data.error){
					app.errorMsg = response.data.message;
				} else {
					app.successMsg = response.data.message;
					app.getAllProducts();
				}
			});
        },
        updateProduct(){
			var formData = app.toFormData(app.currentProduct);
			//console.log(app.newProduct);
			axios.post("http://remante-app-nomvc.loc/inc/action.php?action=update", formData).then(function(response){
                app.currentProduct = {name: "", des: "", brand: "", category: "", ean: "", price: ""};
                //console.log(app.currentProduct);
				if(response.data.error){
					app.errorMsg = response.data.message;
				} else {
					app.successMsg = response.data.message;
					app.getAllProducts();
				}
			});
        },
        sortBy: function(sortKey) {
            this.reverse = (this.sortKey == sortKey) ? ! this.reverse : false;
            this.sortKey = sortKey;
        },
        deleteProduct(){
			var formData = app.toFormData(app.currentProduct);
			//console.log(app.newProduct);
			axios.post("http://remante-app-nomvc.loc/inc/action.php?action=delete", formData).then(function(response){
                app.currentProduct = {name: "", des: "", brand: "", category: "", ean: "", price: ""};
                //console.log(app.currentProduct);
				if(response.data.error){
					app.errorMsg = response.data.message;
				} else {
					app.successMsg = response.data.message;
					app.getAllProducts();
				}
			});
		},
		toFormData(obj){
			var fd = new FormData();
			for(var i in obj){
				fd.append(i,obj[i]);
			}
			return fd;
        },
        selectProduct(product){
            app.currentProduct = product;
        },
        clearMsg(){
            app.errorMsg = "";
            app.successMsg = "";
        },
        sort:function(s) {
            //if s == current sort, reverse
            if(s === this.currentSort) {
              this.currentSortDir = this.currentSortDir==='asc'?'desc':'asc';
            }
            this.currentSort = s;
        },
        nextPage:function() {
            if((this.currentPage*this.pageSize) < this.products.length) this.currentPage++;
        },
        prevPage:function() {
            if(this.currentPage > 1) this.currentPage--;
        },
        csvExport(arrData) {
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += [
              Object.keys(arrData[0]).join(";"),
              ...arrData.map(item => Object.values(item).join(";"))
            ]
              .join("\n")
              .replace(/(^\[)|(\]$)/gm, "");
      
            const data = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", data);
            link.setAttribute("download", "export.csv");
            link.click();
        }
    },
    computed:{
        sortedProducts:function() {
            //console.log(products);
          return this.products.sort((a,b) => {
            let modifier = 1;
            if(this.currentSortDir === 'desc') modifier = -1;
            if(a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
            if(a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
            return 0;
            }).filter((row, index) => {
            let start = (this.currentPage-1)*this.pageSize;
            let end = this.currentPage*this.pageSize;
            if(index >= start && index < end) return true;
            });
        },
        csvData() {
            return this.sortedProducts.map(item => ({
              ...item
            }));
        }
    }
});