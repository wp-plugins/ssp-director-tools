function sort_on_change() {
	var sorton = (this.document.getElementById("sort_on").value);
	this.document.getElementById("sort_direction").disabled =  sorton == "null" || sorton == "random";
	
}

function tags_on_change() {
	this.document.getElementById("tagmode").disabled = this.document.getElementById("tags").value == '';
}

function model_on_change() {
	var mymodel = this.document.getElementById("model").value;
	this.document.getElementById("model_id").disabled = mymodel == "null";
}

