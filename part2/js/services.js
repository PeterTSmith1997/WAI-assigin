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
                var urlBase = 'http://localhost/WAI-assigin/part1/api/';

                /*
                 * method to retrieve courses, or, more accurately a promise which when
                 * fulfilled calls the success method
                 */
                this.getSchedules = function (day) {
                    console.log("abot to call defar");
                    console.log(day);
                    var defer = $q.defer(),             // The promise
                        scheduleUrl = urlBase + 'schedule/'+day; // add the static file containing courses to the base url
                    $http.get(scheduleUrl)                          // notice the dot to start the chain to success()
                        .then(function (response) {
                                console.log(response);
                                defer.resolve({
                                    data: response.data.data.Results,         // create data property with value from response
                                    count: response.data.data.RowCount
                                });
                            },                                                 // another dot to chain to error()
                            function (err) {
                                defer.reject(err);
                            });
                    // the call to getCourses returns this promise which is fulfilled
                    // by the .get method .success or .failure
                    console.log(scheduleUrl);

                    return defer.promise;
                };
                this.getSlotDetails =function (slot){

                    var defer = $q.defer(),             // The promise
                        slotUrl = urlBase + 'slot/'+slot; // add the static file containing courses to the base url
                    $http.get(slotUrl)                          // notice the dot to start the chain to success()
                        .then(function (response) {
                                console.log(response);
                                defer.resolve({
                                    data: response.data.data.Results,         // create data property with value from response
                                    count: response.data.data.RowCount
                                });
                            },                                                 // another dot to chain to error()
                            function (err) {
                                defer.reject(err);
                            });
                    return defer.promise;
                };

               this.getPapers = function () {
                   var defer = $q.defer(),
                       paperUrl = urlBase + 'papers/';
                   $http.get(paperUrl, {cache: true})
                       .then(function (response) {
                           console.log(response);
                           defer.resolve({
                               data: response.data.data.Results         // create data property with value from response
                           });

                       },
                       function (err) {
                           defer.reject(err);
                       });
                   return defer.promise;
               };

               this.getDays = function () {

                   var defer = $q.defer(),
                       daysUrl = urlBase + 'days/';
                   $http.get(daysUrl, {cache: true})
                       .then(function (response) {
                               console.log(response);
                               defer.resolve({
                                   data: response.data.data.Results         // create data property with value from response
                               });

                           },
                           function (err) {
                               defer.reject(err);
                           });
                   return defer.promise;
               };
            }
        ]
    );
}());