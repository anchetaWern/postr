window.onload = function () {
	var objectGetLength = function (object) {
		var count = 0;
		for (var key in object) {
			if (object.hasOwnProperty(key)) { count++; }
		}
		
		return count;
	};
	
	var tests = new Tests();
	var test0 = tests.create("test 0 - clear()");
	var test1 = tests.create("test 1 - get()");
	var test2 = tests.create("test 2 - set()");
	var test3 = tests.create("test 3 - remove()");
	var test4 = tests.create("test 4 - reset()");
	var test5 = tests.create("test 5 - toObject()");
	var test6 = tests.create("test 6 - fromObject()");
	var test7 = tests.create("test 7 - addEvent()");
	var test8 = tests.create("test 8 - removeEvent()");
	var test9 = tests.create("test 9 - fireEvent()");
	var test10 = tests.create("test 10 - watcher()");
	var test11 = tests.create("test 11 - applyDefaults()");
	var test12 = tests.create("test 12 - defaults");
	var test13 = tests.create("test 13 - events");
	
	// test 0 - clear()
	(function () {
		localStorage.setItem("test", "test");
		Store.clear();
		if (localStorage.length !== 0) { test0.failure(); }
		test0.success();
	}());
	
	// test 1 - get()
	(function () {
		var store = new Store("test1");
		
		localStorage.setItem("store.test1.test", "\"test\"");
		if (store.get("test") !== "test") { test1.failure(); }
		
		localStorage.setItem("store.test1.test", "5");
		if (store.get("test") !== 5) { test1.failure(); }
		
		localStorage.setItem("store.test1.test", "true");
		if (store.get("test") !== true) { test1.failure(); }
		
		localStorage.removeItem("store.test1.test");
		if (store.get("test") !== undefined) { test1.failure(); }
		
		localStorage.setItem("store.test1.test", null);
		if (store.get("test") !== null) { test1.failure(); }
		
		localStorage.setItem("store.test1.test", "null");
		if (store.get("test") !== null) { test1.failure(); }
		
		localStorage.setItem("store.test1.test", "not_parseable");
		if (store.get("test") !== null) { test1.failure(); }
		
		localStorage.setItem("store.test1.test", "{}");
		if (typeof store.get("test") !== "object") { test1.failure(); }
		
		localStorage.setItem("store.test1.test", "[]");
		if (typeof store.get("test") !== "object") { test1.failure(); }
		
		test1.success();
	}());
	
	// test 2 - set()
	(function () {
		var store = new Store("test2");
		
		store.set("test", "test");
		if (localStorage.getItem("store.test2.test") !== "\"test\"") { test2.failure(); }
		
		store.set("test", 5);
		if (localStorage.getItem("store.test2.test") !== "5") { test2.failure(); }
		
		store.set("test", true);
		if (localStorage.getItem("store.test2.test") !== "true") { test2.failure(); }
		
		store.set("test", undefined);
		if (localStorage.getItem("store.test2.test") !== null) { test2.failure(); }
		
		store.set("test", null);
		if (localStorage.getItem("store.test2.test") !== "null") { test2.failure(); }
		
		store.set("test", function () {});
		if (localStorage.getItem("store.test2.test") !== "null") { test2.failure(); }
		
		store.set("test", {});
		if (localStorage.getItem("store.test2.test") !== "{}") { test2.failure(); }
		
		store.set("test", []);
		if (localStorage.getItem("store.test2.test") !== "[]") { test2.failure(); }
		
		test2.success();
	}());
	
	// test 3 - remove()
	(function () {
		var store = new Store("test3");
		
		store.set("test", "test");
		if (store.get("test") !== "test") { test3.failure(); }
		
		store.remove("test");
		if (store.get("test") !== undefined) { test3.failure(); }
		
		test3.success();
	}());
	
	// test 4 - reset()
	(function () {
		Store.clear();
		var store = new Store("test4");
		
		store.set("test", "test");
		store.set("test2", 5);
		store.set("test3", true);
		localStorage.setItem("test", "test");
		localStorage.setItem("store.test4.", "test");
		if (localStorage.length !== 5) { test4.failure(); }
		
		store.reset();
		if (localStorage.length !== 1) { test4.failure(); }
		
		test4.success();
	}());
	
	// test 5 - toObject()
	(function () {
		var store = new Store("test5");
		
		store.set("test", "test");
		store.set("test2", 5);
		store.set("test3", true);
		
		var object = store.toObject();
		var reference = {
			"test": "test",
			"test2": 5,
			"test3": true
		};
		
		if (object.test !== reference.test) { test5.failure(); }
		if (object.test2 !== reference.test2) { test5.failure(); }
		if (object.test3 !== reference.test3) { test5.failure(); }
		if (objectGetLength(object) !== objectGetLength(reference)) { test5.failure(); }
		
		test5.success();
	}());
	
	// test 6 - fromObject()
	(function () {
		var store = new Store("test6");
		
		store.set("test", "test");
		store.set("test2", 5);
		store.set("test3", true);
		
		store.fromObject({
			"test3": false,
			"test4": null
		}, true);
		
		if (store.get("test") !== "test") { test6.failure(); }
		if (store.get("test2") !== 5) { test6.failure(); }
		if (store.get("test3") !== false) { test6.failure(); }
		if (store.get("test4") !== null) { test6.failure(); }
		
		store.fromObject({
			"test4": "test",
			"test5": 5
		});
		
		if (store.get("test") !== undefined) { test6.failure(); }
		if (store.get("test2") !== undefined) { test6.failure(); }
		if (store.get("test3") !== undefined) { test6.failure(); }
		if (store.get("test4") !== "test") { test6.failure(); }
		if (store.get("test5") !== 5) { test6.failure(); }
		
		test6.success();
	}());
	
	// test 7 - addEvent()
	(function () {
		var store = new Store("test7");
		var f = function f() {};
		var f2 = function f2() {};
		
		store.addEvent("test", f);
		store.addEvent("test", f);
		if (objectGetLength(store.listeners) !== 1) { test7.failure(); }
		if (store.listeners.test.length !== 1) { test7.failure(); }
		if (store.listeners.test[0] !== f) { test7.failure(); }
		
		store.addEvent("test", f2);
		if (objectGetLength(store.listeners) !== 1) { test7.failure(); }
		if (store.listeners.test.length !== 2) { test7.failure(); }
		if (store.listeners.test[0] !== f) { test7.failure(); }
		if (store.listeners.test[1] !== f2) { test7.failure(); }
		
		store.addEvent("test2", f);
		store.addEvent("test2", f2);
		if (objectGetLength(store.listeners) !== 2) { test7.failure(); }
		if (store.listeners.test.length !== 2) { test7.failure(); }
		if (store.listeners.test[0] !== f) { test7.failure(); }
		if (store.listeners.test[1] !== f2) { test7.failure(); }
		if (store.listeners.test2.length !== 2) { test7.failure(); }
		if (store.listeners.test2[0] !== f) { test7.failure(); }
		if (store.listeners.test2[1] !== f2) { test7.failure(); }
		
		store.addEvent("*", f);
		if (objectGetLength(store.listeners) !== 3) { test7.failure(); }
		if (store.listeners["*"].length !== 1) { test7.failure(); }
		if (store.listeners["*"][0] !== f) { test7.failure(); }
		
		test7.success();
	}());
	
	// test 8 - removeEvent()
	(function () {
		var store = new Store("test8");
		var f = function f() {};
		var f2 = function f2() {};
		
		try {
			store.removeEvent("test", f);
		} catch (e) {
			test8.failure();
		}
		
		store.addEvent("test", f);
		store.addEvent("test", f2);
		store.addEvent("test2", f);
		store.addEvent("test2", f2);
		store.addEvent("*", f);
		
		store.listeners.test2.push(f2);
		store.removeEvent("test2", f2);
		if (objectGetLength(store.listeners) !== 3) { test8.failure(); }
		if (store.listeners.test2.length !== 1) { test8.failure(); }
		if (store.listeners.test2[0] !== f) { test8.failure(); }
		
		store.removeEvent("test2", f);
		if (objectGetLength(store.listeners) !== 2) { test8.failure(); }
		if (store.listeners.test2 !== undefined) { test8.failure(); }
		
		store.removeEvent("*", f);
		if (objectGetLength(store.listeners) !== 1) { test8.failure(); }
		if (store.listeners["*"] !== undefined) { test8.failure(); }
		
		test8.success();
	}());
	
	// test 9 - fireEvent()
	(function () {
		var store = new Store("test9");
		var count = 0;
		
		var success = function (value, name, store) {
			if (value === "newValue" && name === "test" && store === "test9") { count++; }
			if (value === "newValue" && name === "test2" && store === "test9") { count++; }
		};
		
		var success2 = function (value, name, store) {
			if (value === "newValue" && name === "test" && store === "test9") { count++; }
			if (value === "newValue" && name === "test2" && store === "test9") { count++; }
			if (count === 8) { test9.success(); }
		};
		
		store.listeners["*"] = [success, success2];
		store.listeners.test = [success, success2];
		store.listeners.test2 = [success, success2];
		
		store.fireEvent("test", "newValue");
		store.fireEvent("test2", "newValue");
	}());
	
	// test 10 - watcher()
	(function () {
		var store = new Store("test10", null, 300);
		var lastTime = 0;
		
		store.set("test", "test");
		store.watcher(true);
		store.remove("test");
		
		store.addEvent("test", function () {
			test10.failure();
		});
		
		store.set("test2", "test");
		store.addEvent("test2", function () {
			// if (lastTime !== 0) { console.log(+(new Date) - lastTime); }
			if (lastTime !== 0 && (+(new Date) - lastTime) < 100) {
				test10.failure();
			}
			
			lastTime = +(new Date);
			store.set("test2", Math.random());
		});
		
		store.watcher().watcher().watcher();
		store.watcher().watcher().watcher();
		store.watcher().watcher().watcher();
		store.watcher().watcher().watcher();
		store.remove("test2");
		
		test10.success();
	}());
	
	// test 11 - applyDefaults()
	(function () {
		var store = new Store("test11");
		
		store.defaults.test = "test";
		store.defaults.test2 = 5;
		store.defaults.test3 = true;
		
		store.applyDefaults();
		if (store.get("test") !== "test") { test11.failure(); }
		if (store.get("test2") !== 5) { test11.failure(); }
		if (store.get("test3") !== true) { test11.failure(); }
		
		store.set("test2", 6);
		store.set("test4", true);
		store.defaults.test4 = false;
		store.defaults.test5 = false;
		store.applyDefaults();
		if (store.get("test2") !== 6) { test11.failure(); }
		if (store.get("test4") !== true) { test11.failure(); }
		if (store.get("test5") !== false) { test11.failure(); }
		
		test11.success();
	}());
	
	// test 12 - defaults
	(function () {
		Store.clear();
		var store = new Store("test12", {
			"test": "test",
			"test2": 5,
			"test3": true,
		});
		
		if (store.get("test") !== "test") { test12.failure(); }
		if (store.get("test2") !== 5) { test12.failure(); }
		if (store.get("test3") !== true) { test12.failure(); }
		if (localStorage.length !== 3) { test12.failure(); }
		
		store.set("test", "test2");
		if (store.get("test") !== "test2") { test12.failure(); }
		
		store.applyDefaults();
		if (store.get("test") !== "test2") { test12.failure(); }
		
		store.remove("test");
		if (store.get("test") !== "test") { test12.failure(); }
		
		store.set("test", null);
		store.set("test2", false);
		store.set("test4", 4);
		if (store.get("test") !== null) { test12.failure(); }
		if (store.get("test2") !== false) { test12.failure(); }
		if (store.get("test4") !== 4) { test12.failure(); }
		
		store.reset();
		if (store.get("test") !== "test") { test12.failure(); }
		if (store.get("test2") !== 5) { test12.failure(); }
		if (localStorage.length !== 3) { test12.failure(); }
		
		store.set("test", null);
		store.set("test2", false);
		store.set("test4", 4);
		if (store.get("test") !== null) { test12.failure(); }
		if (store.get("test2") !== false) { test12.failure(); }
		if (store.get("test4") !== 4) { test12.failure(); }
		
		var object = {
			"test3": false,
			"test5": 101
		};
		
		store.fromObject(object);
		
		if (store.get("test") !== "test") { test12.failure(); }
		if (store.get("test2") !== 5) { test12.failure(); }
		if (store.get("test3") !== false) { test12.failure(); }
		if (store.get("test5") !== 101) { test12.failure(); }
		if (localStorage.length !== 4) { test12.failure(); }
		
		var object = {
			"test5": false
		};
		
		store.set("test4", 4);
		store.set("test", undefined);
		store.fromObject(object, true);
		
		if (store.get("test") !== "test") { test12.failure(); }
		if (store.get("test2") !== 5) { test12.failure(); }
		if (store.get("test3") !== false) { test12.failure(); }
		if (store.get("test4") !== 4) { test12.failure(); }
		if (store.get("test5") !== false) { test12.failure(); }
		
		test12.success();
	}());
	
	// test 13 - events
	(function () {
		var store = new Store("test13", null, 300);
		var count = 0;
		
		store.addEvent("*", function (value, name, store) {
			if (value === "newValue" && name === "test" && store === "test13") { count++; }
			if (count === 2) { test13.success(); } else { test13.failure(); }
		});
		
		store.addEvent("test", function (value, name, store) {
			if (value === "newValue" && name === "test" && store === "test13") { count++; }
		});
		
		store.set("test", "newValue");
	}());
};
