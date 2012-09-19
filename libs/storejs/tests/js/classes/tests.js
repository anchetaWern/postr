(function () {
	var Test = function (creator, name) {
		this.creator = creator;
		this.name = name;
		this.succeeded = false;
		this.failed = false;
		
		this.element = document.createElement("div");
		this.element.innerHTML = this.name + '... <b class="orange">WAITING</b>';
		document.body.appendChild(this.element);
	};
	
	Test.prototype.success = function () {
		if (!this.failed && !this.succeeded) {
			this.succeeded = true;
			this.creator.successful++;
			this.creator.update();
			this.element.innerHTML = this.name + '... <b class="green">OK</b>';
		}
		
		return this;
	};
	
	Test.prototype.failure = function () {
		this.failed = true;
		if (this.succeeded) {
			this.succeeded = false;
			this.creator.successful--;
			this.creator.update();
		}
		
		this.element.innerHTML = this.name + '... <b class="red">FAIL</b>';
		return this;
	};
	
	var Tests = this.Tests = function () {
		this.count = 0;
		this.successful = 0;
		this.update();
	};
	
	Tests.prototype.update = function () {
		document.getElementById("count").innerHTML = this.count;
		document.getElementById("successful").innerHTML = this.successful;
		if (this.count === this.successful) {
			document.getElementById("result").innerHTML = '<b class="green">ALL OK</b>';
		} else {
			document.getElementById("result").innerHTML = '<b class="red">FAIL</b>';
		}
		
		return this;
	};
	
	Tests.prototype.create = function (name) {
		this.count++;
		this.update();
		return new Test(this, name);
	};
}());
