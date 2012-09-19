# [store.js](https://github.com/frankkohlhepp/store-js) v3.1.2
*A powerful toolkit for localStorage.*

### Info
store.js aims to solve a bunch of localStorage's problems.  
The main problem is that localStorage stringifies every value you save.  
Starting with that issue, localStorage is not very powerful: You just can save key-value pairs. And that's it.  
You don't have any structure (or groups), you cannot make a second, clean group, you cannot set default values,
and you cannot just put a group of values into an object. Also, there are no events.

store.js solves each and every one of those problems!

### Howto
store.js groups your values into something called a store. Multiple stores are separated from each other.  
So let's make a new store:

``` javascript
var settings = new Store("settings");
```

Just choose a name for the new store, and save it in a variable. **Always** remember to use the "new" keyword! Never leave it off!  
Now you can almost normally get, set and remove values:

``` javascript
settings.set("color", "blue");
settings.set("enable_test1", true);
settings.set("number_of_rainbows", 8);

// and

var color = settings.get("color");

// and

settings.remove("color");
```

Pretty straight forward. But it automatically JSON-serializes and JSON-deserializes your values. So, if you put a boolean in, a boolean comes out.
All data types are supported (including objects and arrays), except functions and types that JSON can't parse.  
null is supported as a data type. undefined however means that there is no such value. So, to save "nothing", always use null.

store.js saves the values with the store's name and a prefix, e.g. the key "color" becomes "store.settings.color" in localStorage.

### Defaults
Now let's take a look at how you can specify some default values:

``` javascript
var defaults = {
	"color": "red",
	"enable_test1": false
};

var settings = new Store("settings", defaults);
```

You simply create an object with default values and pass it as the second argument to the constructor.  
Defaults are saved in the instance variable "defaults". You can simple get or set the values from this object.

``` javascript
var value = settings.defaults.myValue;

// and

settings.defaults.myValue = value;
```

After changing the defaults, **always** reapply them by calling the method applyDefaults().  
Of course this will not change values that are already set in localStorage.

``` javascript
settings.defaults.myNewValue = something;
settings.applyDefaults();
```

Everything in your store is always based on the defaults. You can't remove a value which has a default.  
If you try to do so, the value is set to the default. If you want to remove a value with a default value, remove the default value first!

### Objects
If you want to work with the contents of a store as an object, you can simply export and import the contents.  
There are two methods for it: toObject() and fromObject().

``` javascript
var mySettings = settings.toObject();

// and

settings.fromObject(mySettings);
```

fromObject() by default replaces the complete store with the values passed in.
(But even here, defaults are taken into account, you can't remove values which have defaults.)  
This means, everything in the store gets removed, and replaced with your values.

But you can also merge. In this case, no values currently in the store get removed, only replaced.
To merge an object, pass true as the second parameter:

``` javascript
var mySettings = {...};
settings.fromObject(mySettings, true);
```

### Events
store.js supports events. Fake events to be exact. There is no way to add an event listener
for localstorage to the DOM, so store.js emulates events by checking periodically for new values.  
The default period is 500ms. You can set a custom value by setting watcherSpeed in the constructor (third parameter).  
Keep in mind that checking for new values costs CPU time, so use this setting wisely!

To add and remove listeners, use addEvent() and removeEvent().

``` javascript
settings.addEvent("color", function (value, name, store) {
	console.log("New value of color is: " + value);
});
```

You can also subscribe to all values ("*"):

``` javascript
settings.addEvent("*", function (value, name, store) {
	console.log(name + " in the store " + store + " has a new value: " + value);
});
```

The callback function gets passed three arguments: the new value, the name of the value, and the name of the store.  
Remove an event listener (you need to save a reference to the callback function when adding the event listener!):

``` javascript
var myFunction = function (value, name, store) {
	// do something
};

settings.addEvent("color", myFunction);
settings.removeEvent("color", myFunction);
```

### Reference
\* = optional

###### Constructor
``` javascript
var settings = new Store("settings", {
	"color": "blue",
	"sound": 0.8
}, 1000);

// OR...

var settings = new Store("settings", null, 100);

// OR...

var settings = new Store("settings");
```

**Parameters**: name(string), *defaults(object) = {}, *watcherSpeed(number) = 500  
**Return value**: store(object)

Creates a new Store, optionally with default values and a custom watcher speed.  
If a value is already present in localStorage, it will not be overridden.  
If a value is not already present, it will be created with the default value.  
watcherSpeed is the period after which the event emulator checks for changes.
Keep in mind that checking for new values costs CPU time, so use this setting wisely!

###### clear() (class method)
``` javascript
Store.clear();
```

**Parameters**: (none)  
**Return value**: (none)

Removes everything from localStorage.

###### name (string)
Contains the name of the store.  
Don't change it manually! Create a new store instead.

###### defaults (object)
Contains the defaults.  
You may change this value manually, but you **must** run applyDefaults() afterwards!

###### watcherSpeed (number)
Contains the period after which the event emulator checks for changes.  
You may change this value manually, but keep in mind that checking for new values costs CPU time, so use this setting wisely!

###### listeners (object)
Contains the event listeners.  
Don't tinker with it! Use addEvent() and removeEvent() instead!

###### applyDefaults()
``` javascript
settings.applyDefaults();
```

**Parameters**: (none)  
**Return value**: store(object)

Used internally to apply defaults when necessary.  
**Always** call this method when you update the defaults by changing the defaults instance variable.

###### watcher()
``` javascript
settings.watcher(true);

// or

settings.watcher();
```

**Parameters**: *force(boolean) = false  
**Return value**: store(object)

Used internally to start the watcher when adding event listeners.  
Don't tinker with it, unless you know what you're doing!

###### get()
``` javascript
var color = settings.get("color");
```

**Parameters**: name(string)  
**Return value**: value(any) or undefined

Retrieves a value. (And automatically JSON-deserializes it)  
If a value is not present in localStorage, it will return undefined.

###### set()
``` javascript
settings.set("color", "blue");
```

**Parameters**: name(string), value(any)  
**Return value**: store(object)

Sets a value. (And automatically JSON-serializes it)  
If the new value is undefined, the value will be removed from localStorage.  
Use null instead, if you want to set a value to "nothing".

###### remove()
``` javascript
settings.remove("color");
```

**Parameters**: name(string)  
**Return value**: store(object)

Removes a value.  
If the value has a default, the value will fall back to the default.

###### reset()
``` javascript
settings.reset();
```

**Parameters**: (none)  
**Return value**: store(object)

Removes all values of a store and restores the defaults.

###### toObject()
``` javascript
var object = settings.toObject();
```

**Parameters**: (none)  
**Return value**: values(object)

Puts all values of a store in an object.

###### fromObject()
``` javascript
settings.fromObject({
	"color": "brown",
	"use_everything": true,
	"be_a_hero": true
});
```

**Parameters**: values(object), *merge(boolean) = false  
**Return value**: store(object)

Replaces the store with the values from an object.  
If "merge" is false (which is the default), then the complete store will be removed, and replaced with the values. (Defaults will be restored anyways.)  
If "merge" is true, then new values will be added, different values will be replaced, all other values won't be touched.

###### addEvent()
``` javascript
settings.addEvent("color", function (value, name, store) {
	// ...
});

// OR...

settings.addEvent("*", function (value, name, store) {
	// ...
});
```

**Parameters**: selector(string), callback(function)  
**Return value**: store(object)

Adds an event listener for a specific value or all values ("*").  
The callback function gets passed three arguments: the new value, the name of the value, and the name of the store.

###### removeEvent()
``` javascript
settings.removeEvent("color", callback);

// OR...

settings.removeEvent("*", callback);
```

**Parameters**: selector(string), callback(function)  
**Return value**: store(object)

Removes an event listener for a specific value or all values ("*").  
The callback must be a pointer to a previously with addEvent() added function.

###### fireEvent()
``` javascript
settings.fireEvent("color", "blue");
```

**Parameters**: name(string), value(any)  
**Return value**: store(object)

Used internally to fire events.  
Don't tinker with it, unless you know what you're doing!

### License
store.js is licensed under the **MIT-license**.  
For details see *LICENSE.txt*
