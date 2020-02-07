/**
 * Created by w16018262 on 05/02/20.
 */
(function () {
    'use strict';
    /** Service to return the data */

    angular.module('App').
    service('dataService',         // the data service name, can be anything we want
        ['$q',                     // dependency, $q handles promises, the request initially returns a promise, not the data
            '$http',                  // dependency, $http handles the ajax request
            function($q, $http) {     // the parameters must be in the same order as the dependencies

                /*
                 * var to hold the data base url
                 */
                var urlBase = 'http://localhost/api/';

                /*
                 * method to retrieve courses, or, more accurately a promise which when
                 * fulfilled calls the success method
                 */
                this.getSchedules = function () {
                    var defer = $q.defer(),             // The promise
                        scheduleUrl = urlBase + 'schedule/'; // add the static file containing courses to the base url
                    /**
                     * make an ajax get call
                     * chain calls to .success and .error which will resolve or reject the promise
                     * @param {string} urlBase The url to call, later we'll to this to pass parameters
                     * @param {object} config a configuration object, can contain parameters to pass, in this case we set cache to true
                     * @return {object} promise The call returns, not data, but a promise which only if the call is successful is 'honoured'
                     */
                    $http.get(scheduleUrl, {cache: true})                          // notice the dot to start the chain to success()
                        .then(function (response) {
                                console.log(response);
                                defer.resolve({
                                    data: response.data.data.results         // create data property with value from response
                                });
                            },                                                 // another dot to chain to error()
                            function (err) {
                                defer.reject(err);
                            });
                    // the call to getCourses returns this promise which is fulfilled
                    // by the .get method .success or .failure
                    return defer.promise;
                };
                
            }
        ]
    );
}());