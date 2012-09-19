# Changelog
*Feature/Change/Bug/Other*

### v3.1.2
* **Bug**: Fixed bug in removeEvent()

### v3.1.1
* **Bug**: Fixed [hasOwnProperty issue](http://www.devthought.com/2012/01/18/an-object-is-not-a-hash/)

### v3.1 [ API CHANGE! ]
* **Feature**: Added instance method applyDefaults
* **Feature**: Added instance method watcher
* **Feature**: Added instance method fireEvent
* **Feature**: Added instance varible defaults
* **Feature**: Added instance varible watcherSpeed
* **Feature**: Added support for TDD
* **Change**: Renamed method removeAll to reset
* **Change**: Methods remove, reset and fromObject now take the defaults into account
* **Bug**: Resolved dependency on Object.prototype.keys
* **Bug**: Resolved dependency on Array.prototype.each
* **Bug**: Fixed implementation of class method clear; wasn't working in IE
* **Bug**: Fixed event listeners, one function can now only be assigned once to a selector
* **Bugs**: Fixed events not firing in special cases
* **Bugs**: Fixed events firing when they should not in special cases
* **Bug**: Fixed bug in Firefox when using functions as values
* **Other**: Updated copyright

### v3.0.1
* **Change**: Improved implementation of class method

### v3.0
* **Feature**: Added event support
* **Feature**: Added clear class method
* **Other**: Small improvements

### v2.3
* **Change**: Moved var statements

### v2.2
* **Feature**: Added version number to README file
* **Feature**: Added Changelog
* **Change**: Improved code style
