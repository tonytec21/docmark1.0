/**
 * @classdesc Implementation of Promise, because as we do not know a library that will be used for development can not depend on any.
 * @class
 */
var SerproSignerPromise = (function () {
	var callbackSuccess = null;
	var callbackError = null;

	/**
	 * Then method to use um finish.
	 *
	 * @param {function} cbSuccess - Callback to use on success.
	 * @return this
	 * @memberof SerproSignerPromise
	 */
	this.success = function (cbSucess) {
		callbackSuccess = cbSucess;
		return this;
	};

	/**
	 * Then method to use um finish.
	 *
	 * @param {function} cbError - Callback to use on error.
	 * @return this
	 * @memberof SerproSignerPromise
	 */
	this.error = function (cbError) {
		callbackError = cbError;
		return this;
	};

	/**
	 * Resolve method to us on resolve event.
	 * @param {object} value - Value to send a callback setted on Then.
	 * @memberof SerproSignerPromise
	 */
	this.resolve = function (value) {
		callbackSuccess(value);
	};

	/**
	 * Return if exists callback error for this promisse.
	 *
	 * @return True if has callback error
	 * @memberof SerproSignerPromise
	 */
	this.hasCallbackError = function () {
		return (callbackError === null ? false : true);
	}

	/**
	 * Reject method to us on reject (error) event.
	 *
	 * @param {object} value - Value to send a callback setted on Then.
	 * @memberof SerproSignerPromise
	 */
	this.reject = function (value) {
		if (callbackError) {
			callbackError(value);
		}
	};

});
window.SerproSignerPromise = SerproSignerPromise;
